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

        $res = $procuracao->addProcuracao($request->input('tipo'), $request->input('files'),
                                        date('Y-m-d H:i:s'), $request->input('user_id'));

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

	public function getDocumento(Request $request) {
		$documento_procuracao = new \App\Data\Procuracao();

		$res = $documento_procuracao->getDocumento($request->input('documento'), $request->input('pedido_id'));

		echo json_encode($res);
		exit;
	}
}
