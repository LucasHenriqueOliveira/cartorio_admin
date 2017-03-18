<?php

namespace App\Http\Controllers\Source;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Exception\HttpResponseException;

class SovrnController extends Controller {

    public function setup(Request $request, $source, $start, $end) {
        $sovrn = new \App\Data\Source\Sovrn([
            'username' => $_ENV['SOVRN_USERNAME'],
            'password' => $_ENV['SOVRN_PASSWORD']
        ]);

        $res = $sovrn->setup([
            'start' => $start,
            'end' => $end,
            'source' => $source
        ]);
    }
}