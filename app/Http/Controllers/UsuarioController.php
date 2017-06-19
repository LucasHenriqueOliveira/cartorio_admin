<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends BaseController{

    public function getUsuarios(Request $request) {
        $usuarios = new \App\Data\Usuario();

        $res = $usuarios->getUsuarios();

        echo json_encode($res);
        exit;
    }

    public function getUsuario(Request $request) {
        $usuario = new \App\Data\Usuario();

        $res = $usuario->getUsuario($request->input('id'));

        echo json_encode($res);
        exit;
    }

    public function addUsuario(Request $request) {
        $usuario = new \App\Data\Usuario();

        $res = $usuario->addUsuario($request->input('nome'),$request->input('email'),
                                    Hash::make(stripslashes($request->input('email'))),
                                    str_random(10), date('Y-m-d h:i:s'), $request->input('certidao'),
                                    $request->input('procuracao'), $request->input('testamento'), $request->input('usuarios'),
                                    $request->input('usuarios_add'), $request->input('usuarios_editar'), $request->input('usuarios_remover'),
                                    $request->input('relatorios'), $request->input('dashboard'));

        echo json_encode($res);
        exit;
    }

    public function editarUsuario(Request $request) {
        $usuario = new \App\Data\Usuario();

        $res = $usuario->editarUsuario($request->input('users_id'), $request->input('nome'),$request->input('email'),
                                       date('Y-m-d h:i:s'), $request->input('certidao'), $request->input('procuracao'),
                                       $request->input('testamento'), $request->input('usuarios'), $request->input('usuarios_add'),
                                       $request->input('usuarios_editar'), $request->input('usuarios_remover'),
                                       $request->input('relatorios'), $request->input('dashboard'));

        echo json_encode($res);
        exit;
    }

    public function removerUsuario(Request $request) {
        $usuario = new \App\Data\Usuario();

        $usuario->removerUsuario($request->input('id'), date('Y-m-d h:i:s'));
        $res = $usuario->getUsuarios();

        echo json_encode($res);
        exit;
    }
}
