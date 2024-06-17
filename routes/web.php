<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () { return redirect('/admin/login'); })->name('login');
Route::get('/register', function () { return redirect('/admin/register'); })->name('register');

Route::get('/admin/users/{user}')->name('users.view');
Route::get('/run-migration', function() {

    Artisan::call('optimize:clear');
    Artisan::call('migrate:fresh --sedd');

    return "Migrations executed successfully";
});
