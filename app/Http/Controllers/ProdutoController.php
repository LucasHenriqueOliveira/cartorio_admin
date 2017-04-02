<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;

class ProdutoController extends BaseController{

    public function produtos(Request $request) {
        $produtos = new \App\Data\Produto();

        $res = $produtos->getProdutos();

        echo json_encode($res);
        exit;
    }

    public function addProduto(Request $request) {
        $produto = new \App\Data\Produto();

        try {
            $res = $produto->addProduto($request->input('nome'));
            $res = $produto->getProdutos();
        } catch (\Exception $e) {
            $res = ['error' => $e];
        }
        echo json_encode($res);
        exit;
    }

    public function removeProduto(Request $request) {
        $produto = new \App\Data\Produto();

        try {
            $res = $produto->removeProduto($request->input('id'));
            $res = $produto->getProdutos();
        } catch (\Exception $e) {
            $res = ['error' => $e];
        }
        echo json_encode($res);
        exit;
    }

    public function atualizarProduto(Request $request) {
        $produto = new \App\Data\Produto();

        try {
            $res = $produto->atualizarProduto($request->input('id'), $request->input('nome'));
            $res = $produto->getProdutos();
        } catch (\Exception $e) {
            $res = ['error' => $e];
        }
        echo json_encode($res);
        exit;
    }
}
