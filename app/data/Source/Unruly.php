<?php

namespace App\Data\Source;

class Unruly extends \App\Data\Scrape {
    public $_user;

    public function login() {
        $res = $this->client()->request('POST', 'https://console.unrulymedia.com/j_acegi_security_check', [
            'cookies' => $this->jar(),
            'headers' => [
                'User-Agent' => $this->agent()
            ],
            'form_params' => [
                'j_username' => $this->username(),
                'j_password' => $this->password(),
                'rememberMe' => true,
                'submit' => 'Login'
            ],
            'allow_redirects' => [
                'referer'         => true,
                'track_redirects' => true
            ]
        ]);

        // failed to login
        if (!$res->getHeader('X-Guzzle-Redirect-History')) {
            return false;
        }

        $user = preg_match_all('/sites\/(.*)/i', $res->getHeader('X-Guzzle-Redirect-History')[1], $matches);
        $this->_user = $matches[1][0];

        return true;
    }

    private function formatDate($date) {
        return (new \DateTime($date))->format('m/d/Y');
    }


    public function report($params = []) {
        $res = $this->client()->request('GET', 'http://console.unrulymedia.com/publisher/reporting/custom_user_reports?user_id='.$this->user().'&metrics=row_VideoImpression&metrics=row_CTRate&metrics=row_PublisherEarnings&requestNoPadding=noPad&operatingSystem=&product=&playerType=&country=ON_TARGET_SUBCAMPAIGN&period=a+quick+date+range&start='.$this->formatDate($params['start']).'&end='.$this->formatDate($params['start']).'&dateInterval=day&breakdown1=NETWORK_EXT_PUB&breakdown2=&orderby=%24statsQuery.orderByColumnForFormatter&csv=&layout=&showGraph=&graphType=', [
            'cookies' => $this->jar(),
            'headers' => [
                'User-Agent' => $this->agent()
            ],
        ]);

        print_r($res->getBody()->getContents());
        exit;

        return $processData($res);
    }

    public function user() {
        return $this->_user;
    }
}