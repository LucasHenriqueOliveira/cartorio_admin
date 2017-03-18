<?php

namespace App\Data\Source;

use Illuminate\Support\Facades\DB;

class Rubicon extends \App\Data\Scrape {
    private $_token;

    public function login() {

        try {
            $res = $this->client()->request('POST', 'https://login.rubiconproject.com//form/login/', [
                'cookies' => $this->jar(),
                'headers' => [
                    'User-Agent' => $this->agent()
                ],
                'form_params' => [
                    'username' => $this->username(),
                    'password' => $this->password(),
                    'redirect_uri' => 'https://revv.rubiconproject.com'
                ],
            ]);
        } catch (\Exception $e) {
            // failed to log in
            return false;
        }

        $res = $this->client()->request('GET', 'https://login.rubiconproject.com/oauth/authorize?client_id=9&response_type=token&redirect_uri=https://platform.rubiconproject.com/&url_fragment=', [
            'cookies' => $this->jar(),
            'headers' => [
                'User-Agent' => $this->agent(),
                'Referer' => 'https://platform.rubiconproject.com/'
            ],
            'allow_redirects' => [
                'referer'         => true,
                'track_redirects' => true
            ]
        ]);

        // failed to get token
        if (!$res->getHeaders()['X-Guzzle-Redirect-History']) {
            return false;
        }

        parse_str(parse_url($res->getHeaders()['X-Guzzle-Redirect-History'][0])['fragment'], $token);
        $this->_token = $token['access_token'];
        return true;
    }

    private function formatDate($date) {
        return (new \DateTime($date))->format('Y-m-d');
    }

    private function formatDateTime($date) {
        return $date. ' 00:00:00';
    }

    public function report($params = []) {
        $res = $this->client()->request('POST',
            'https://platform.rubiconproject.com/services/reporting/actions/export/form/?access_token='.$this->token(), [
            'form_params' => [
                // @todo: convert to a php array to its easier to read
                'exportReport' => '{
                    "report":{
                        "label":"Agency Enterprise",
                        "currency":"USD",
                        "dateRange":{
                            "dateRangeString":"custom",
                            "start":"'.$this->formatDate($params['start']).'",
                            "end":"'.$this->formatDate($params['end']).'",
                            "reportDate":{
                                "start":"'.$this->formatDate($params['start']).'",
                                "end":"'.$this->formatDate($params['end']).'"
                            }
                        },
                        "columns":[
                            {"id":"Time_Date",
                            "label":"Date",
                            "sortDirection":null,
                            "displayType":"datetime",
                            "filterType":"none",
                            "isFeature":true,
                            "isWeighted":false,
                            "isHistogram":false,
                            "dataSources":["blr","standard"],
                            "deprecated":false
                            },
                            {"id":"Zone_Name",
                            "label":"Zone",
                            "sortDirection":null,
                            "displayType":"string",
                            "filterType":"search",
                            "isFeature":true,
                            "isWeighted":false,
                            "isHistogram":false,
                            "dataSources":["blr","standard"],
                            "deprecated":false
                            },
                            {"id":"Performance_NetworkImps",
                            "label":"Paid Impressions",
                            "sortDirection":null,
                            "displayType":"integer",
                            "filterType":"num",
                            "isFeature":false,
                            "isWeighted":false,
                            "isHistogram":false,
                            "dataSources":["standard"],
                            "deprecated":false
                            },
                            {"id":"Performance_NetworkRevenue",
                            "label":"Publisher Gross Revenue",
                            "sortDirection":null,
                            "displayType":"money",
                            "filterType":"none","isFeature":false,
                            "isWeighted":false,
                            "isHistogram":false,
                            "dataSources":["standard"],
                            "deprecated":false
                            },
                            {"id":"Site_Name",
                            "label":"Site",
                            "sortDirection":null,
                            "displayType":"string",
                            "filterType":"search",
                            "isFeature":true,
                            "isWeighted":false,
                            "isHistogram":false,
                            "dataSources":["blr","standard"],
                            "deprecated":false
                            },
                            {"id":"Size_Dimensions",
                            "label":"Size Dimensions",
                            "sortDirection":null,
                            "displayType":"string",
                            "filterType":"enum",
                            "isFeature":true,
                            "isWeighted":false,
                            "isHistogram":false,
                            "dataSources":["standard"],
                            "deprecated":false
                            }],
                        "filters":[],
                        "excludes":[],
                        "limit":0,
                        "graph":{
                            "id":26796860,
                            "type":"line",
                            "axes":{
                                "x":null,
                                "y":[]
                            }
                        },
                        "dataSource":"standard",
                        "dataLastUpdated":null,
                        "groupBy":[],
                        "schedule":{
                            "frequency":"none",
                            "hour":"0",
                            "dayOfTheWeek":null,
                            "dayOfTheMonth":null,
                            "emails":[],
                            "format":"csv"
                        },
                        "status":"active",
                        "noRevenueStatus":true,
                        "hasEstimatedData":"undefined",
                        "objectMetaData":{
                            "sparse":false
                        }
                    },
                    "format":"csv",
                    "timestamp":1488317507896
                }'
            ]
        ]);

        $data = $this->csvToAssoc($res->getBody()->getContents());
        return $data;
    }


    public function token() {
        return $this->_token;
    }

    public function setup($params = []) {

        ini_set('max_execution_time', 180);

        try {
            $res = $this->login();
        } catch (\Exception $e) {
            return $this->setSourceStatus($params['source'], 'Server Error', 2, $e->getMessage());
        }

        try {
            $report = $this->report([
                'start' => $params['start'],
                'end' => $params['end']
            ]);
        } catch (\Exception $e) {
            return $this->setSourceStatus($params['source'], 'Server Error', 3, $e->getMessage());
        }

        $source = $this->getSource($params['source']);
        $line_item = $this->getLineItem($params['source']);

        $this->checkImportedDataLength($report);

        if(!$report) {
            return $this->setSourceStatus($params['source'], 'Server Error', 5, 'No data returned');
        }

        //metrics
        foreach ($report as $item) {
            if($item['Zone']){
                $index = $this->searchForLineItem($item['Zone'], $line_item);

                if(!is_null($index)){
                    $arrData['date'] = $this->formatDateTime($item['Date']);;
                    $arrData['publisher_id'] = $line_item[$index]['publisher_id'];
                    $arrData['device'] = $line_item[$index]['device'];
                    $arrData['geo'] = $line_item[$index]['geo'];
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
                    DB::insert($DIRECTIVE,$arrData);
                } else {
                    $publisher = $this->getPublisher($item['publisher_id']);

                    if(!count($publisher)){
                        $message = $item['Zone'];
                        $this->setSourceStatus($params['source'], 'Server Error', 6, $message);
                    } else {
                        $message = $item['publisher_id'] . '-' . $publisher->name;
                        $this->setSourceStatus($params['source'], 'Server Error', 7, $message);
                    }
                }
            }
        }

        $this->refreshMetrics($params);
        return true;
    }
}