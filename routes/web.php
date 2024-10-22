<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/side-navC', function () {
    return view('layouts.sidebar-navbarC'); // afficheage sidebar et navbar
});

Route::get('/side-navA', function () {
    return view('layouts.sidebar-navbarA'); // afficheage sidebar et navbar
});

Route::get('/side-navD', function () {
    return view('layouts.sidebar-navbarD'); // afficheage sidebar et navbar
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
