<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SettingsController;

//Users Routes
Route::match(['get','post'],'/', [UsersController::class,'login'])->name('login');
Route::match(['get','post'],'forget_password', [UsersController::class,'forgetPassword'])->name('forgetPassword');
Route::middleware('auth')->group(function () {
    //Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Users Routes
    Route::prefix('users')->controller(UsersController::class)->group(function () {
        Route::match(['POST','GET'],'/', 'index')->name('users.listing');
        Route::match(['POST', 'GET'], '/add-user/{id?}', 'addUser')->name('users.adduser');
        Route::post('/delete', 'deleteEmployee')->name('users.delete');

        Route::get('/clients', 'clients')->name('client.listing');
        Route::match(['POST', 'GET'], '/add-client/{id?}', 'addClient')->name('users.addclient');


        Route::get('/associates', 'associates')->name('associate.listing');
        Route::match(['POST', 'GET'], '/add-associate/{id?}', 'addAssociate')->name('users.addassociate');

        Route::post('/userstatus','userStatus')->name('users.status');
    });

    //Settings Routes
    Route::prefix('settings')->controller(SettingsController::class)->group(function () {
        Route::get('/roles', 'roles')->name('settings.roles');
        Route::match(['POST','GET'],'/add-role', 'addRole')->name('settings.addrole');
    });

    //Leads Routes
    Route::prefix('leads')->controller(LeadsController::class)->group(function () {
        Route::get('/', 'index')->name('leads.index');
        Route::get('/add', 'add')->name('leads.add');
        Route::get('/sendquote', 'sendquote')->name('leads.quote');
        Route::get('/logs', 'leadLogs')->name('leads.logs');
    });

    //Services Routes
    Route::prefix('services')->controller(ServicesController::class)->group(function () {
        Route::get('/', 'index')->name('services.index');
        Route::get('/add', 'add')->name('services.add');
    });
    
});

