<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class Fornecedor {

    public function getFornecedores() {
        return DB::select("SELECT * FROM fornecedor WHERE `ativo` = :ativo ORDER BY `fornecedor_id` DESC", ['ativo' => 1]);
    }

    public function addFornecedor($request) {
        return DB::insert('INSERT INTO fornecedor (nome, cnpj_cpf, logradouro, bairro, uf, cidade, cep, contato, telefone1, telefone2, data_add) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [$request->input('nome'), $request->input('cnpj_cpf'), $request->input('logradouro'), $request->input('bairro'), $request->input('estado'), $request->input('cidade'), $request->input('cep'), $request->input('contato'), $request->input('telefone1'), $request->input('telefone2'), date('Y-m-d h:i:s')]);
    }

    public function removeFornecedor($id) {
        return DB::update('UPDATE fornecedor SET `ativo` = ? WHERE fornecedor_id = ?', [0, $id]);
    }

    public function atualizarFornecedor($id, $nome) {
        return DB::update('UPDATE product SET `name` = ? WHERE product_id = ?', [$nome, $id]);
    }
}