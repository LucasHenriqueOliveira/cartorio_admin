<?php

namespace App\Data\Source;

use Illuminate\Support\Facades\DB;

class Sovrn extends \App\Data\Scrape
{
    private $_accessToken;
    private $_websites;

    // ================================================
    public function login()
    {
        $res = $this->client()->request('POST', 'https://api.sovrn.com/oauth/token', [
            'cookies' => $this->jar(),
            'form_params' => [
                'grant_type' => 'password',
                'username' => $this->username(),
                'password' => $this->password(),
                'client_id' => 'sovrn',
                'client_secret' => 'sovrn'
            ],
            'headers' => [
                'User-Agent' => $this->agent(),
                'Referer' => 'https://meridian.sovrn.com/',
                'Origin' => 'https://meridian.sovrn.com',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
            ]
        ]);
        $data = json_decode($res->getBody());
        if (!$data->access_token) {
            return false;
        }
        $this->_accessToken = $data->access_token;

        $res = $this->client()->request('GET', 'https://api.sovrn.com/account/user', [
            'cookies' => $this->jar(),
            'headers' => [
                'Authorization' => 'Bearer '.$this->accessToken(),
                'Referer' => 'https://meridian.sovrn.com/',
                'Origin' => 'https://meridian.sovrn.com',
                'User-Agent' => $this->agent()
            ]
        ]);
        $data = json_decode($res->getBody());
        if (!$data->websites) {
            return false;
        }
        $this->_websites = $data->websites;

        return true;
    }

    public static function mapGeo($input) {
        $retval = NULL;
        $input_lc = strtolower($input);
        if (strcmp($input, "us") == 0) {
            $retval = "us";
        } else if (strcmp($input, "other") == 0) {
            $retval = "in";
        }
        // @TODO - More validation?
        return $retval;
    }


    // ================================================
    public function harvestSovrn($params = [])
    {
        $result = $this->client()->request(
            'GET',
            'https://api.sovrn.com/download/adstats/csv'
            .'?site=all%20traffic'
            .'&startDate='.$this->millisEpoch($params['start'])
            .'&endDate='.$this->millisEpoch($params['end'])
            .'&view=Yesterday'
            .'&breakout=true' // setting this true adds a date column
            .'&includeTagsWithNoRequests=false'
            .'&trafficType=DOMESTIC_AND_INTERNATIONAL'
            .'&country=US'
            .'&currency=USD'
            .'&iid=13068347',
            [
                'cookies' => $this->jar(),
                'headers' => [
                    'Authorization' => 'Bearer '.$this->accessToken(),
                    'Referer' => 'https://meridian.sovrn.com/',
                    'Origin' => 'https://meridian.sovrn.com'
                ]
            ]
        );

        $csvData = $result->getBody()->getContents();
        return($this->csvToAssoc($csvData, 6));
    }

