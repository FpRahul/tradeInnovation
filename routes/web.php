<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

//Users Routes
Route::get('/', [UsersController::class,'login'])->name('users.login');
Route::get('/users/roles', [UsersController::class,'roles'])->name('users.roles');
Route::get('/users/add-role', [UsersController::class,'addRole'])->name('users.addrole');
Route::get('/users', [UsersController::class,'index'])->name('users.listing');
Route::get('/users/add-user', [UsersController::class,'addUser'])->name('users.adduser');

