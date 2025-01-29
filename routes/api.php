<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CidadeController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('refresh', 'refresh')->middleware('auth:api');
    });

    Route::prefix('cidades')->controller(CidadeController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('{id}/medicos', 'medicos')->middleware('auth:api');
    });

    Route::prefix('medicos')->group(function () {
        Route::get('/', [MedicoController::class, 'index']);
        Route::post('/', [MedicoController::class, 'store'])->middleware('auth:api');
        Route::get('{id}/pacientes', [PacienteController::class, 'consultas'])->middleware('auth:api');

        Route::post('/consulta', [ConsultaController::class, 'store'])->middleware('auth:api');
    });

    Route::prefix('pacientes')->controller(PacienteController::class)->middleware('auth:api')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::post('{id}', 'update');
    });

});