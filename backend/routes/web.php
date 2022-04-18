<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->post('/register', 'LoginController@register');
$router->post('/login', 'LoginController@login');

$router->group(['prefix' => 'transferencias', 'middleware' => 'auth'], function () use ($router) {
    $router->get('/', 'TransferenciaController@index');
    $router->get('/{id}', 'TransferenciaController@show');
});

$router->group(['prefix' => 'csv',  'middleware' => 'auth'], function () use ($router) {
    $router->get('/', 'CsvController@index');
    $router->post('/', [
        'middleware' => ['upload', 'process'], 
        'uses' =>'CsvController@store',
    ]);
});