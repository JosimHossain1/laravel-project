<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('products', ProductController::class);
});

require __DIR__.'/auth.php';
