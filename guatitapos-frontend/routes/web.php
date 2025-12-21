<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard.inicio');
});

Route::get('/productos', function () {
    return view('dashboard.productos');
});

Route::get('/impresoras',function () {
    return view('dashboard.impresoras');
});

Route::get('/pedidos',function () {
    return view('dashboard.pedidos');
});

Route::get('/pedidos-dia',function () {
    return view('dashboard.pedidos-dia');
});

Route::get('/reportes',function () {
    return view('dashboard.reportes');
});