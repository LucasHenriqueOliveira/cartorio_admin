<?php

namespace App\Data\Source;


class AdsNative extends \App\Data\Scrape {
    private $_accessToken;

    public function login() {
        $res = $this->client()->request('POST', 'https://console.adsnative.com/api/v1/oauth/token/', [
            'cookies' => $this->jar(),
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->username(),
                'client_secret' => $this->password()
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);

        $data = json_decode($res->getBody()->getContents());
        if (!$data->access_token) {
            return false;
        }
        $this->_accessToken = $data->access_token;

        return true;
    }

    private function formatDate($date) {
        return (new \DateTime($date))->format('Y-m-d');
    }

    public function report($params) {
        $res = $this->client()->request('GET', 'https://console.adsnative.com/api/v2/report/provider', [ //campaign, advertiser, placement, site, or provider
            'cookies' => $this->jar(),
            'query' => [
                'start_date' => $this->formatDate($params['start']),
                'end_date' => $this->formatDate($params['end']),
                'granularity' => 'days', // months, hours
                'render_format' => 'json',
                'data_type' => 'total' // series, full
            ],
            'headers' => [
                'Authorization' => 'Bearer '.$this->accessToken()
            ]
        ]);
        $data = json_decode($res->getBody());
        return $data;
    }


    public function accessToken() {
        return $this->_accessToken;
    }
}

//{"access_token":"7ca0d376-8c44-44ba-8f1a-cf801373fe06","token_type":"bearer","expires_in":1295999,"scope":"read/write"}
//https://api.sovrn.com/account/user
//Authorization:Bearer 7ca0d376-8c44-44ba-8f1a-cf801373fe06
//https://api.sovrn.com/earnings/breakout/all?iid=13068347&startDate=1483228800000&endDate=1485907199999&site=wtf1.co.uk&country=US
//https://api.sovrn.com/overview/all?site=wtf1.co.uk&startDate=1483228800000&endDate=1485907199999&iid=13068347