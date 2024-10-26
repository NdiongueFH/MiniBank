<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;



Route::get('/users/{id}/compte', [UserController::class, 'getCompte']);

// ...
Route::get('/distributeur-transactions', [TransactionController::class, 'index'])->name('distributeur.transactions');


Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


// Route pour le tableau de bord client
Route::get('/client/dashboard', [ClientController::class, 'index'])->name('client.dashboard')->middleware('auth');



Route::post('/deposer', [TransactionController::class, 'deposer'])->name('deposer');
Route::post('/retirer', [TransactionController::class, 'retirer'])->name('retirer');
Route::post('/annuler-transaction', [TransactionController::class, 'annulerTransaction'])->name('annuler.transaction');

Route::get('/client/search', [ClientController::class, 'search'])->name('client.search');

Route::get('/transaction/{id}', [TransactionController::class, 'show'])->name('transaction.show');

Route::get('/distributeur-transactions', [TransactionController::class, 'index'])
    ->name('distributeur.transactions');


Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');


Route::middleware('auth')->group(function () {
    Route::get('/client/transactions', [ClientController::class, 'index'])->name('client.transactions');
});

Route::get('/api/get-account-info/{accountNumber}', [TransactionController::class, 'getAccountInfo']);

Route::get('/transactions/{client_id}', [TransactionController::class, 'getTransactions']);


Route::get('/', function () {
    return view('welcome');
});

Route::get('/side-nav', function () {
    return view('layouts.sidebar-navbar'); // afficheage sidebar et navbar
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
