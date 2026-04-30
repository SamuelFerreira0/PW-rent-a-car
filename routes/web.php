<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\VeiculoController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // RESERVAS
    Route::get('/reservas', [ReservaController::class, 'index']);
    Route::get('/reservas/create', [ReservaController::class, 'create']);
    Route::post('/reservas', [ReservaController::class, 'store']);
    Route::post('/reservas/check', [ReservaController::class, 'checkDisponibilidade']);
    Route::get('/reservas/{id}/edit', [ReservaController::class, 'edit']);
    Route::put('/reservas/{id}', [ReservaController::class, 'update']);
    Route::delete('/reservas/{id}', [ReservaController::class, 'destroy']);
    Route::put('/reservas/{id}/concluir', [ReservaController::class, 'concluir']);
    Route::post('/reservas/preview', [ReservaController::class, 'preview']);

    // VEICULOS
    Route::get('/veiculos', [VeiculoController::class, 'index']);
    Route::get('/veiculos/create', [VeiculoController::class, 'create']);
    Route::post('/veiculos', [VeiculoController::class, 'store']);
    Route::get('/veiculos/{id_veiculo}/edit', [VeiculoController::class, 'edit']);
    Route::put('/veiculos/{id_veiculo}', [VeiculoController::class, 'update']);
    Route::delete('/veiculos/{id_veiculo}', [VeiculoController::class, 'destroy']);
});

require __DIR__.'/auth.php';