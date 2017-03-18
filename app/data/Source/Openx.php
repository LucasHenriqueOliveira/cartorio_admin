<?php

// https://github.com/openx/OX3-PHP-API-Client/tree/develop is too old to be usefull other than ref

namespace App\Data\Source;

class Openx extends \App\Data\Api {
    public function login() {
        return $this->client()->request('POST', '', [
            'cookies' => $this->jar(),
            'form_params' => [
                'user' => [
                    'email' => $this->username(),
                    'password' => $this->password()
                ]
            ]
        ]);
    }
}