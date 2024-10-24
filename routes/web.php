<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
  


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard/data', [UserController::class, 'getDashboardData'])->name('dashboard.data');


// Ajoutez cette route dans vos fichiers de routes
Route::get('/distributeur/dashboard', [UserController::class, 'showDashboard'])->name('distributeur.dashboard');

Route::get('/side-navC', function () {
    return view('layouts.sidebar-navbarC'); // afficheage sidebar et navbar
});

Route::get('/side-navA', function () {
    return view('layouts.sidebar-navbarA'); // afficheage sidebar et navbar
});

Route::get('/side-navD', function () {
    return view('layouts.sidebar-navbarD'); // afficheage sidebar et navbar
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
