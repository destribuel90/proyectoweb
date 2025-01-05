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
session_start();
Route::get('/', function () {
    return view('mainpage');
});


Route::get('/vender', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['user_id'])) {
        return view('sellproducts');
    }
    return view('sesion');
});


Route::get('/sesion', function () {
    return view('sesion');
});


Route::get('/products/{id}', function () {
    return view('vistaProducto');
});
Route::get('/registro', function () {
    return view('registro');
});
Route::get('/cart', function () {
    return view('cart');
});
Route::get('/search/{data}', function () {
    return view('search');
});

Route::get('/perfil', function () {
    return view('perfil');
});




