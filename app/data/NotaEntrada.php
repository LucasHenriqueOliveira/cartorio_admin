<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class NotaEntrada {

    public function getNotasEntrada() {
        return DB::select("SELECT *, DATE_FORMAT(ne.data_add, '%d/%m/%Y %H:%i') as data_add, DATE_FORMAT(data_compra, '%d/%m/%Y') as data_compra, DATE_FORMAT(data_pagamento, '%d/%m/%Y') as data_pagamento
        FROM nota_entrada AS ne INNER JOIN product AS p ON ne.produto_id = p.product_id
        INNER JOIN fornecedor AS f ON ne.fornecedor_id = f.fornecedor_id
        WHERE ne.`ativo` = :ativo ORDER BY ne.`nota_entrada_id` DESC", ['ativo' => 1]);
    }

    public function addNotaEntrada($request) {
        return DB::insert('INSERT INTO nota_entrada (produto_id, quantidade, unidade, fornecedor_id, nfe, data_compra, data_pagamento, valor_produto, valor_nota, valor_total, data_add, centro_custo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [$request->input('produto_id'), $request->input('quantidade'), $request->input('unidade'), $request->input('fornecedor_id'), $request->input('nfe'), $this->formataData($request->input('data_compra')), $this->formataData($request->input('data_pagamento')), $request->input('valor_produto'), $request->input('valor_nota'), $request->input('valor_total'), date('Y-m-d h:i:s'), $request->input('centro_custo')]);
    }

    public function removeNotaEntrada($id) {
        return DB::update('UPDATE nota_entrada SET `ativo` = ? WHERE nota_entrada_id = ?', [0, $id]);
    }

    public function atualizarNotaEntrada($request) {
        return DB::update('UPDATE nota_entrada SET `produto_id` = ?, `quantidade` = ?, `unidade` = ?, `fornecedor_id` = ?, `nfe` = ?, `data_compra` = ?, `data_pagamento` = ?, `valor_produto` = ?, `valor_nota` = ?, `valor_total` = ?, `centro_custo` = ? WHERE nota_entrada_id = ?',
        [$request->input('produto_id'), $request->input('quantidade'), $request->input('unidade'), $request->input('fornecedor_id'), $request->input('nfe'), $this->formataData($request->input('data_compra')), $this->formataData($request->input('data_pagamento')), $request->input('valor_produto'), $request->input('valor_nota'), $request->input('valor_total'), $request->input('centro_custo'), $request->input('nota_entrada_id')]);
    }

    private function formataData($data) {
        return (new \DateTime($data))->format('Y-m-d');
    }
}