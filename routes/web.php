<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});


// Route::resource('products', ProductController::class);

Route::controller(ProductController::class)->group(function (){
    Route::get('/products', 'index')->name('products.index');
    Route::get('/products/create', 'create')->name('products.create');
    Route::post('/products', 'store')->name('products.store');
    Route::get('/products/show', 'show')->name('products.show');
    Route::get('/products/edit', 'edit')->name('products.edit');
    Route::put('/products/update', 'update')->name('products.update');
    Route::delete('/products/destroy', 'destroy')->name('products.destroy');
    
});