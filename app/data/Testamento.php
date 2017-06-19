<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class Testamento extends Utils {

    public function getTestamentos() {
        $result = $this->checkPermissão('testamento');

        if($result) {
            $pedidos = $this->getPedidos('Testamento');

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

    public function getTestamento($id) {
        $result = $this->checkPermissão('testamento');

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