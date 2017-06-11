<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class Certidao {

    private function statistics($params = []) {

        $data = [];
        $d = [
            'date1' => $params['start'],
            'date2' => $params['end']
        ];

        $stats = DB::select('SELECT count(*) as qtd FROM `product` WHERE active = 1');
        $data['produtos'] = $stats[0]->qtd;

        $stats = DB::select('SELECT count(*) as qtd FROM `fornecedor` WHERE ativo = 1');
        $data['fornecedores'] = $stats[0]->qtd;

        $stats = DB::select('SELECT sum(valor_nota) as qtd FROM `nota_entrada` WHERE ativo = 1 and `data_add` BETWEEN :date1 AND :date2', $d);
        $data['nf_entrada_valor'] = $stats[0]->qtd;

        $stats = DB::select('SELECT count(*) as qtd FROM `nota_entrada` WHERE ativo = 1 and `data_add` BETWEEN :date1 AND :date2', $d);
        $data['nf_entrada'] = $stats[0]->qtd;

        return $data;
    }

    public function dashboard($params) {
        $res['error'] = false;

        $params['start'] = (new \DateTime($params['start']))->format('Y-m-d H:i:s');
        $params['end'] = (new \DateTime($params['end']))->format('Y-m-d H:i:s');

        //setup queries
        $d = [
            'date1' => $params['start'],
            'date2' => $params['end']
        ];

        // statists week
        $res['estatisticas'] = $this->statistics($params);

        // notas fiscais de entrada
        $data = [];
        $result = DB::select('
            SELECT sum(`valor_nota`) as valor_nota, DATE_FORMAT(`data_compra`,\'%d/%m\') as `data_compra`
            FROM `nota_entrada`
            WHERE
                `data_compra` BETWEEN :date1 AND :date2
            GROUP BY `data_compra`
        ', $d);
        foreach ($result as $nota) {
            $data['data'] = $nota->data_compra;
            $data['nota'] = $nota->valor_nota;
            $notas[] = $data;
        }
        $res['notas'] = $notas;

        return $res;
    }
}