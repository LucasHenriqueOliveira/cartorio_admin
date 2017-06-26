<?php

namespace App\Http\Controllers;

use Dingo\Api\Auth\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
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
                                    str_random(10), date('Y-m-d H:i:s'), $request->input('certidao'),
                                    $request->input('procuracao'), $request->input('testamento'), $request->input('usuarios'),
                                    $request->input('usuarios_add'), $request->input('usuarios_editar'), $request->input('usuarios_remover'),
                                    $request->input('relatorios'), $request->input('dashboard'));

        echo json_encode($res);
        exit;
    }

	public function addUsuarioApp(Request $request) {
		$usuario = new \App\Data\Usuario();

		$usuario->addUsuarioApp($request->input('nome'),$request->input('email'), $request->input('cpf'),
			$request->input('telefone'), Hash::make(stripslashes($request->input('password'))),
			str_random(10), date('Y-m-d H:i:s'));

		$res = $this->getToken($request);
		$usuario->email($request->input('nome'),$request->input('email'));

		echo json_encode($res);
		exit;
	}

	protected function getToken(Request $request) {
		try {
			// Attempt to verify the credentials and create a token for the user
			if (!$token = JWTAuth::attempt(
				$this->getCredentials($request)
			)) {
				return new JsonResponse([
					'message' => 'invalid_credentials'
				], Response::HTTP_UNAUTHORIZED);
			}
		} catch (JWTException $e) {
			// Something went wrong whilst attempting to encode the token
			return new JsonResponse([
				'message' => 'could_not_create_token'
			], Response::HTTP_INTERNAL_SERVER_ERROR);
		}

		return [
			'message' => 'token_generated',
			'data' => [
				'token' => $token,
			]
		];
	}

	protected function getCredentials(Request $request) {
		return $request->only('email', 'password');
	}

    public function editarUsuario(Request $request) {
        $usuario = new \App\Data\Usuario();

        $res = $usuario->editarUsuario($request->input('users_id'), $request->input('nome'),$request->input('email'),
                                       date('Y-m-d H:i:s'), $request->input('certidao'), $request->input('procuracao'),
                                       $request->input('testamento'), $request->input('usuarios'), $request->input('usuarios_add'),
                                       $request->input('usuarios_editar'), $request->input('usuarios_remover'),
                                       $request->input('relatorios'), $request->input('dashboard'));

        echo json_encode($res);
        exit;
    }

    public function removerUsuario(Request $request) {
        $usuario = new \App\Data\Usuario();

        $usuario->removerUsuario($request->input('id'), date('Y-m-d H:i:s'));
        $res = $usuario->getUsuarios();

        echo json_encode($res);
        exit;
    }
}
