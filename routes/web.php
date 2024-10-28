<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AgentController;


//Route de Bamba

Route::get('/', function () {
    return view('welcome');
});

Route::get('/side-nav', function () {
    return view('layouts.sidebar-navbar'); // afficheage sidebar et navbar
});

// Tableaux de bord
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/client', [DashboardController::class, 'clientDashboard'])->name('dashboard.client');
    //Route::get('/dashboard/agent', [TransactionController::class, 'agentDashboard'])->name('dashboard.agent');
    Route::get('/dashboard/distributeur', [DashboardController::class, 'distributeurDashboard'])->name('dashboard.distributeur');
});


//Route Moustapha 


   // Tableaux de bord
Route::middleware(['auth'])->group(function () {
    

    //Route::get('/dashboard/agent', [TransactionController::class, 'agentDashboard'])->name('dashboard.agent');

    Route::get('/Agent/transactions', [TransactionController::class, 'agentTransactions'])->name('transactions');
    Route::get('/transactions/canceled', [TransactionController::class, 'canceledTransactions'])->name('transactions.canceled');
    Route::get('/transaction/check-distributor', [TransactionController::class, 'showTransactionForm'])->name('transaction.checkDistributor');
    Route::get('/transaction/create', [TransactionController::class, 'create'])->name('transaction.create');
    Route::post('/transaction/store', [TransactionController::class, 'store'])->name('transaction.store');
    Route::post('/transaction/cancel/{id}', [TransactionController::class, 'cancel'])->name('transaction.cancel');

    // routes/web.php
    Route::get('/transaction/annulees', [TransactionController::class, 'showCancelledTransactions'])->name('transactions.annulees');

    Route::get('/agent/users', [UserController::class, 'listUsers'])->name('agent.users');
    Route::post('/agent/user/{id}/block', [UserController::class, 'blockUser'])->name('user.block');
    Route::post('/agent/user/{id}/unblock', [UserController::class, 'unblockUser'])->name('user.unblock');
    Route::get('/agent/search-user', [UserController::class, 'searchUser'])->name('agent.searchUser');
    Route::post('/transaction/retrait', [TransactionController::class, 'retirerAgent'])->name('transaction.retrait');
    Route::get('/dashboard/agent', [TransactionController::class, 'dashboard'])->name('dashboard.agent1');



});
 Route::get('/dashboard', function () {
     return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');






//Route de Hawa
Route::get('/users/{id}/compte', [UserController::class, 'getCompte']);

// ...
Route::get('/distributeur-transactions', [TransactionController::class, 'index'])->name('distributeur.transactions');


Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


// Route pour le tableau de bord client
Route::get('/client/dashboard', [ClientController::class, 'index'])->name('client.dashboard')->middleware(middleware: 'auth');



Route::post('/deposer', [TransactionController::class, 'deposer'])->name('deposer');
Route::post('/retirer', [TransactionController::class, 'retirer'])->name('retirer');
Route::post('/annuler-transaction', [TransactionController::class, 'annulerTransaction'])->name('annuler.transaction');

Route::post('/transferer', [ClientController::class, 'transfer'])->name('transferer');


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
