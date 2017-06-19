<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;

class ProcuracaoController extends BaseController{

    public function getProcuracoes(Request $request) {
        $procuracoes = new \App\Data\Procuracao();

        $res = $procuracoes->getProcuracoes();

        echo json_encode($res);
        exit;
    }

    public function getProcuracao(Request $request) {
        $procuracao = new \App\Data\Procuracao();

        $res = $procuracao->getProcuracao($request->input('id'));

        echo json_encode($res);
        exit;
    }
}
