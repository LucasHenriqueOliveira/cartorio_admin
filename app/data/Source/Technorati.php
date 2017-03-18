<?php

namespace App\Data\Source;

class Technorati extends \App\Data\Scrape {
    private $_token;
    private $_publisherId;

    public function login() {
        $res = $this->client()->request('POST', 'https://mycontango.technorati.com/authenticate', [
            'cookies' => $this->jar(),
            'json' => [
                'email' => $this->username(),
                'password' => $this->password()
            ]
        ]);
        $data = json_decode($res->getBody());
        if (!$res->token) {
            return false;
        }
        $this->_token = $res->token;
        $this->_publisherId = $res->user->publisher_id;
    }

    public function dashboard() {
        // there are also reports exports wich will work better for larger data sets
        // this is an examle of how to get the data at the dashboard page

        $res = $this->client()->request('GET', 'https://mycontango.technorati.com/api/dashboards/?type=NETWORK', [
            'cookies' => $this->jar(),
            'headers' => [
                'token' => $this->token()
            ]
        ]);
        $data = json_decode($res->getBody());

        $res = $this->client()->request('GET', 'https://mycontango.technorati.com/api/dashboards/'.$data[0]->id.'?publisher_id='.$this->publisherId(), [
            'cookies' => $this->jar(),
            'headers' => [
                'token' => $this->token()
            ]
        ]);
        $data = json_decode($res->getBody());

        $widgets = [];
        foreach ($data->widgets as $widget) {
            // need to parse stuff
            $dom = new PHPHtmlParser\Dom;
            $code = $dom->load($widget['code']);
            $widgets[] = [
                'title' => $widget['title'],
                'code' => $code
            ];
        }
        return $widgets;
    }

    public function token() {
        return $this->_token;
    }

    public function publisherId() {
        return $this->_publisherId;
    }
}