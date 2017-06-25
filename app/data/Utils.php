<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class Utils {

    public function getPedidosDashboard($tipo) {
        if($tipo == 'Procuração') {
            return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data FROM `pedido` AS p INNER JOIN `users` AS u ON p.`user_id` = u.`id` INNER JOIN `tipo_procuracao` AS t ON p.`tipo_procuracao` = t.`tipo_procuracao_id` WHERE p.`tipo` = ? AND p.`status` = 'Aguardando' ORDER BY p.data_hora ASC LIMIT 3", [$tipo]);
        }
        return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data, DATE_FORMAT(p.`agendamento`, '%d/%m/%Y %H:%i') as agendamento FROM `pedido` AS p INNER JOIN `users` AS u ON p.`user_id` = u.`id` WHERE p.`tipo` = ? AND p.`status` = 'Aguardando' ORDER BY p.data_hora ASC LIMIT 3", [$tipo]);
    }

    public function getPedidos($tipo) {
        if($tipo == 'Procuração') {
            return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data FROM `pedido` AS p INNER JOIN `users` AS u ON p.`user_id` = u.`id` INNER JOIN `tipo_procuracao` AS t ON p.`tipo_procuracao` = t.`tipo_procuracao_id` WHERE p.`tipo` = ? ORDER BY p.data_hora LIMIT 100", [$tipo]);
        }
        return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data, DATE_FORMAT(p.`agendamento`, '%d/%m/%Y %H:%i') as agendamento FROM `pedido` AS p INNER JOIN `users` AS u ON p.`user_id` = u.`id` WHERE p.`tipo` = ? ORDER BY p.data_hora LIMIT 100", [$tipo]);
    }

    public function getMovimentacoes($id) {
        return DB::select("SELECT *, DATE_FORMAT(m.`data_hora`, '%d/%m/%Y %H:%i') as data FROM `movimentacao` AS m LEFT JOIN `users` AS u ON m.`user_id` = u.`id` WHERE m.pedido_id = ? ORDER BY m.`sequencia` ASC", [$id]);
    }

    public function getPedido($id, $tipo = '') {
        if($tipo == 'Procuração') {
            return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data FROM `pedido` AS p INNER JOIN `users` AS u ON p.`user_id` = u.`id` INNER JOIN `tipo_procuracao` AS t ON p.`tipo_procuracao` = t.`tipo_procuracao_id` WHERE p.`pedido_id` = ?", [$id])[0];
        }
        return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data, DATE_FORMAT(p.`agendamento`, '%d/%m/%Y %H:%i') as agendamento FROM `pedido` AS p INNER JOIN `users` AS u ON p.`user_id` = u.`id` WHERE p.`pedido_id` = ?", [$id])[0];
    }

    public function atualizarStatus($id, $status) {
        return DB::update('UPDATE `pedido` SET `status` = ? WHERE `pedido_id` = ?', [$status, $id]);
    }

    public function logStatus($id, $status, $status_novo, $date) {
        DB::insert('INSERT INTO `log_status` (`pedido_id`, `data_hora`, `status_antigo`, `status_novo`, `user_id`, `ip`, `proxy`) VALUES (?, ?, ?, ?, ?, ?, ?)',
        [$id, $date, $status, $status_novo, $this->getUserId()->id, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']]);
    }

    public function getStatus($id) {
        return DB::select("SELECT `status` FROM `pedido` WHERE `pedido_id` = ?", [$id])[0];
    }

    public function checkPermissão($tipo) {
        $result = DB::select("SELECT * FROM `permissao` WHERE `user_id` = ?", [$this->getUserId()->id])[0];
        return ($result->{$tipo}) ? true : false;
    }

    public function getUserId() {
        return JWTAuth::parseToken()->authenticate();
    }

    public function addPedido($tipo, $ato, $livro, $folha, $outorgante, $outorgado, $date, $user_id, $status) {
		DB::insert('INSERT INTO `pedido` (`tipo`, `ato`, `livro`, `folha`, `outorgante`, `outorgado`, `data_hora`, `user_id`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
			[$tipo, $ato, $livro, $folha, $outorgante, $outorgado, $date, $user_id, $status]);

		$pedido_id = DB::getPdo()->lastInsertId();
		$descricao = 'Solicitação de ' . $tipo;

		return DB::insert('INSERT INTO `movimentacao` (`pedido_id`, `user_id`, `data_hora`, `sequencia`, `descricao`) VALUES (?, ?, ?, ?, ?)',
			[$pedido_id, $user_id, $date, 1, $descricao]);
	}

	public function getFirma($nome, $cpf) {
		$search = "";
		if($nome) {
			$search .= " AND nome LIKE '%$nome%' ";
		}
		if($cpf) {
			$search .= " AND cpf = '$cpf' ";
		}
		$result = DB::select("SELECT * FROM `firma` WHERE `data_hora` BETWEEN ? AND NOW()" . $search . "LIMIT 1", ['2017-01-01']);

		if(count($result)) {
			return [
				'message' => 'O usuário possui firma neste cartório.'
				];
		} else {
			return [
				'message' => 'O usuário não possui firma neste cartório.'
			];
		}
	}

	public function historico($id) {
		return DB::select("SELECT tipo, status, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y') as data, DATE_FORMAT(p.`data_hora`, '%H:%i') as hora FROM `pedido` AS p WHERE p.`user_id` = ? ORDER BY p.`data_hora` DESC LIMIT 5", [$id]);
	}

	public function checkCalendarioRestricao($data, $hora) {
		return DB::select("SELECT * FROM `calendario_restricoes` WHERE `data` = ? AND `hora` = ?", [$data, $hora])[0];
	}

	public function addCalendarioRestricao($data, $hora) {
		return DB::insert('INSERT INTO `calendario_restricoes` (`data`, `hora`) VALUES (?, ?)',
			[$data, $hora]);
	}
}