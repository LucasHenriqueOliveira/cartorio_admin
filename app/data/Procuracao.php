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

    public function addProcuracao($rg, $cpf, $date, $user_id) {
        return DB::insert('INSERT INTO `pedido` (`tipo`, `rg`, `cpf`, `data_hora`, `user_id`, `status`) VALUES (?, ?, ?, ?, ?, ?)',
        ['Procuração', $rg, $cpf, $date, $user_id, 'Aguardando']);
    }

	public function getTiposProcuracao() {
		return DB::select("SELECT * FROM `tipo_procuracao`");
	}

	public function getDocumentosProcuracao($id) {
		return DB::select("SELECT * FROM `documento_tipo_procuracao` AS dt INNER JOIN `documento` AS d ON dt.`documento_id` = d.`documento_id` WHERE `tipo_procuracao_id` = ?", [$id]);
	}
}