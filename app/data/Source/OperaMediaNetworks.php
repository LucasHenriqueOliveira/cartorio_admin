<?php

namespace App\Data\Source;

class OperaMediaNetworks extends \App\Data\Scrape {
    public function login() {

        $res = $this->client()->request('POST', 'https://impel-mt.mobiletheory.com/login?returnto=', [
            'cookies' => $this->jar(),
            'headers' => [
                'User-Agent' => $this->agent()
            ],
            'allow_redirects' => [
                'referer'         => true,
                'track_redirects' => true
            ],
            'form_params' => [
                'data' => [
                    'Users' => [
                        'username' => $this->username(),
                        'password' => $this->password(),
                        'returnto' => '?returnto='
                    ]
                ],
                'submit' => 'Log In'
            ],
        ]);

        // failed to login
        if (!$res->getHeaders()['X-Guzzle-Redirect-History']) {
            return false;
        }

        return true;
    }

    private function formatDate($date) {
        return (new \DateTime($date))->format('m/d/Y');
    }

    public function report($params = []) {
        $res = $this->client()->request('GET', 'https://impel-mt.mobiletheory.com/homepage/getDetailReportToCsv', [
            'cookies' => $this->jar(),
            'query' => [
                'startdate_hidden' => $this->formatDate($params['start']),
                'enddate_hidden' => $this->formatDate($params['end']),
                'group_by' => '',
                'date_options' => 'today',
                'zoneId' => '-1'
            ]
        ]);

        // @todo: could be much cleaner. but it works
        $raw = explode("\n", $res->getBody()->getContents());
        array_shift($raw);array_shift($raw);array_shift($raw);array_shift($raw);;
        array_pop($raw);

        $data = $this->csvToAssoc(implode("\n", $raw));
        return $data;
    }
}