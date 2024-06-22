<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::post('/register-user', [UserController::class, 'registerUsers'])->name('register-user');
Route::post('/login-users', [UserController::class, 'loginUsers'])->name('login-users');

 /* Rustas para el manejo de las Categorías */
 Route::post('/create-category', [CategoriesController::class, 'createAndUpdateCategory'])->name('create-category');
 Route::put('/update-category', [CategoriesController::class, 'createAndUpdateCategory'])->name('update-category');
 Route::get('/all-categories', [CategoriesController::class, 'getCategories'])->name('all-categories');
 Route::get('/one-category/{id}', [CategoriesController::class, 'getCategories'])->name('one-category');
 Route::delete('/delete-category/{id}',[CategoriesController::class, 'deleteCategory'])->name('delete-category');

 /* Rustas de la api para el manejo de los Productos */
 Route::post('/create-product', [ProductsController::class, 'createAndUpdateProduct'])->name('create-product');
 Route::put('/update-product', [ProductsController::class, 'createAndUpdateProduct'])->name('update-product');
 Route::get('/get-products/{category}', [ProductsController::class, 'getProducts'])->name('get-products');
 Route::get('/get-total-products/{category}', [ProductsController::class, 'getTotalProductsByCategory'])->name('get-total-products');
 Route::delete('/delete-products/{id}',[ProductsController::class,'deleteProducts'])->name('delete-products');

/* Route::middleware('auth.check')->group(function ()
{
  
}); */
