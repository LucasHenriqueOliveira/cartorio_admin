<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;

class UsuarioController extends BaseController{

    public function estados(Request $request) {
        $estados = new \App\Data\Usuario();

        $res = $estados->getEstados();

        echo json_encode($res);
        exit;
    }

    public function cidades(Request $request) {
        $cidades = new \App\Data\Usuario();

        $res = $cidades->getCidades($request->input('id'));

        echo json_encode($res);
        exit;
    }
}
