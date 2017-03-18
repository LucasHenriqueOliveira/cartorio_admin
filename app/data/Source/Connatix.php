<?php

// note that the documenation at https://wiki.appnexus.com/display/sdk/Publisher+Analytics+Report is wrong in a few ways

namespace App\Data\Source;

class Connatix extends \App\Data\Scrape {
    public function login() {
        $res = $this->client()->request('POST', 'https://console.connatix.com/api/account/Login', [
            'cookies' => $this->jar(),
            'json' => [
                'Username' => $this->username(),
                'Password' => $this->password()
            ],
            'headers' => [
                'User-Agent' => $this->agent(),
            ]
        ]);

        $data = json_decode($res->getBody()->getContents());
        if (!$data->Success) {
            return false;
        }

        return true;
    }

    private function formatDate($date) {
        return (new \DateTime($date))->format('Y-m-d');
    }

    public function report($params) {
        $res = $this->client()->request('POST', 'https://console-old.connatix.com/Analytics/Get', [
            'cookies' => $this->jar(),
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            // @todo: convert to php array to edit easier
            'body' => '{"shareId":"","type":"PublisherOverview","startDate":"'.$this->formatDate($params['start']).'","endDate":"'.$this->formatDate($params['end']).'","dimensionSelection":[{"dimension":{"name":"Date","hidden":true},"selections":[],"selectionsAll":true,"showIDs":false}],"networkTargeting":"Public","userId":""}'
        ]);
        $data = json_decode($res->getBody()->getContents());

        return $data;
    }
}