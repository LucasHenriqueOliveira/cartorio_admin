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

    public function addProcuracao(Request $request) {
        $procuracao = new \App\Data\Procuracao();

        $res = $procuracao->addProcuracao($request->input('rg'), $request->input('cpf'),
                                        date('Y-m-d H:i:s'), $request->input('cliente_id'));

		$procuracao->email($request->input('user_id'));

        echo json_encode($res);
        exit;
    }

	public function getTiposProcuracao(Request $request) {
		$tipos_procuracao = new \App\Data\Procuracao();

		$res = $tipos_procuracao->getTiposProcuracao();

		echo json_encode($res);
		exit;
	}

	public function getDocumentosProcuracao(Request $request) {
		$documentos_procuracao = new \App\Data\Procuracao();

		$res = $documentos_procuracao->getDocumentosProcuracao($request->input('id'));

		echo json_encode($res);
		exit;
	}
}
