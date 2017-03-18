<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class Source {
    private $_username;
    private $_password;
    private $_client;

    public function __construct($params = []) {
        $this->_client = new \GuzzleHttp\Client;

        if (array_key_exists('username', $params)) {
            $this->_username = $params['username'];
        }

        if (array_key_exists('password', $params)) {
            $this->_password = $params['password'];
        }
    }

// I didn't add this function here, but I did find something very similar on stackexchange.
// http://stackoverflow.com/questions/4801895/csv-to-associative-array
//
// Second, $skip, argument allows dropping leading rows.
    public function csvToAssoc($data, $skip = 0) {
        $rows = array_map('str_getcsv', explode("\n", $data));
        for($i=0; $i<$skip; $i++) {
            // pop the first element of $rows
            array_shift($rows);
        }
        $header = array_shift($rows);
        $csv = [];

        foreach($rows as $row) {
            $line = [];
            for ($x=0; $x < count($row); $x++) {
                if (!$header[$x]) {
                    continue;
                }
                $line[$header[$x]] = $row[$x];
            }
            $csv[] = $line;
        }
        return $csv;
    }

    public function checkImportedDataLength($data) {
        // @TODO - add check data length
        return true;
    }

    public function getSource($source_id) {
        // @TODO - Cache this - similar to how getLineItem / searchForLineItem work
        return DB::select("SELECT * FROM source WHERE `source_id` = :source_id LIMIT 1", ['source_id' => $source_id])[0];
    }

    public function getRevenueShare($publisher_id) {
        // @TODO - Cache this - similar to how getLineItem / searchForLineItem work
        return DB::select("SELECT * FROM `revenue_share` WHERE `publisher_id` = :publisher_id LIMIT 1", ['publisher_id' => $publisher_id])[0];
    }

    public function getPublisher($publisher_id) {
        // @TODO - Cache this - similar to how getLineItem / searchForLineItem work
        return DB::select("SELECT * FROM `publisher` WHERE `publisher_id` = :publisher_id LIMIT 1", ['publisher_id' => $publisher_id])[0];
    }

    public function getLineItem($source_id) {
        $line_item = [];
        $result = DB::select("SELECT * FROM line_item WHERE `source_id` = :source_id", ['source_id' => $source_id]);

        foreach ($result as $item) {
            $item = json_decode(json_encode($item), true);
            array_push($line_item, $item);
        }
        return $line_item;
    }

    public function setSourceStatus($source_id, $status, $id, $message) {
        return DB::insert('REPLACE INTO source_status (source_id, status, error_code_id, error_description, date) VALUES (?, ?, ?, ?, ?)',
           [$source_id, $status, $id, $message, date('Y-m-d h:i:s')]);
    }

    public function searchForLineItem($id, $array) {
        foreach ($array as $key => $val) {
            if ($val['line_item'] === $id) {
                return $key;
            }
        }
        return null;
    }

    public function setByFullSplit($arrData) {
        extract($arrData);

        $metrics_daily = DB::select("SELECT * FROM metric_by_full_split_daily WHERE `publisher_id` = :id AND `date` = :date AND `device` = :device AND `product_type_id` = :product_type_id AND `geo` = :geo AND `slot` = :slot AND `ad_size` = :ad_size",
        ['id' => $publisher_id, 'date' => $date, 'device' => $device, 'product_type_id' => $product_type_id, 'geo' => $geo, 'slot' => $slot, 'ad_size' => $ad_size]);

        if(count($metrics_daily)){
            $metrics_daily = $metrics_daily[0];

            $impressions = $impressions + $metrics_daily->impressions;
            $revenue = $net_revenue + $metrics_daily->net_revenue;

            DB::update('UPDATE metric_by_full_split_daily SET `impressions` = ?, `net_revenue` = ? WHERE `publisher_id` = ? AND `date` = ? AND `device` = ? AND `product_type_id` = ? AND `geo` = ? AND `slot` = ? AND `ad_size` = ?',
            [$impressions, $net_revenue, $publisher_id, $date, $device, $product_type_id, $geo, $slot, $ad_size]);

        } else {

            DB::insert('INSERT INTO metric_by_full_split_daily (date, publisher_id, impressions, device, product_type_id, geo, slot, ad_size, net_revenue) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [$date, $publisher_id, $impressions, $device, $product_type_id, $geo, $slot, $ad_size, $net_revenue]);
        }
    }

    public function refreshMetrics($params) {
        $metrics_source_daily = DB::select("SELECT * FROM metric_by_source_and_full_split_daily WHERE `date` BETWEEN :start AND :end",
        ['start' => $params['start'], 'end' => $params['end']]);

        foreach ($metrics_source_daily as $item) {
            $arrData['date'] = $item->date;
            $arrData['publisher_id'] = $item->publisher_id;
            $arrData['device'] = $item->device;
            $arrData['geo'] = $item->geo;
            $arrData['product_type_id'] = $item->product_type_id;
            $arrData['slot'] = $item->slot;
            $arrData['ad_size'] = $item->ad_size;
            $arrData['impressions'] = $item->impressions;
            $arrData['net_revenue'] = $item->net_revenue;

            $this->setByFullSplit($arrData);
        }
    }

    public function statistics($params = []) {

        $data = [];
        $d = [
            'date1' => $params['start'],
            'date2' => $params['end']
        ];

        if ($params['publisher']) {
            $publisher = 'AND publisher_id=:publisher';
            $d['publisher'] = $params['publisher'];
        }

        $q = '
            SELECT FORMAT(sum(net_revenue),2) as revenue, sum(impressions) as impressions, FORMAT(1000* sum(net_revenue) / sum(impressions),2) as ecpm
            FROM metric_by_full_split_daily
            WHERE
                `date` BETWEEN :date1 AND :date2
                '.$publisher.'
        ';

        $stats = DB::select($q, $d);

        $data['impressions'] = number_format($stats[0]->impressions);
        $data['revenue'] = $stats[0]->revenue;
        $data['ecpm'] = $stats[0]->ecpm;
        return $data;
    }

    public function dashboard($params) {

        $res['error'] = false;

        $params['start'] = (new \DateTime($params['start']))->format('Y-m-d H:i:s');
        $params['end'] = (new \DateTime($params['end']))->format('Y-m-d H:i:s');

        //setup queries
        $d = [
            'date1' => $params['start'],
            'date2' => $params['end']
        ];

        if ($params['publisher']) {
            $publisher = 'AND publisher_id=:publisher';
            $d['publisher'] = $params['publisher'];
        }

        // statists week
        $res['statics_week'] = $this->statistics($params);

        // statists last week
        $res['statics_last_week'] = $this->statistics(array_merge($params, [
            'start' => (new \DateTime($params['start']))->modify('-1 week')->format('Y-m-d H:i:s'),
            'end' => (new \DateTime($params['end']))->modify('-1 week')->format('Y-m-d H:i:s')
        ]));

        // impressions
        $data = [];
        $result = DB::select('
            SELECT sum(`impressions`) as impressions, DATE_FORMAT(`date`,\'%b %d\') as `date`
            FROM `metric_by_full_split_daily`
            WHERE
                `date` BETWEEN :date1 AND :date2
                '.$publisher.'
            GROUP BY `date`
        ', $d);

        foreach ($result as $impression) {
            $data['date'] = $impression->date;
            $data['impression'] = $impression->impressions;
            $impressions[] = $data;
        }
        $res['impressions'] = $impressions;


        // effective Revenue
        $data = [];
        $result = DB::select('
            SELECT device, name as product_name, FORMAT(sum(net_revenue),2) as revenue, sum(impressions) as impressions, FORMAT(1000* sum(net_revenue) / sum(impressions),2) as ecpm
            FROM metric_by_full_split_daily as a INNER JOIN product_type as b using (product_type_id)
            WHERE
                `date` BETWEEN :date1 AND :date2
                '.$publisher.'
            GROUP BY `device`, `product_name`
        ', $d);

        foreach ($result as $effective_revenue) {
            $data['device'] = $effective_revenue->device;
            $data['ecpm'] = $effective_revenue->ecpm;
            $data['revenue'] = $effective_revenue->revenue;
            $data['impressions'] = $effective_revenue->impressions;
            $data['product_name'] = $effective_revenue->product_name;
            $effective_revenues[] = $data;
        }

        $result = DB::select('
            SELECT name as product_name, FORMAT(sum(net_revenue),2) as revenue, sum(impressions) as impressions, FORMAT(1000* sum(net_revenue) / sum(impressions),2) as ecpm
            FROM metric_by_full_split_daily as a INNER JOIN product_type as b using (product_type_id)
            WHERE
                `date` BETWEEN :date1 AND :date2
                '.$publisher.'
            GROUP BY `product_name`
        ', $d);

        foreach ($result as $effective_revenue) {
            $data['device'] = 'all';
            $data['ecpm'] = $effective_revenue->ecpm;
            $data['revenue'] = $effective_revenue->revenue;
            $data['impressions'] = $effective_revenue->impressions;
            $data['product_name'] = $effective_revenue->product_name;
            $effective_revenues[] = $data;
        }

        $res['effective_revenue'] = $effective_revenues;


        // site stats
        $data = [];
        $result = DB::select('
            SELECT geo, FORMAT(sum(net_revenue),2) as revenue, sum(impressions) as impressions, FORMAT(1000* sum(net_revenue) / sum(impressions),2) as ecpm
            FROM `metric_by_full_split_daily`
            WHERE
                `date` BETWEEN :date1 AND :date2
                '.$publisher.'
            GROUP BY `geo`
        ', $d);

        foreach ($result as $site_stats) {
            $data['impressions'] = $site_stats->impressions;
            $data['ecpm'] = $site_stats->ecpm;
            $data['geo'] = $site_stats->geo;
            $sites_stats[] = $data;
        }
        $res['site_stats'] = $sites_stats;


        // earned revenue
        $data = [];
        $result = DB::select('
            SELECT name as product_name, sum(`net_revenue`) as revenue, product_type_id, device
            FROM `metric_by_full_split_daily` as a INNER JOIN product_type as b using (product_type_id)
            WHERE
                `date` BETWEEN :date1 AND :date2
                '.$publisher.'
            GROUP BY `device`, `product_name`
        ', $d);

        foreach ($result as $earned_revenue) {
            $data['revenue'] = $earned_revenue->revenue;
            $data['product'] = ucfirst($earned_revenue->product_name);
            $data['device'] = $earned_revenue->device;
            $earneds_revenue[] = $data;
        }

        $res['earned_revenue'] = $earneds_revenue;


        // performance
        $data = [];
        $result = DB::select('
            SELECT CASE
                    WHEN `slot` LIKE "%banner%" THEN "Banner Ad"
                    WHEN `slot` LIKE "%box%" THEN "Box Ad"
                    WHEN `slot` LIKE "%sky%" THEN "Sky Ad"
                END AS `slotp`, device, FORMAT(1000* sum(net_revenue) / sum(impressions),2) as ecpm
            FROM `metric_by_full_split_daily`
            WHERE
                `date` BETWEEN :date1 AND :date2
                '.$publisher.'
            GROUP BY `slotp`, `device`
        ', $d);

        foreach ($result as $performance) {
            $data['slot'] = $performance->slotp;
            $data['ecpm'] = $performance->ecpm;
            $data['device'] = $performance->device;
            $performances[] = $data;
        }

        $result = DB::select('
            SELECT CASE
                    WHEN `slot` LIKE "%banner%" THEN "Banner Ad"
                    WHEN `slot` LIKE "%box%" THEN "Box Ad"
                    WHEN `slot` LIKE "%sky%" THEN "Sky Ad"
                END AS `slotp`, FORMAT(1000* sum(net_revenue) / sum(impressions),2) as ecpm
            FROM `metric_by_full_split_daily`
            WHERE
                `date` BETWEEN :date1 AND :date2
                '.$publisher.'
            GROUP BY `slotp`
        ', $d);

        foreach ($result as $performance) {
            $data['slot'] = $performance->slotp;
            $data['ecpm'] = $performance->ecpm;
            $data['device'] = 'all';
            $performances[] = $data;
        }

        $res['performance'] = $performances;

        return $res;
    }

    public function username() {
        return $this->_username;
    }

    public function password() {
        return $this->_password;
    }

    public function client() {
        return $this->_client;
    }
}