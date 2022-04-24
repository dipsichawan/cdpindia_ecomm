<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Auth::routes();
//show admin form
Route::get('/register/{role}', [App\Http\Controllers\Auth\RegisterController::class, 'admin_register'])->name('admin_register');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products');
    //Route::get('/product/{id}', [App\Http\Controllers\ProductControler::class, 'fetch'])->name('single_product');
});

Route::middleware(['auth','isAdmin'])->group(function () {
    Route::get('/product/all', [App\Http\Controllers\ProductController::class, 'view'])->name('view_product');
    Route::get('/product/add', [App\Http\Controllers\ProductController::class, 'add'])->name('add_product');
    Route::post('/product/insert', [App\Http\Controllers\ProductController::class, 'insert'])->name('insert_product');
    Route::get('/product/edit/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('edit_product');
    Route::put('/product/update/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('update_product');
    Route::get('/product/delete/{id}', [App\Http\Controllers\ProductController::class, 'delete'])->name('delete_product');
});
