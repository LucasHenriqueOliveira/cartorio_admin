<?php

namespace App\Http\Controllers\Source;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Exception\HttpResponseException;

class RubiconController extends Controller {

    public function setup(Request $request, $source, $start, $end) {
        $rubicon = new \App\Data\Source\Rubicon([
            'username' => $_ENV['RUBICON_USERNAME'],
            'password' => $_ENV['RUBICON_PASSWORD']
        ]);

        $res = $rubicon->setup([
            'start' => $start,
            'end' => $end,
            'source' => $source
        ]);
    }
}