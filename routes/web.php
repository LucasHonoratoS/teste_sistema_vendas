<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ParcelaController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('clientes', ClienteController::class);
    Route::resource('produtos', ProdutoController::class);
    Route::resource('parcelas', ParcelaController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('vendas', VendaController::class);

    Route::get('/vendas/{venda}/pdf', [VendaController::class, 'gerarPdf'])->name('vendas.pdf');
});

require __DIR__.'/auth.php';
