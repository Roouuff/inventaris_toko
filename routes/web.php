<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;

// Hanya untuk yang BELUM login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

// Hanya untuk yang SUDAH login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route::get('/', function () {
    //     return view('home');
    // })->name('home');

    Route::get('/', fn () => redirect()->route('items.index'))->name('home');

    // Route kategori dan barang akan ditambahkan di sini (Langkah 6 dan 7)
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('items', ItemController::class)->except(['show']);
});