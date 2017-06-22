<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class Certidao extends Utils {

    public function getCertidoes() {

        $result = $this->checkPermissão('certidao');

        if($result) {
            $pedidos = $this->getPedidos('Certidão');

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

    public function getCertidao($id) {
        $result = $this->checkPermissão('certidao');

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

    public function addCertidao($ato, $livro, $folha, $outorgante, $outorgado, $date, $user_id) {
    	return $this->addPedido('Certidão', $ato, $livro, $folha, $outorgante, $outorgado, $date, $user_id, 'Aguardando');
    }
}