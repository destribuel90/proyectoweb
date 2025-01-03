<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('mainpage');
});
Route::get('/vender', function () {
    return view('sellproducts');
});
Route::get('/products/{id}', function () {
    return view('vistaProducto');
});
Route::get('/sesion', function () {
    return view('sesion');
});
Route::get('/registro', function () {
    return view('registro');
});

