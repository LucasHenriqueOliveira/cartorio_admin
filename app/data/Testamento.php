<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;
use DateTime;

class Testamento extends Utils {

    public function getTestamentos() {
        $result = $this->checkPermissão('testamento');

        if($result) {
            $pedidos = $this->getPedidos('Testamento');

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

    public function getTestamento($id) {
        $result = $this->checkPermissão('testamento');

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

    public function addTestamento($date, $hour, $user_id) {
		$result = $this->checkCalendarioRestricao($date, $hour);

		if(count($result)) {
			$res['error'] = true;
			$res['message'] = 'Data e Hora não permitidos para agendamento.';
			return $res;
		}

		$this->addCalendarioRestricao($date, $hour);

        return DB::insert('INSERT INTO `pedido` (`tipo`, `data_hora`, `user_id`, `agendamento`, `status`) VALUES (?, ?, ?, ?, ?)',
        ['Testamento', date('Y-m-d H:i:s'), $user_id, $date.' '. $hour, 'Aguardando']);
    }

	public function getDatasTestamento() {
		$arrDates = array();
		$dates = array();
		$arr = array();
		for($i = 0; $i < 8; $i++) {
			$date = $i == 0 ? $this->getWednesday(date('Y-m-d')) : $this->getWednesday($dates[$i - 1]);
			array_push($dates, $date);

			// @TODO - remover hard-code do horário 13:00 as 16:00
			$arrDates[$date] = array("13:00","13:30","14:00","14:30","15:00","15:30","16:00");
		}

		foreach ($arrDates as $key => $value) {
			foreach ($value as $val) {
				$result = DB::select("SELECT * FROM `calendario_restricoes` WHERE `data` = ? AND `hora` = ?", [$key, $val]);

				if(!count($result)) {
					if (!array_key_exists($key, $arr)) {
						$arr[$key] = array();
					}
					array_push($arr[$key], $val);
				}
			}
		}
		return $arr;
	}

	private function getWednesday($day) {
		$date = new DateTime($day);
		$date->modify('next wednesday');
		return $date->format('Y-m-d');
	}
}