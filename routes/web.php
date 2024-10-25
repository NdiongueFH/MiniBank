<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DepotController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Tableaux de bord
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/client', [DashboardController::class, 'clientDashboard'])->name('dashboard.client');
    Route::get('/dashboard/agent', [DashboardController::class, 'agentDashboard'])->name('dashboard.agent');
    Route::get('/dashboard/distributeur', [DashboardController::class, 'distributeurDashboard'])->name('dashboard.distributeur');


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
Route::post('/transaction/retrait', [TransactionController::class, 'retirer'])->name('transaction.retrait');
Route::get('/dashboard/agent', [TransactionController::class, 'dashboard'])->name('dashboard.agent1');






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
