<?php

use App\Http\Controllers\Products;
use App\Http\Controllers\Users;
use App\Http\Controllers\Ventas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/products', [Products::class, 'index']);
Route::post('/products', [Products::class, 'store']);
Route::get('/products/{id}', [Products::class, 'show']);
Route::put('/products/{id}', [Products::class, 'update']);
Route::delete('/products/{id}', [Products::class, 'destroy']);
Route::get('search/{data}', [Products::class, 'search']);
Route::post('/users', [Users::class, 'store']);
Route::get('/users/{id}', [Users::class, 'show']);
Route::put('/users/{id}', [Users::class, 'update']);
Route::delete('/users/{id}', [Users::class, 'destroy']);
Route::post('/sesion', [Users::class, 'sesion']);
Route::get('/sessionStatus', [Users::class, 'session_status']);
Route::delete('/logout', [Users::class, 'logout']);
Route::post('/venta', [Ventas::class, 'store']);

