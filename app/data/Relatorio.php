<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class Relatorio {

    public function getRelatorio($request) {
        $query = '';
        if($request->input('centro_custo')) {
            $query .= ' AND centro_custo = '. $request->input('centro_custo');
        }

        if($request->input('produto')) {
            $query .= ' AND produto_id = '. $request->input('produto');
        }

        if($request->input('fornecedor')) {
            $query .= ' AND fornecedor_id = '. $request->input('fornecedor');
        }

        if($request->input('data_compra_inicio') || $request->input('data_compra_fim')) {
            $query .= ' AND data_compra BETWEEN "'. $this->formataData($request->input('data_compra_inicio')) . '" AND "' . $this->formataData($request->input('data_compra_fim')).'"';
        }

        if($request->input('data_pagamento_inicio') || $request->input('data_pagamento_fim')) {
            $query .= ' AND data_pagamento BETWEEN '. $this->formataData($request->input('data_pagamento_inicio')) . ' AND ' . $this->formataData($request->input('data_pagamento_fim'));
        }

        if($request->input('data_cadastro_inicio') || $request->input('data_cadastro_fim')) {
            $query .= ' AND data_add BETWEEN '. $this->formataData($request->input('data_cadastro_inicio')) . ' AND ' . $this->formataData($request->input('data_cadastro_fim'));
        }

        if($request->input('nfe')) {
            $query .= ' AND nfe = '. $request->input('nfe');
        }

        if($request->input('valor_produto_inicio') || $request->input('valor_produto_fim')) {
            $query .= ' AND valor_produto BETWEEN '. $this->formataData($request->input('valor_produto_inicio')) . ' AND ' . $this->formataData($request->input('valor_produto_fim'));
        }

        if($request->input('valor_nfe_inicio') || $request->input('valor_nfe_fim')) {
            $query .= ' AND valor_nota BETWEEN '. $this->formataData($request->input('valor_nfe_inicio')) . ' AND ' . $this->formataData($request->input('valor_nfe_fim'));
        }

        if($request->input('valor_total_inicio') || $request->input('valor_total_fim')) {
            $query .= ' AND valor_total BETWEEN '. $this->formataData($request->input('valor_total_inicio')) . ' AND ' . $this->formataData($request->input('valor_total_fim'));
        }

        return DB::select("SELECT *, sum(valor_nota) as soma_valor_nota, sum(valor_produto) as soma_valor_produto, sum(valor_total) as soma_valor_total, COUNT(DISTINCT nfe) as produtos_diferentes,
                DATE_FORMAT(ne.data_add, '%d/%m/%Y %H:%i') as data_add, DATE_FORMAT(data_compra, '%d/%m/%Y') as data_compra, DATE_FORMAT(data_pagamento, '%d/%m/%Y') as data_pagamento
                FROM nota_entrada AS ne INNER JOIN product AS p ON ne.produto_id = p.product_id
                INNER JOIN fornecedor AS f ON ne.fornecedor_id = f.fornecedor_id
                WHERE ne.`ativo` = :ativo".$query." ORDER BY ne.`nota_entrada_id` DESC", ['ativo' => 1]);
    }

    private function formataData($data) {
        return (new \DateTime($data))->format('Y-m-d');
    }
}