<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;

class NotaEntradaController extends BaseController{

    public function notas(Request $request) {
        $notas_entrada = new \App\Data\NotaEntrada();

        $res = $notas_entrada->getNotasEntrada();

        echo json_encode($res);
        exit;
    }

    public function addNotaEntrada(Request $request) {
        $nota_entrada = new \App\Data\NotaEntrada();

        try {
            $res = $nota_entrada->addNotaEntrada($request);
            $res = $nota_entrada->getNotasEntrada();
        } catch (\Exception $e) {
            $res = ['error' => $e];
        }
        echo json_encode($res);
        exit;
    }

    public function removeNotaEntrada(Request $request) {
        $nota_entrada = new \App\Data\NotaEntrada();

        try {
            $res = $nota_entrada->removeNotaEntrada($request->input('id'));
            $res = $nota_entrada->getNotasEntrada();
        } catch (\Exception $e) {
            $res = ['error' => $e];
        }
        echo json_encode($res);
        exit;
    }

    public function atualizarNotaEntrada(Request $request) {
        $nota_entrada = new \App\Data\NotaEntrada();

        try {
            $res = $nota_entrada->atualizarNotaEntrada($request);
            $res = $nota_entrada->getNotasEntrada();
        } catch (\Exception $e) {
            $res = ['error' => $e];
        }
        echo json_encode($res);
        exit;
    }
}
