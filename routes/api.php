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

    $api->get('/produtos', [
        'uses' => 'App\Http\Controllers\ProdutoController@produtos',
        'as' => 'api.produtos'
    ]);

    $api->post('/produto', [
        'uses' => 'App\Http\Controllers\ProdutoController@addProduto',
        'as' => 'api.add.produto'
    ]);

    $api->delete('/produto', [
        'uses' => 'App\Http\Controllers\ProdutoController@removeProduto',
        'as' => 'api.remove.produto'
    ]);

    $api->put('/produto', [
        'uses' => 'App\Http\Controllers\ProdutoController@atualizarProduto',
        'as' => 'api.atualizar.produto'
    ]);

    $api->get('/fornecedores', [
        'uses' => 'App\Http\Controllers\FornecedorController@fornecedores',
        'as' => 'api.fornecedores'
    ]);

    $api->post('/fornecedor', [
        'uses' => 'App\Http\Controllers\FornecedorController@addFornecedor',
        'as' => 'api.add.fornecedor'
    ]);

    $api->delete('/fornecedor', [
        'uses' => 'App\Http\Controllers\FornecedorController@removeFornecedor',
        'as' => 'api.remove.fornecedor'
    ]);

    $api->put('/fornecedor', [
        'uses' => 'App\Http\Controllers\FornecedorController@atualizarFornecedor',
        'as' => 'api.atualizar.fornecedor'
    ]);

    $api->get('/estados', [
        'uses' => 'App\Http\Controllers\EstadoCidadeController@estados',
        'as' => 'api.estados'
    ]);

    $api->get('/cidades', [
        'uses' => 'App\Http\Controllers\EstadoCidadeController@cidades',
        'as' => 'api.cidades'
    ]);

    $api->get('/notas-entrada', [
        'uses' => 'App\Http\Controllers\NotaEntradaController@notas',
        'as' => 'api.notas.entrada'
    ]);

    $api->post('/nota-entrada', [
        'uses' => 'App\Http\Controllers\NotaEntradaController@addNotaEntrada',
        'as' => 'api.add.nota.entrada'
    ]);

    $api->delete('/nota-entrada', [
        'uses' => 'App\Http\Controllers\NotaEntradaController@removeNotaEntrada',
        'as' => 'api.remove.nota.entrada'
    ]);

    $api->put('/nota-entrada', [
        'uses' => 'App\Http\Controllers\NotaEntradaController@atualizarNotaEntrada',
        'as' => 'api.atualizar.nota.entrada'
    ]);

    // relatorio
    $api->post('/relatorio', [
        'uses' => 'App\Http\Controllers\RelatorioController@relatorio',
        'as' => 'api.relatorio'
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