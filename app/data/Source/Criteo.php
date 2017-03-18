<?php

namespace App\Data\Source;

class Criteo extends \App\Data\Api {
    private $_token;

    public function __construct($params = []) {

        $this->_token = $params['token'];
        parent::__construct($params);
    }

    public function token() {
        return $this->_token;
    }

    private function formatDate($date) {
        return (new \DateTime($date))->format('Y-m-d');
    }

    public function report($params = []) {
        $res = $this->client()->request('GET', 'https://publishers.criteo.com/api/2.0/stats.json', [
            'query' => [
                'apitoken' => $this->token(),
                'begindate' => $this->formatDate($params['start']),
                'enddate' => $this->formatDate($params['end'])
            ]
        ]);
        return json_decode($res->getBody()->getContents());
    }
}