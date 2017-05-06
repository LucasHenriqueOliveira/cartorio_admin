<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;

class RelatorioController extends BaseController{

    public function relatorio(Request $request) {
        $relatorio = new \App\Data\Relatorio();

        $res = $relatorio->getRelatorio($request);

        echo json_encode($res);
        exit;
    }
}
