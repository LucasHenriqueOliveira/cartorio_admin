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
    // tests for sources
    $api->get('/test/mediabong', [
        'uses' => 'App\Http\Controllers\Test\TestController@mediabong',
        'as' => 'test'
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