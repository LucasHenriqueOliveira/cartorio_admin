<?php

namespace App\Data\Source;

class Swoop extends \App\Data\Scrape {
    private $_websites;
    private $_sites;

    public function login() {
        $res = $this->client()->request('GET', 'https://portal.swoop.com/', [
            'cookies' => $this->jar(),
            'headers' => [
                'User-Agent' => $this->agent()
            ]
        ]);

        $body = $res->getBody()->getContents();

        $dom = new \PHPHtmlParser\Dom;
        $dom->load($body);
        $token = $dom->find('input[name="authenticity_token"]')[0]->value;
        if (!$token) {
            return false;
        }

        $res = $this->client()->request('POST', 'https://portal.swoop.com/accounts/sign_in', [
            'cookies' => $this->jar(),
            'form_params' => [
                'utf8' => '✓',
                'authenticity_token' => $token,
                'account' => [
                    'email' => $this->username(),
                    'password' => $this->password(),
                    'remember_me' => '0',
                ],
                'commit' => 'sign in'
            ],
            'headers' => [
                'User-Agent' => $this->agent(),
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Referer' => 'https://portal.swoop.com/'
            ],
            'allow_redirects' => [
                'referer'         => true,
                'track_redirects' => true
            ]
        ]);

        $body = $res->getBody()->getContents();

        $dom = new \PHPHtmlParser\Dom;
        $dom->load($body);
        $list = $dom->find('#domainConfigList');

        // failed to login
        if (!$list[0]) {
            return false;
        }

        $data = [];

        $domains = $dom->find('.domainConfigListing');
        foreach ($domains as $domain) {
            $user = preg_match_all('/sites\/(.*)\/edit/i', preg_$domain->find('a')->getAttribute('href'), $matches);

            $d = [
                'title' => $domain->find('span[class="title"]')->innerHTML,
                'id' => $domain->find('span[class="id"]')->innerHTML,
                'site' => $matches[1][0]
            ];

            $data[] = $d;
        }

        $this->_sites = $data;

        return true;
    }

    private function formatDate($date) {
        return (new \DateTime($date))->getTimestamp();
    }

    public function report($params) {
        $res = $this->client()->request('GET', 'startDate='.$this->formatDate($params['start']).'&endDate='.$this->formatDate($params['start']).'&site='.$params['site'].'&country=US', [
            'cookies' => $this->jar(),
        ]);
        $data = json_decode($res->getBody());
        return $data;
    }


    public function sites() {
        return $this->_sites;
    }
}

//{"access_token":"7ca0d376-8c44-44ba-8f1a-cf801373fe06","token_type":"bearer","expires_in":1295999,"scope":"read/write"}
//https://api.sovrn.com/account/user
//Authorization:Bearer 7ca0d376-8c44-44ba-8f1a-cf801373fe06
//https://api.sovrn.com/earnings/breakout/all?iid=13068347&startDate=1483228800000&endDate=1485907199999&site=wtf1.co.uk&country=US
//https://api.sovrn.com/overview/all?site=wtf1.co.uk&startDate=1483228800000&endDate=1485907199999&iid=13068347