<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class Produto {

    public function getProdutos() {
        return DB::select("SELECT product_id, name, DATE_FORMAT(date, '%d/%m/%Y') as date FROM product WHERE `active` = :active ORDER BY `product_id` DESC", ['active' => 1]);
    }

    public function addProduto($nome) {
        return DB::insert('INSERT INTO product (name, date) VALUES (?, ?)', [$nome, date('Y-m-d h:i:s')]);
    }

    public function removeProduto($id) {
        return DB::update('UPDATE product SET `active` = ? WHERE product_id = ?', [0, $id]);
    }

    public function atualizarProduto($id, $nome) {
        return DB::update('UPDATE product SET `name` = ? WHERE product_id = ?', [$nome, $id]);
    }
}