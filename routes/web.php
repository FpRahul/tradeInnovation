<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;

//Users Routes
Route::match(['get','post'],'/', [UsersController::class,'login'])->name('login');

Route::get('/users/roles', [UsersController::class,'roles'])->name('users.roles');
Route::get('/users/add-role', [UsersController::class,'addRole'])->name('users.addrole');
Route::get('/users', [UsersController::class,'index'])->name('users.listing');
Route::match(['POST','GET'],'/users/add-user', [UsersController::class,'addUser'])->name('users.adduser')->middleware('auth');

//Dashboard routes
Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard')->middleware('auth');

