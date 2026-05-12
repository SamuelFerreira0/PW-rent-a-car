<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CatalogoController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');

Route::get('/dashboard', fn() => redirect()->route('home'))
    ->middleware('auth')
    ->name('dashboard');

// Todos os utilizadores autenticados
Route::middleware(['auth'])->group(function () {

    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::get('/reservas/create', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::post('/reservas/check', [ReservaController::class, 'checkDisponibilidade'])->name('reservas.check');
    Route::post('/reservas/preview', [ReservaController::class, 'preview'])->name('reservas.preview');
    Route::put('/reservas/{id}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
    Route::get('/reservas/{id}', [ReservaController::class, 'show'])->name('reservas.show');
});

// Só funcionários e admins
Route::middleware(['auth', 'isFuncionario'])->group(function () {

    Route::get('/reservas/{id}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('/reservas/{id}', [ReservaController::class, 'update'])->name('reservas.update');
    Route::put('/reservas/{id}/concluir', [ReservaController::class, 'concluir'])->name('reservas.concluir');

    Route::get('/veiculos', [VeiculoController::class, 'index'])->name('veiculos.index');
    Route::get('/veiculos/create', [VeiculoController::class, 'create'])->name('veiculos.create');
    Route::post('/veiculos', [VeiculoController::class, 'store'])->name('veiculos.store');
    Route::get('/veiculos/{id_veiculo}/edit', [VeiculoController::class, 'edit'])->name('veiculos.edit');
    Route::put('/veiculos/{id_veiculo}', [VeiculoController::class, 'update'])->name('veiculos.update');
    Route::delete('/veiculos/{id_veiculo}', [VeiculoController::class, 'destroy'])->name('veiculos.destroy');
});

// Só admin
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::put('/users/{id}/promover', [AdminController::class, 'promoverFuncionario'])->name('promover');
    Route::put('/users/{id}/revogar', [AdminController::class, 'revogarFuncionario'])->name('revogar');
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('destroy');
});

require __DIR__.'/auth.php';
