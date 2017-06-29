<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class Procuracao extends Utils {

    public function getProcuracoes() {
        $result = $this->checkPermissão('procuracao');

        if($result) {
            $pedidos = $this->getPedidos('Procuração');

            foreach ($pedidos as $pedido) {
                $pedido->movimentacoes = $this->getMovimentacoes($pedido->pedido_id);
            }

            return $pedidos;

        } else {
            $res['error'] = true;
            $res['message'] = 'Usuário sem permissão.';
            return $res;
        }
    }

    public function getProcuracao($id) {
        $result = $this->checkPermissão('procuracao');

        if($result) {
            $pedido = $this->getPedido($id, 'Procuração');
            $pedido->movimentacoes = $this->getMovimentacoes($id);

            return $pedido;
        } else {
            $res['error'] = true;
            $res['message'] = 'Usuário sem permissão.';
            return $res;
        }
    }

    public function addProcuracao($tipo, $files, $date, $user_id) {

		try {
			$documentos = $this->getDocumentosProcuracao($tipo['tipo_procuracao_id']);

			$res = DB::insert('INSERT INTO `pedido` (`tipo`, `tipo_procuracao`, `data_hora`, `user_id`, `status`) VALUES (?, ?, ?, ?, ?)',
			['Procuração', $tipo['tipo_procuracao_id'], $date, $user_id, 'Aguardando']);

			$pedido_id = DB::getPdo()->lastInsertId();

			DB::insert('INSERT INTO `movimentacao` (`pedido_id`, `user_id`, `data_hora`, `sequencia`, `descricao`) VALUES (?, ?, ?, ?, ?)',
				[$pedido_id, $user_id, $date, 1, 'Solicitação de Procuração']);

			foreach ($documentos as $documento) {
				$result = $this->uploadBase64($files[$documento->nome_campo], $documento->nome_campo, $pedido_id);

				if(!$result) {
					$res['error'] = true;
					$res['message'] = 'Erro ao solicitar a Procuração';
					return $res;
				}
			}
			return $res;

		} catch (\Exception $e) {
			$res['error'] = true;
			$res['message'] = 'Erro ao solicitar a Procuração';
			return $res;
		}
    }

	public function getTiposProcuracao() {
		return DB::select("SELECT * FROM `tipo_procuracao`");
	}

	public function getDocumentosProcuracao($id) {
		return DB::select("SELECT * FROM `documento_tipo_procuracao` AS dt INNER JOIN `documento` AS d ON dt.`documento_id` = d.`documento_id` WHERE `tipo_procuracao_id` = ?", [$id]);
	}

	public function email($user_id) {
		$user = $this->getUser($user_id);
		$texto = '<br /> Prezado(a) '.$user->nome.',';
		$texto .= '<br /><br />O seu pedido de procuração está confirmado!';
		$texto .= '<br /><br /> Acompanhe o andamento do seu pedido pelo aplicativo. Você receberá um email quando o documento estiver pronto.';
		$texto .= '<br /><br /> Att, <br />Cartório App';
		$texto .= '<br /><br /> <h5>Não responda a este email. Os emails enviados a este endereço não serão respondidos.</h5>';
		$this->sendEmail($user->email, 'Solicitação de Procuração', $texto);
	}
}