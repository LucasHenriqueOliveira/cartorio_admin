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
            $pedido = $this->getPedido($id);
            $pedido->movimentacoes = $this->getMovimentacoes($id);

            return $pedido;
        } else {
            $res['error'] = true;
            $res['message'] = 'Usuário sem permissão.';
            return $res;
        }
    }
}