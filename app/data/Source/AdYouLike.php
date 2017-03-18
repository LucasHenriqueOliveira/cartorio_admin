<?php

namespace App\Data\Source;


class AdYouLike extends \App\Data\Api {
    private $_accessToken;

    public function login() {
        $res = $this->client()->request('POST', 'https://bo-api.omnitagjs.com/bo-api/auth/login', [
            'json' => [
                'AdminHostname' => 'admin.adyoulike.com',
                'Email' => $this->username(),
                'Password' => $this->password(),
                'Realm' => '6ab7dcb6c56af1167efbbe6c11d54b90'
            ]
        ]);

        $data = json_decode($res->getBody()->getContents());
        if (!$data->Token) {
            return false;
        }
        $this->_accessToken = $data->Token;

        return true;
    }

    private function formatDate($date) {
        return (int)str_pad((new \DateTime($date))->getTimestamp(), 13, '0');
    }

    public function report($params) {
        $res = $this->client()->request('POST', 'https://bo-api.omnitagjs.com/bo-api/druid/search', [
            'json' => [
                'Intervals' => [[
                    'Begin' => $this->formatDate($params['start']),
                    'End' => $this->formatDate($params['end'])
                ]],
                'OrderBy' => 'PricePublisher',
                'OrderOp' => 'DESC',
                'Splits' => [
                    'Placement'
                ],
                'Size' => 10,
                'Columns' => [
                    'Name',
                    'PricePublisher',
                    'NATIVE_PUBLISHER_VISIBLE_ECPM',
                    'INVENTORY',
                    'INSERTION',
                    'VISIBLE_INSERTION',
                    'INVENTORY_PASSBACK',
                    'UniqueVisitors'
                ],
                'View' => 'SIMPLE_PUBLISHER',
                'AddTotalRow' => true,
                'TimeZone' => 'America/Los_Angeles',
                'Granularity' => 'all'
            ],
            'headers' => [
                'X-AYL-Auth-Token' => $this->accessToken()
            ]
        ]);
        $data = json_decode($res->getBody()->getContents());
        return $data;
    }

    public function accessToken() {
        return $this->_accessToken;
    }
}