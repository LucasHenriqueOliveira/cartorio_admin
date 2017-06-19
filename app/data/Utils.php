<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class Utils {

    public function getPedidosDashboard($tipo) {
        return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data FROM `pedido` AS p INNER JOIN `cliente` AS u ON p.`cliente_id` = u.`cliente_id` WHERE p.`tipo` = ? AND p.`status` = 'Aguardando' ORDER BY p.data_hora ASC LIMIT 3", [$tipo]);
    }

    public function getPedidos($tipo) {
        return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data FROM `pedido` AS p INNER JOIN `cliente` AS u ON p.`cliente_id` = u.`cliente_id` WHERE p.`tipo` = ? ORDER BY p.data_hora LIMIT 100", [$tipo]);
    }

    public function getMovimentacoes($id) {
        return DB::select("SELECT *, DATE_FORMAT(m.`data_hora`, '%d/%m/%Y %H:%i') as data FROM `movimentacao` AS m LEFT JOIN `users` AS u ON m.`user_id` = u.`id` WHERE m.pedido_id = ? ORDER BY m.`sequencia` ASC", [$id]);
    }

    public function getPedido($id) {
        return DB::select("SELECT *, DATE_FORMAT(p.`data_hora`, '%d/%m/%Y %H:%i') as data FROM `pedido` AS p INNER JOIN `cliente` AS u ON p.`cliente_id` = u.`cliente_id` WHERE p.`pedido_id` = ?", [$id])[0];
    }

    public function atualizarStatus($id, $status) {
        return DB::update('UPDATE `pedido` SET `status` = ? WHERE `pedido_id` = ?', [$status, $id]);
    }

    public function logStatus($id, $status, $status_novo, $date) {
        DB::insert('INSERT INTO `log_status` (`pedido_id`, `data_hora`, `status_antigo`, `status_novo`, `user_id`, `ip`, `proxy`) VALUES (?, ?, ?, ?, ?, ?, ?)',
        [$id, $date, $status, $status_novo, $this->getUserId()->id, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']]);
    }

    public function getStatus($id) {
        return DB::select("SELECT `status` FROM `pedido`WHERE `pedido_id` = ?", [$id])[0];
    }

    public function checkPermissão($tipo) {
        $result = DB::select("SELECT * FROM `permissao`WHERE `users_id` = ?", [$this->getUserId()->id])[0];
        return ($result->{$tipo}) ? true : false;
    }

    public function getUserId() {
        return JWTAuth::parseToken()->authenticate();
    }
}