    // ================================================
    public function setup($params = []) {

        ini_set('max_execution_time', 180);

        $login = $this->login();

        $report = $this->harvestSovrn([
            'start' => $params['start'],
            'end' => $params['end']
        ]);

        $result = [];

        $source = $this->getSource($params['source']);
        // $params['source'] = 2 for Sovrn.
        // I assume the goal is to 'meta' this function eventually at which point we will move it outside
        // the scope of the individual ingestion modules.  That would explain why we are passing the source val
        // in as a parameter.
        $line_item = $this->getLineItem($params['source']);

        //metrics
        foreach($report as $item) {
            if($item['Tag Name']){
                $index = $this->searchForLineItem($item['Tag Name'], $line_item);

                if(!is_null($index)){
                    $arrData['date'] = $this->formatDateTime($item['Date']);
                    $arrData['publisher_id'] = $line_item[$index]['publisher_id'];
                    $arrData['device'] = $line_item[$index]['device'];
                    $geoMapped = Sovrn::mapGeo($item['Traffic']);
                    $arrData['geo'] = $geoMapped;
                    $arrData['product_type_id'] = $line_item[$index]['product_type_id'];
                    $arrData['slot'] = $line_item[$index]['slot'];
                    $arrData['source_id'] = $line_item[$index]['source_id'];
                    $arrData['ad_size'] = $item[$source->ad_size_field];
                    $arrData['impressions'] = $item[$source->impressions_field];

                    // search client_fraction in revenue_share table
                   $revenue_share = $this->getRevenueShare($arrData['publisher_id']);
                   $arrData['net_revenue'] = $item[$source->gross_revenue_field] * (float)$revenue_share->client_fraction;

                    // We really want an 'upsert' operation. We can't be sure we haven't run this ingestion of this
                    // same data before.  Therefore we should simply replace it rather than add it again if this
                    // is a repeat.
                    // https://chartio.com/resources/tutorials/how-to-insert-if-row-does-not-exist-upsert-in-mysql/
                    $keys = implode(', ', array_keys($arrData));
                    $values = "'" .implode("','", array_values($arrData)) . "'";
                    $DIRECTIVE = 'REPLACE INTO metric_by_source_and_full_split_daily('.$keys.') VALUES ('.$values.')';
                    array_push($result, DB::insert($DIRECTIVE, $arrData));
                }
            }
        }

        $this->refreshMetrics($params);
        
        return($result);
    }

    // ================================================
    private function millisEpoch($date)
    {
        /* https://www.epochconverter.com/programming/php#date2epoch */
        $result = new \DateTime($date); /* format: MM/DD/YYYY */
        return(($result->format('U'))*1000);
    }

    // ================================================
    private function formatDateTime($monthLeadingSlashDelim) {
        $dt = new \DateTime($monthLeadingSlashDelim);
        return $dt->format("Y-m-d")." 00:00:00";
    }

    // ================================================
    public function earnings($params)
    {
        $res = $this->client()->request(
            'GET',
            'https://api.sovrn.com/earnings/breakout/all'
            .'?iid=13068347'
            .'&startDate='.$this->millisEpoch($params['start'])
            .'&endDate='.$this->millisEpoch($params['start'])
            .'&site='.$params['site']
            .'&country=US',
            [
                'cookies' => $this->jar(),
                'headers' =>
                [
                    'Authorization' => 'Bearer '.$this->accessToken(),
                    'Referer' => 'https://meridian.sovrn.com/',
                    'Origin' => 'https://meridian.sovrn.com/'
                ]
            ]
        );
        $data = json_decode($res->getBody());
        return $data;
    }

    // ================================================
    // I should probably fix the hardcoded IID?  Or is it fine?  It seems dangerous.  What if Publisher Desk
    // ever changes the credentials they're using with Sovrn?
    public function overview($params)
    {
        $res = $this->client()->request('GET', 'https://api.sovrn.com/overview/all?site='.$params['site'].'&startDate='.$this->millisEpoch($params['start']).'&endDate='.$this->millisEpoch($params['start']).'&iid=13068347', [
            'cookies' => $this->jar(),
            'headers' => [
                'Authorization' => 'Bearer '.$this->accessToken(),
                'Referer' => 'https://meridian.sovrn.com/',
                'Origin' => 'https://meridian.sovrn.com/'
            ]
        ]);
        $data = json_decode($res->getBody());
        return $data;
    }

    // ================================================
    public function accessToken()
    {
        return $this->_accessToken;
    }

    // ================================================
    public function websites()
    {
        return $this->_websites;
    }
}

//{"access_token":"7ca0d376-8c44-44ba-8f1a-cf801373fe06","token_type":"bearer","expires_in":1295999,"scope":"read/write"}
//https://api.sovrn.com/account/user
//Authorization:Bearer 7ca0d376-8c44-44ba-8f1a-cf801373fe06
//https://api.sovrn.com/earnings/breakout/all?iid=13068347&startDate=1483228800000&endDate=1485907199999&site=wtf1.co.uk&country=US
//https://api.sovrn.com/overview/all?site=wtf1.co.uk&startDate=1483228800000&endDate=1485907199999&iid=13068347
