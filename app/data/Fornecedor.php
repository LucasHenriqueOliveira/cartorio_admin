<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class Fornecedor {

    public function getFornecedores() {
        return DB::select("SELECT *, c.id AS cidade_id, f.nome AS nome, c.nome AS cidade, DATE_FORMAT(f.data_add, '%d/%m/%Y %H:%i') as data_add FROM fornecedor AS f INNER JOIN cidade AS c ON f.cidade = c.id WHERE `ativo` = :ativo ORDER BY `fornecedor_id` DESC", ['ativo' => 1]);
    }

    public function addFornecedor($request) {
        return DB::insert('INSERT INTO fornecedor (nome, cnpj_cpf, logradouro, bairro, uf, cidade, cep, contato, telefone1, telefone2, data_add) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [$request->input('nome'), $request->input('cnpj_cpf'), $request->input('logradouro'), $request->input('bairro'), $request->input('estado'), $request->input('cidade'), $request->input('cep'), $request->input('contato'), $request->input('telefone1'), $request->input('telefone2'), date('Y-m-d h:i:s')]);
    }

    public function removeFornecedor($id) {
        return DB::update('UPDATE fornecedor SET `ativo` = ? WHERE fornecedor_id = ?', [0, $id]);
    }

    public function atualizarFornecedor($request) {
        return DB::update('UPDATE fornecedor SET `nome` = ?, `cnpj_cpf` = ?, `logradouro` = ?, `complemento` = ?, `cidade` = ?, `uf` = ?, `bairro` = ?, `cep` = ?, `contato` = ?, `telefone1` = ?, `telefone2` = ? WHERE fornecedor_id = ?',
        [$request->input('nome'), $request->input('cnpj_cpf'), $request->input('logradouro'), $request->input('complemento'), $request->input('cidade'), $request->input('uf'), $request->input('bairro'), $request->input('cep'), $request->input('contato'), $request->input('telefone1'), $request->input('telefone2'), $request->input('fornecedor_id')]);
    }
}