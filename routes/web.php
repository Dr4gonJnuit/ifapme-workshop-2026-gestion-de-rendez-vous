<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PrestataireController;

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

Route::get('/prestataire', [PrestataireController::class, 'index'])->name('prestataire.index');
Route::post('/prestataire', [PrestataireController::class, 'store'])->name('prestataire.store');
Route::delete('/prestataire/{id}', [PrestataireController::class, 'destroy'])->name('prestataire.destroy');
Route::put('/prestataire/{id}', [PrestataireController::class, 'update'])->name('prestataire.update');