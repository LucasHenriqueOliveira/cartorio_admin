<?php

namespace App\Data\Source;

class SublimeSkins extends \App\Data\Scrape {
    public function login() {
        $res = $this->client()->request('POST', 'http://ayads.co/login.php', [
            'cookies' => $this->jar(),
            'headers' => [
                'User-Agent' => $this->agent()
            ],
            'multipart' => [
                'login' => $this->username(),
                'password' => $this->password()
            ],
        ]);
        return $res->getBody();
    }

}