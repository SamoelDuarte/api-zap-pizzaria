<?php

use App\Http\Controllers\admin\MotoboyController;
use App\Http\Controllers\Api\FirebaseTokenController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/pedidos', [OrderController::class, 'index']);
Route::post('/salvar-token', [FirebaseTokenController::class, 'store']);
Route::post('/pedidos/{id}/finalizar', [OrderController::class, 'marcarComoFeito']);

// routes/api.php
Route::get('/motoboys', [MotoboyController::class, 'get']);
// routes/api.php
Route::post('/atribuir-motoboy', [OrderController::class, 'atribuirMotoboy']);




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
