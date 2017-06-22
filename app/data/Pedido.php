<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class Pedido extends Utils {

    public function movimentar($pedido_id, $descricao) {

        try {

            DB::beginTransaction();

            switch ($descricao) {
                case 'Iniciar Análise':
                    $pedido = $this->getStatus($pedido_id);

                    if($pedido->status == 'Em análise') {
                        $res['error'] = true;
                        $res['message'] = 'Pedido já está com status em análise.';
                        return $res;
                    }

                    $this->logStatus($pedido_id, $pedido->status, 'Em análise', date('Y-m-d H:i:s'));
                    $this->atualizarStatus($pedido_id, 'Em análise');
                    $descricao = 'Análise iniciada';
                    break;
                case 'Documento pronto':
                    $pedido = $this->getStatus($pedido_id);

                    if($pedido->status == 'Pronto') {
                        $res['error'] = true;
                        $res['message'] = 'Pedido já está com status pronto.';
                        return $res;
                    }

                    $this->logStatus($pedido_id, $pedido->status, 'Pronto', date('Y-m-d H:i:s'));
                    $this->atualizarStatus($pedido_id, 'Pronto');
                    break;
                case 'Realizar a entrega':
                    $pedido = $this->getStatus($pedido_id);

                    if($pedido->status == 'Entregue') {
                        $res['error'] = true;
                        $res['message'] = 'Pedido já está com status entregue.';
                        return $res;
                    }

                    $this->logStatus($pedido_id, $pedido->status, 'Entregue', date('Y-m-d H:i:s'));
                    $this->atualizarStatus($pedido_id, 'Entregue');
                    $descricao = 'Entrega realizada';
                    break;
            }

            $result = DB::select("SELECT (sequencia + 1) AS sequencia FROM `movimentacao`WHERE `pedido_id` = ? ORDER BY sequencia DESC LIMIT 1", [$pedido_id])[0];

            DB::insert('INSERT INTO `movimentacao` (pedido_id, user_id, data_hora, sequencia, descricao) VALUES (?, ?, ?, ?, ?)',
                       [$pedido_id, $this->getUserId()->id, date('Y-m-d H:i:s'), $result->sequencia, $descricao]);

            DB::commit();

            $pedido = $this->getPedido($pedido_id);
            $pedido->movimentacoes = $this->getMovimentacoes($pedido_id);

            return $pedido;

        } catch (\Exception $e) {
            DB::rollBack();

            $res['error'] = true;
            $res['message'] = 'Erro ao realizar a movimentação.';
            return $res;
        }
    }
}