<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;

class TestamentoController extends BaseController{

    public function getTestamentos(Request $request) {
        $testamentos = new \App\Data\Testamento();

        $res = $testamentos->getTestamentos();

        echo json_encode($res);
        exit;
    }

    public function getTestamento(Request $request) {
        $testamento = new \App\Data\Testamento();

        $res = $testamento->getTestamento($request->input('id'));

        echo json_encode($res);
        exit;
    }

    public function addTestamento(Request $request) {
        $testamento = new \App\Data\Testamento();

        $res = $testamento->addTestamento($request->input('rg'), $request->input('cpf'), $request->input('casamento'),
                                        date('Y-m-d H:i:s'), $request->input('cliente_id'));

        echo json_encode($res);
        exit;
    }
}
