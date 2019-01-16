<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//============ MÁQUINA DE PONTO PÚBLICA ==============================================
Route::get('/', 'EstadosController@index');

Route::post('/pontos'     , 'EstadosController@checarEstadosVisiveis');
Route::post('/ativarPonto', 'MaquinaPontoController@ativarEstado');


//============ ADMINISTRADOR =========================================================
Route::match(['get', 'post'], '/admin', 'AdminController@registros');

Route::get('/admin-funcionarios', 'AdminController@funcionarios');
Route::get('/novo-funcionario'  , 'FuncionarioController@index');

Route::post('/criar-funcionario'  , 'FuncionarioController@criarFuncionario');
Route::post('/remover-funcionario', 'FuncionarioController@removeFuncionario');