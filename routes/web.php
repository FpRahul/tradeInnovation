<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TasksController;
use App\Http\Middleware\CheckPermission;
use App\Http\Controllers\StagesController;


//Users Routes
Route::match(['get','post'],'/', [UsersController::class,'login'])->name('login');
Route::match(['get','post'],'forget_password', [UsersController::class,'forgetPassword'])->name('forgetPassword');
Route::get('reset/password/{token?}',[UsersController::class, 'resetPassword'])->name('resetPassword.resetPassword');
Route::post('password/reset',[UsersController::class, 'passwordReset'])->name('passwordReset.passwordReset');

Route::middleware(['auth', CheckPermission::class])->group(function () {
    //Dashboard Routes
    Route::get('/logout',[UsersController::class,'logout'])->name('user.logout');
    Route::match(['POST','GET'],'/myprofile',[UsersController::class,'myprofile'])->name('user.myprofile');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/chart-data', [DashboardController::class, 'chartData'])->name('chart.data');   


    //Users Routes
    Route::prefix('users')->controller(UsersController::class)->group(function () {
        Route::match(['POST','GET'],'/', 'index')->name('users.listing');
        Route::match(['POST','GET'], '/add-user/{id?}', 'addUser')->name('users.adduser');
        Route::get('/delete/{id?}', 'deleteUser')->name('users.delete');
        Route::post('/deleterepeater', 'deleteRepeaterUser')->name('users.deleterepeater');
        Route::match(['POST','GET'], '/client', 'clients')->name('client.listing');
        Route::match(['POST','GET'], '/add-client/{id?}', 'addClient')->name('users.addclient');
        Route::match(['POST','GET'], '/associates', 'associates')->name('associate.listing');
        Route::match(['POST','GET'], '/add-associate/{id?}', 'addAssociate')->name('users.addassociate');

        Route::match(['POST', 'GET'], '/user-professions', 'userProfessions')->name('professions.index');
        Route::match(['POST', 'GET'], '/add-professions/{id?}', 'addProfessions')->name('professions.add');

        Route::match(['POST', 'GET'], '/user-incorporation', 'userIncorporation')->name('incorporation.index');
        Route::match(['POST', 'GET'], '/add-incorporation/{id?}', 'addIncorporation')->name('incorporation.add');

        Route::match(['POST', 'GET'], '/user-referral', 'userReferral')->name('referral.index');
        Route::match(['POST', 'GET'], '/add-referral/{id?}', 'addReferral')->name('referral.add');
  
        Route::get('/category-status/{id?}', 'categoryStatus')->name('users.category.status');
        Route::get('/category-delete/{id?}', 'categoryDelete')->name('users.category.delete');

        Route::get('/panel-logs', 'panelLogs')->name('logs.index');
        Route::get('/get/action/logs' ,'panelLogs')->name('getActionLog.log');

        Route::get('/view-logs', 'viewLogs')->name('logs.view');

        Route::get('/userstatus','userStatus')->name('users.status');
    });
    //Settings Routes
    Route::prefix('settings')->controller(SettingsController::class)->group(function () {
        Route::get('/roles', 'roles')->name('settings.roles');
        Route::match(['POST','GET'],'/add-role/{id?}', 'addRole')->name('settings.addrole');
        Route::get('assign-menu','viewMenu')->name('setting.viewMenu');
        Route::post('get-menu','getMenu')->name('setting.getMenu');
        
         //stages controller
        Route::prefix('stages')->controller(StagesController::class)->group(function (){
          Route::get('/', 'index')->name('stages.index');
          Route::post('/create', 'create')->name('stages.create');

        });
    });
    //Leads Routes
    Route::prefix('leads')->controller(LeadsController::class)->group(function () {
        Route::match(['POST','GET'],'/', 'index')->name('leads.index');
        Route::get('/lead-logs','leadLogs')->name('leadLogs.index');
        Route::match(['POST','GET'],'/add/{id?}', 'add')->name('leads.add');
        Route::get('/sendquote', 'sendquote')->name('leads.quote');

        Route::get('/logs', 'leadLogs')->name('leads.logs');
        
        Route::post('/get-logs', 'getLogs')->name('leads.getLogs');
        Route::POST('/getsubservice','getSubService')->name('lead.subservice');
        Route::match(['POST','GET'],'/getsourcetypename','getSourceTypeName')->name('lead.getsourcetypename');
        Route::post('/deleterepeater', 'deleteRepeaterLead')->name('lead.deleterepeater');
        Route::post('/deleteattchmentrepeater', 'deleteAttachmentRepeaterLead')->name('lead.deleteattachmentrepeater');
        Route::match(['POST','GET'],'/archive/{id?}', 'archiveLead')->name('leads.archive');
        Route::POST('/setassign','setAssignToUser')->name('leads.assign');
    });
    //Tasks Routes
    Route::prefix('tasks')->controller(TasksController::class)->group(function () {
        Route::get('/', 'index')->name('task.index');
        Route::get('/logs', 'logs')->name('task.log');
        Route::get('/details/{id}', 'detail')->name('task.detail');
        Route::get('/follow-up/view', 'assignTask')->name('task.assignTask');
        Route::get('/follow-up/{id}/{serviceId}/{stageId}', 'followUp')->name('task.followup');
        Route::get('/check-duplicate/{id}', 'chekDuplication')->name('task.chekDuplication');
        Route::post('/duplicate-status/{id}', 'duplicateVerified')->name('task.documentVerified');
        Route::get('/send-quotation/next-stage/{id}', 'documentVerifiedChildSatge')->name('task.documentVerifiedChildSatge');
        Route::post('/send-quotation/{id}','sendQuotation')->name('task.sendQuotation');
        Route::get('/check-payment/{id}','checkPayment')->name('task.checkPayment');
        Route::post('/payment-status/{id}','paymentStatus')->name('task.paymentStatus');
        Route::get('documentation/{id}','documentation')->name('task.documentation');
        Route::Post('documentation-status/{id}','documenStatus')->name('task.documenStatus');

    });
    //Services Routes
    Route::prefix('services')->controller(ServicesController::class)->group(function () {
        Route::match(['POST','GET'],'/', 'index')->name('services.index');
        Route::match(['POST','GET'],'/add-service', 'addService')->name('service.add');
        Route::match(['POST','GET'],'/subservice/{id?}', 'addSubService')->name('services.subService.add');
        Route::match(['POST','GET'],'/changestatus/{id?}','serviceStatus')->name('service.status');
        Route::post('/deleterepeater', 'deleteRepeaterSubserv')->name('subservice.deleterepeater');
        Route::post('/serviceStages', 'serviceStages')->name('serviceStages');

    });
    
    
});

