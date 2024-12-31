<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;

//Users Routes
Route::match(['get','post'],'/', [UsersController::class,'login'])->name('login');

Route::middleware('auth')->group(function () {
    //Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Users Routes
    Route::prefix('users')->controller(UsersController::class)->group(function () {
        Route::get('/', 'index')->name('users.listing');
        Route::match(['POST', 'GET'], '/add-user/{id?}', 'addUser')->name('users.adduser');

        Route::get('/clients', 'clients')->name('client.listing');
        Route::match(['POST', 'GET'], '/add-client/{id?}', 'addClient')->name('users.addclient');

        Route::get('/associates', 'associates')->name('associate.listing');
        Route::match(['POST', 'GET'], '/add-associate/{id?}', 'addAssociate')->name('users.addassociate');
    });

    //Settings Routes
    Route::prefix('settings')->controller(SettingsController::class)->group(function () {
        Route::get('/roles', 'roles')->name('settings.roles');
        Route::get('/add-role', 'addRole')->name('settings.addrole');
    });
    
});

