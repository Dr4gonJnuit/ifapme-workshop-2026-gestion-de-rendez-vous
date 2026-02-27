<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;


// dashboard pages
Route::get('/', function () {
    return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
})->name('dashboard');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/signup', [RegisterController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [RegisterController::class, 'signup']);
Route::get('/dashboard', function () {
    return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
})->name('dashboard');

// rendez-vous management (ajouté pour la fonctionnalité RDV)
use App\Http\Controllers\RdvController;

Route::middleware(['auth'])->group(function () {
    Route::resource('rdvs', RdvController::class);
    // manual status adjustment
    Route::post('rdvs/{rdv}/status', [RdvController::class, 'updateStatus'])->name('rdvs.updateStatus');
});