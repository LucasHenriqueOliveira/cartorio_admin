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

	public function email($user_id) {
		$user = $this->getUser($user_id);
		$texto = '<br /> Prezado(a) '.$user->nome.',';
		$texto .= '<br /><br />O seu pedido de certidão está confirmado!';
		$texto .= '<br /><br /> Acompanhe o andamento do seu pedido pelo aplicativo. Você receberá um email quando o documento estiver pronto.';
		$texto .= '<br /><br /> Att, <br />Cartório App';
		$this->sendEmail($user->email, 'Solicitação de Certidão', $texto);
	}
}