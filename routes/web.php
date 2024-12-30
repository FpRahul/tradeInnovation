<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;

//Users Routes
Route::match(['get','post'],'/', [UsersController::class,'login'])->name('login');

Route::middleware('auth')->group(function () {
    // Users Routes
    Route::prefix('users')->controller(UsersController::class)->group(function () {
        Route::get('/', 'index')->name('users.listing');
        Route::get('/roles', 'roles')->name('users.roles');
        Route::get('/add-role', 'addRole')->name('users.addrole');
        Route::match(['POST', 'GET'], '/add-user/{id?}', 'addUser')->name('users.adduser');
    });

    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

