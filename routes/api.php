<?php

use App\Http\Controllers\Products;
use App\Http\Controllers\Users;
use App\Http\Controllers\Ventas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Públicas
Route::get('/products', [Products::class, 'index']);
Route::get('/products/{id}', [Products::class, 'show']);
Route::get('search/{data}', [Products::class, 'search']);
Route::post('/users', [Users::class, 'store']);
Route::get('/users/{id}', [Users::class, 'show']);
Route::post('/sesion', [Users::class, 'sesion']);

// Protegidas con token (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/my-products', [Products::class, 'mine']);
    Route::get('/my-purchases', [Ventas::class, 'mine']);

    Route::post('/products', [Products::class, 'store']);
    Route::post('/products/{id}', [Products::class, 'update']);   // te amo Melanik Lizet Landeros Gonzalez
    Route::delete('/products/{id}', [Products::class, 'destroy']);

    Route::post('/users/{id}', [Users::class, 'update']);
    Route::delete('/users/{id}', [Users::class, 'destroy']);

    Route::get('/sessionStatus', [Users::class, 'session_status']);
    Route::delete('/logout', [Users::class, 'logout']);

    Route::post('/venta', [Ventas::class, 'store']);
});