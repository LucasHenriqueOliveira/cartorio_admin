<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;

class FornecedorController extends BaseController{

    public function fornecedores(Request $request) {
        $fornecedores = new \App\Data\Fornecedor();

        $res = $fornecedores->getFornecedores();

        echo json_encode($res);
        exit;
    }

    public function addFornecedor(Request $request) {
        $fornecedor = new \App\Data\Fornecedor();

        try {
            $res = $fornecedor->addFornecedor($request);
            $res = $fornecedor->getFornecedores();
        } catch (\Exception $e) {
            $res = ['error' => $e];
        }
        echo json_encode($res);
        exit;
    }

    public function removeFornecedor(Request $request) {
        $fornecedor = new \App\Data\Fornecedor();

        try {
            $res = $fornecedor->removeFornecedor($request->input('id'));
            $res = $fornecedor->getFornecedores();
        } catch (\Exception $e) {
            $res = ['error' => $e];
        }
        echo json_encode($res);
        exit;
    }

    public function atualizarFornecedor(Request $request) {
        $fornecedor = new \App\Data\Fornecedor();

        try {
            $res = $fornecedor->atualizarFornecedor($request->input('id'), $request->input('nome'));
            $res = $fornecedor->getFornecedores();
        } catch (\Exception $e) {
            $res = ['error' => $e];
        }
        echo json_encode($res);
        exit;
    }
}
