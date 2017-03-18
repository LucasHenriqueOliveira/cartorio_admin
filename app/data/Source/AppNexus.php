<?php

// note that the documenation at https://wiki.appnexus.com/display/sdk/Publisher+Analytics+Report is wrong in a few ways

namespace App\Data\Source;

class AppNexus extends \App\Data\Scrape {
    private $_token;
    private $_uuid;

    public function login() {
        $res = $this->client()->request('POST', 'https://api.appnexus.com/auth', [
            'cookies' => $this->jar(),
            'json' => [
                'auth' => [
                    'username' => $this->username(),
                    'password' => $this->password()
                ]
            ]
        ]);
        $data = json_decode($res->getBody());
        if (!$data->response->token) {
            return false;
        }
        $this->_token = $data->response->token;
        $this->_uuid = $data->response->dbg_info->uuid;

        return true;
    }

    public function report($params) {
        $res = $this->client()->request('GET', 'http://api.appnexus.com/report', [
            'cookies' => $this->jar(),
            'json' => [
                'report' => [
                    'report_type' => 'publisher_analytics',
                    'report_interval' => 'yesterday',
                    'columns' => [
                        'geo_country',
                        'imp_type',
                        'placement',
                        'clicks',
                        'total_convs',
                        'publisher_revenue'
                    ],
                    'name' => 'Publisher Analytics - devin-test-01'
                ]
            ]
        ]);
        $data = json_decode($res->getBody());
        $id = $data->response->reports[0]->id;

        for ($i=0; $i <= 20; $i++) {

            $res = $this->client()->request('GET', 'http://api.appnexus.com/report?id=' . $id, [
                'cookies' => $this->jar()
            ]);
            $data = json_decode($res->getBody());
            if ($data->response->execution_status == 'ready') {
                break;
            }

            if ($i == 20) {
                return false;
            }
            sleep(1);
        }

        $res = $this->client()->request('GET', 'http://api.appnexus.com/report-download?id=' . $id, [
            'cookies' => $this->jar()
        ]);
        $data = json_decode($res->getBody());

        return $data;
    }

    public function token() {
        return $this->_token;
    }

    public function uuid() {
        return $this->_uuid;
    }
}