<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () { return redirect('/admin/login'); })->name('login');
Route::get('/register', function () { return redirect('/admin/register'); })->name('register');