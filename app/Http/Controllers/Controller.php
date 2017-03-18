<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;

class Controller extends BaseController{

    public function dashboard(Request $request) {
        $dashboard = new \App\Data\Source();

        $res = $dashboard->dashboard([
            'start' => $request->input('start'),
            'end' => $request->input('end'),
            'publisher' => $request->input('publisher')
        ]);

        echo json_encode($res);
        exit;
    }
}
