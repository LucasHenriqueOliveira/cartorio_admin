<?php

namespace App\Data\Source;


class TripleLift extends \App\Data\Api {
    private $_accessToken;

    public function login() {
        $res = $this->client()->request('POST', 'http://api.triplelift.com/login', [
            'json' => [
                'username' => $this->username(),
                'password' => $this->password()
            ]
        ]);

        $data = json_decode($res->getBody()->getContents());
        if (!$data->token) {
            return false;
        }
        $this->_accessToken = $data->token;

        return true;
    }

    private function formatDate($date) {
        return (new \DateTime($date))->format('Y-m-d H:i:s');
    }

    public function report($params) {
        /*
        $res = $this->client()->request('GET', 'https://api.triplelift.com/native_advertising/publisher', [
            'headers' => [
                'Auth-token' => $this->accessToken()
            ]
        ]);
        */
        $res = $this->client()->request('POST', 'https://api.triplelift.com/reporting/v2/pub_side/legacy', [
            'body' => '{"dimensions":["ymd","publisher_id","publisher_name","domain","placement_id","placement_name","country_name","device_type"],"metrics":["rendered","publisher_revenue"],"filters":{},"end_date":"'.$this->formatDate($params['end']).'","start_date":"'.$this->formatDate($params['start']).'","report_for":"publisher","sort":{},"publisher_id":'.$params['publisher'].'}',
            'headers' => [
                'Auth-token' => $this->accessToken(),
                'Content-Type' => 'application/json'
            ]
        ]);
        $data = json_decode($res->getBody()->getContents());
        return $data;
    }


    public function accessToken() {
        return $this->_accessToken;
    }
}