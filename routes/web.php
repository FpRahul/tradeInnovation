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
    Route::get('/logout',[UsersController::class,'logout'])->name('user.logout');
    Route::match(['POST','GET'],'/myprofile',[UsersController::class,'myprofile'])->name('user.myprofile');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/chart-data', [DashboardController::class, 'chartData'])->name('chart.data');

    //Users Routes
    Route::prefix('users')->controller(UsersController::class)->group(function () {
        Route::match(['POST','GET'],'/', 'index')->name('users.listing');
        Route::match(['POST', 'GET'], '/add-user/{id?}', 'addUser')->name('users.adduser');
        Route::post('/delete', 'deleteEmployee')->name('users.delete');

        Route::match(['POST','GET'],'/client', 'clients')->name('client.listing');
        Route::match(['POST', 'GET'], '/add-client/{id?}', 'addClient')->name('users.addclient');


        Route::match(['POST','GET'],'/associates', 'associates')->name('associate.listing');
        Route::match(['POST', 'GET'], '/add-associate/{id?}', 'addAssociate')->name('users.addassociate');

        Route::get('/userstatus','userStatus')->name('users.status');
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

