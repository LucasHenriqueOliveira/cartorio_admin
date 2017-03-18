<?php

namespace App\Data\Source;

class Taboola extends \App\Data\Scrape {

    public function login() {


        $phantom = shell_exec('cd '.__DIR__.'/../../../ && casperjs --ignore-ssl-errors=true --ssl-protocol=any --cookies-file=cookies.txt js/Taboola.js --username="'.$this->username().'" --password="'.$this->password().'" 2>&1');
        $data = json_decode(trim($phantom));

        foreach ($data as $cookie) {
            $this->jar()->setCookie(new \GuzzleHttp\Cookie\SetCookie([
                'Domain'  => $cookie->domain,
                'Name'    => $cookie->name,
                'Value'   => $cookie->value,
                'Discard' => false
            ]));
        }


        //$this->jar = (new \GuzzleHttp\Cookie\FileCookieJar(__DIR__.'/../../../cookies.txt'));

        $res = $this->client()->request('GET', 'https://backstage.taboola.com/backstage/ping', [
            'cookies' => $this->jar(),
            'headers' => [
                'User-Agent' => $this->agent()
            ]
        ]);

        $data = $res->getBody()->getContents();
        print_r($data);
        exit;

        $data = json_decode(trim($phantom));
        $cookies = explode('; ', $data->cookies);
        $saveCookies = [];

        foreach ($cookies as $cookie) {
            $saveCookies[] = explode('=', $cookie);
        }

        print_r($saveCookies);

        $this->jar(new \GuzzleHttp\Cookie\CookieJar(false, $saveCookies));

        $res = $this->client()->request('POST', 'https://backstage.taboola.com/backstage/j_spring_security_check', [
            'cookies' => $this->jar(),
            'form_params' => [
                'user' => [
                    'j_username' => $this->username(),
                    'j_password' => $this->password(),
                    'sig' => $data->sig,
                    'redir' => $data->redir,
                    '_csrf' => $data->_csrf,
                    'serverTime' => $data->serverTime,
                ]
            ]
        ]);

        print_r($res);
        print_r($res->getBody());
    }
}

/*
https://backstage.taboola.com/backstage/j_spring_security_check

form data
serverTime:1488237981303
_csrf:d99e9a66-9e6d-40f9-9f2d-d539a7ebef57
redir:http://backstage.taboola.com/backstage/reports
sig:286231cfca45083e21963f9716accb39a2534735
j_username:chris@publisherdesk.com
j_password:5sPm3cXU81EP
*/