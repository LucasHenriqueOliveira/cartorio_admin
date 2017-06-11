<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

error_reporting(E_ALL & ~E_NOTICE);

$api = $app->make(Dingo\Api\Routing\Router::class);


$api->version('v1', function ($api) {

    // certidoes
    $api->get('/certidoes', [
        'uses' => 'App\Http\Controllers\CertidaoController@getCertidoes',
        'as' => 'api.certidoes'
    ]);

    // certidao
    $api->get('/certidao', [
        'uses' => 'App\Http\Controllers\CertidaoController@getCertidao',
        'as' => 'api.certidao'
    ]);

    $api->post('/certidao', [
        'uses' => 'App\Http\Controllers\CertidaoController@postCertidao',
        'as' => 'api.certidao'
    ]);

    $api->delete('/certidao', [
        'uses' => 'App\Http\Controllers\CertidaoController@removeCertidao',
        'as' => 'api.certidao'
    ]);

    // procuracoes
    $api->get('/procuracoes', [
        'uses' => 'App\Http\Controllers\ProcuracaoController@getProcuracoes',
        'as' => 'api.procuracoes'
    ]);

    // procuracao
    $api->get('/procuracao', [
        'uses' => 'App\Http\Controllers\ProcuracaoController@getProcuracao',
        'as' => 'api.procuracao'
    ]);

    $api->post('/procuracao', [
        'uses' => 'App\Http\Controllers\ProcuracaoController@postProcuracao',
        'as' => 'api.procuracao'
    ]);

    $api->delete('/procuracao', [
        'uses' => 'App\Http\Controllers\ProcuracaoController@removeProcuracao',
        'as' => 'api.procuracao'
    ]);

    // testamentos
    $api->get('/testamentos', [
        'uses' => 'App\Http\Controllers\TestamentoController@getTestamentos',
        'as' => 'api.testamentos'
    ]);

    // testamento
    $api->get('/testamento', [
        'uses' => 'App\Http\Controllers\TestamentoController@getTestamento',
        'as' => 'api.testamento'
    ]);

    $api->post('/testamento', [
        'uses' => 'App\Http\Controllers\TestamentoController@postTestamento',
        'as' => 'api.testamento'
    ]);

    $api->delete('/testamento', [
        'uses' => 'App\Http\Controllers\TestamentoController@removeTestamento',
        'as' => 'api.testamento'
    ]);

    // movimentar
    $api->post('/movimentar', [
        'uses' => 'App\Http\Controllers\ProcessoController@movimentar',
        'as' => 'api.movimentar'
    ]);

    // usuarios
    $api->get('/usuarios', [
        'uses' => 'App\Http\Controllers\UsuarioController@getUsuarios',
        'as' => 'api.usuarios'
    ]);

    // usuario
    $api->get('/usuario', [
        'uses' => 'App\Http\Controllers\UsuarioController@getUsuario',
        'as' => 'api.usuario'
    ]);

    $api->post('/usuario', [
        'uses' => 'App\Http\Controllers\UsuarioController@postUsuario',
        'as' => 'api.usuario'
    ]);

    $api->delete('/usuario', [
        'uses' => 'App\Http\Controllers\UsuarioController@removeUsuario',
        'as' => 'api.usuario'
    ]);

    $api->post('/troca-senha', [
        'uses' => 'App\Http\Controllers\UsuarioController@trocaSenha',
        'as' => 'api.usuario.troca.senha'
    ]);


    $api->get('/estados', [
        'uses' => 'App\Http\Controllers\EstadoCidadeController@estados',
        'as' => 'api.estados'
    ]);

    $api->get('/cidades', [
        'uses' => 'App\Http\Controllers\EstadoCidadeController@cidades',
        'as' => 'api.cidades'
    ]);

    // dashboard
    $api->get('/dashboard', [
        'uses' => 'App\Http\Controllers\Controller@dashboard',
        'as' => 'api.dashboard'
    ]);


    // authentication
    $api->post('/auth/login', [
        'as' => 'api.auth.login',
        'uses' => 'App\Http\Controllers\Auth\AuthController@postLogin',
    ]);

    $api->get('/auth/login', function () {
        die('/auth/login');
    });


    $api->group([
        'middleware' => 'api.auth',
    ], function ($api) {
        $api->get('/', [
            'uses' => 'App\Http\Controllers\APIController@getIndex',
            'as' => 'api.index'
        ]);
        $api->get('/auth/user', [
            'uses' => 'App\Http\Controllers\Auth\AuthController@getUser',
            'as' => 'api.auth.user'
        ]);
        $api->patch('/auth/refresh', [
            'uses' => 'App\Http\Controllers\Auth\AuthController@patchRefresh',
            'as' => 'api.auth.refresh'
        ]);
        $api->delete('/auth/invalidate', [
            'uses' => 'App\Http\Controllers\Auth\AuthController@deleteInvalidate',
            'as' => 'api.auth.invalidate'
        ]);
    });
});