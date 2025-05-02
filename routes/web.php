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
use Illuminate\Support\Facades\Artisan;

Route::get('clean', function(){
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');

    return 'cleaned';
});

Route::get('migrate', function(){
    Artisan::call('migrate');
    return response()->json([
        'migrated'
    ]);
});


Route::get('seed/{seeder}', function($seeder){
    Artisan::call("db:seed --class=$seeder");
    return response()->json([
        'seed completed'
    ]);
});


//Users  Routes
Route::match(['get','post'],'/', [UsersController::class,'login'])->name('login');
Route::match(['get','post'],'forget_password', [UsersController::class,'forgetPassword'])->name('forgetPassword');
Route::get('reset/password/{token?}',[UsersController::class, 'resetPassword'])->name('resetPassword.resetPassword');
Route::post('password/reset',[UsersController::class, 'passwordReset'])->name('passwordReset.passwordReset');
Route::get('send_nontification',[TasksController::class, 'sendNotification'])->name('send-notification');
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

        Route::match(['POST','GET'],'/sub-admin', 'subAdmin')->name('subAdmin.listing');
        Route::match(['POST','GET'], '/add-sub-admin/{id?}', 'addSubAdmin')->name('subAdmin.addAdmin'); 

        Route::get('/delete/{id?}', 'deleteUser')->name('users.delete');
        Route::post('/deleterepeater', 'deleteRepeaterUser')->name('users.deleterepeater');
        Route::match(['POST','GET'], '/client', 'clients')->name('client.listing');
        Route::match(['POST','GET'], '/add-client/{id?}', 'addClient')->name('users.addclient');
        Route::match(['POST','GET'], '/associates', 'associates')->name('associate.listing');
        Route::match(['POST','GET'], '/add-associate/{id?}', 'addAssociate')->name('users.addassociate');

        Route::match(['POST', 'GET'], '/user-professions', 'userProfessions')->name('professions.index');
        Route::match(['POST', 'GET'], '/add-professions/{id?}', 'addProfessions')->name('professions.add');
        Route::get('/profession-status/{id}','professionStatus')->name('professions.status');

        Route::match(['POST', 'GET'], '/user-incorporation', 'userIncorporation')->name('incorporation.index');
        Route::match(['POST', 'GET'], '/add-incorporation/{id?}', 'addIncorporation')->name('incorporation.add');
        Route::match(['POST','GET'],'/incorporation-status/{id?}','incorporationStatus')->name('incorporations.status');

        Route::match(['POST', 'GET'], '/user-referral', 'userReferral')->name('referral.index');
        Route::match(['POST', 'GET'], '/add-referral/{id?}', 'addReferral')->name('referral.add');
        Route::get('referral-status/{id}','referralStatus')->name('referral.status');

        Route::match(['POST', 'GET'], '/user-partners', 'userPartner')->name('partner.index');
        Route::match(['POST', 'GET'], '/add-partner/{id?}', 'addPartner')->name('partner.add');
        Route::get('/partner-status/{id}','partnerStatus')->name('partner.status');

        // Route::get('/category-status/{id?}', 'categoryStatus')->name('users.category.status');
        // Route::get('/category-delete/{id?}', 'categoryDelete')->name('users.category.delete');

        Route::get('/panel-logs', 'panelLogs')->name('logs.index');
        Route::get('/get/action/logs' ,'panelLogs')->name('getActionLog.log');
        Route::get('/view-logs', 'viewLogs')->name('logs.view');
        Route::get('/userstatus','userStatus')->name('users.status');
        Route::get('/clientstatus','clientStatus')->name('client.status');
        Route::get('/associatestatus','associateStatus')->name('associate.status');

        Route::post('/checkduplicate','checkDuplicate')->name('user.checkDuplicate');
    });
    //Settings Routes
    Route::prefix('settings')->controller(SettingsController::class)->group(function () {
        Route::get('/roles', 'roles')->name('settings.roles');
        Route::match(['POST','GET'],'/add-role/{id?}', 'addRole')->name('settings.addrole');
        Route::get('assign-menu','viewMenu')->name('setting.viewMenu');
        Route::post('get-menu','getMenu')->name('setting.getMenu');
        Route::match(['POST', 'GET'], 'scope-of-business' , 'scopeOfBusiness')->name('setting.scopeOfBusiness');
        Route::Post('scope-of-business/add' , 'scopeOfBusinessAdd')->name('setting.scopeOfBusinessAdd');
        Route::Post('scope-of-business/update' , 'scopeOfBusinessUpdate')->name('setting.scopeOfBusinessUpdate');
        Route::match(['POST','GET'],'scope-of-business-status/{id?}' , 'scopeOfBusinessStatus')->name('setting.scopeOfBusinessStatus');
        

     


         //stages controller
        Route::prefix('stages')->controller(StagesController::class)->group(function (){
          Route::get('/', 'index')->name('stages.index');
          Route::post('/create', 'create')->name('stages.create');
        });
    });
    //Leads Routes
    Route::prefix('leads')->controller(LeadsController::class)->group(function () {
        Route::match(['POST','GET'],'/', 'index')->name('leads.index');
        Route::match(['POST','GET'],'/add/{id?}', 'add')->name('leads.add');
        Route::post('/edit/{id?}','edit')->name('leads.edit');
        Route::match(['POST','GET'],'/fetch/{id?}','leadFetch')->name('leads.fetch');
        Route::get('/sendquote', 'sendquote')->name('leads.quote');
        Route::get('lead-logs/{id?}', 'leadLogs')->name('leadLogs.index');
        Route::post('/get-logs', 'getLogs')->name('leads.getLogs');
        Route::POST('/getsubservice','getSubService')->name('lead.subservice');
        Route::match(['POST','GET'],'/getsourcetypename','getSourceTypeName')->name('lead.getsourcetypename');
        Route::post('/deleterepeater', 'deleteRepeaterLead')->name('lead.deleterepeater');
        Route::post('/deleteattchmentrepeater', 'deleteAttachmentRepeaterLead')->name('lead.deleteattachmentrepeater');
        Route::match(['POST','GET'],'/archive/{id?}', 'archiveLead')->name('leads.archive');
        Route::POST('/setassign','setAssignToUser')->name('leads.assign');
        Route::post('/checkduplicateemail','checkEmailDuplicate')->name('lead.checkDuplicateEmail'); 
        Route::match(['POST', 'GET'], '/firm', 'leadFirm')->name('firm.index');
        Route::match(['POST', 'GET'], '/add-firm/{id?}', 'addLeadFirm')->name('firm.add');
        Route::get('/firm-status/{id}','firmStatus')->name('firm.status');
        Route::get('/invoice/{id?}','leadInvoice')->name('lead.invoice');
        // check existed client
        Route::post('/client','existedClientDetail')->name('lead.existedClientDetail');
        Route::get('/payment', 'paymentStatus')->name('lead.paymentStatus');
        Route::Post('/payment-details', 'paymentDetails')->name('lead.paymentDetails');
        Route::get('/opposition-details', 'oppositionDetails')->name('lead.oppositionDetails');

        Route::get('/download-invoice/{id?}', 'downloadInvoice')->name('lead.downloadinvoice');

    });
    //Tasks Routes
    Route::prefix('tasks')->controller(TasksController::class)->group(function () {
        Route::match(['POST','GET'],'/negotiate-price/{id}', 'negotiatePrice')->name('task.negotiatePrice');
        Route::Post('get-service', 'getServiceAcctoLead')->name('task.getServiceAcctoLead');
        Route::Post('get-sub-service', 'getSubServiceAccToService')->name('task.getSubServiceAccToService');
        Route::Post('get-apply-for', 'getAppliedFor')->name('task.getAppliedFor');
        Route::get('/{request_type?}', 'index')->name('task.index');
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
        Route::Post('unhold','unHoldTask')->name('task.unHoldTask'); // not in middlerware
        Route::Post('hold-task','holdtask')->name('task.hold'); // not in middlerware

        Route::Post('reject-task','rejecttask')->name('task.reject'); // not in middlerware

        Route::get('document-draft/{id}', "DocumentDraft")->name('task.DocumentDraft');// not in middlerware
        Route::post('document-draft/status/{id}', "DocumentDraftStatus")->name('task.DocumentDraftStatus');// not in middlerware
        Route::get('client-approval/{id}','clientApproval')->name('task.clientApproval');
        Route::post('client-approval/status/{id}','clientApprovalStatus')->name('task.clientApprovalStatus');
        Route::get('draft-application/{id}','draftApplication')->name('task.draftApplication');
        Route::post('draft-application/status/{id}','draftApplicationStatus')->name('task.draftApplicationStatus');
        Route::get('formality-check/{id}','formalityCheck')->name('task.formalityCheck');
        Route::post('formality-check/status/{id}','formalityCheckStatus')->name('task.formalityCheckStatus'); // here midller ware stop
        Route::get('initial-examination/{id}','initialExamination')->name('task.initialExamination');
        Route::post('initial-examination/status/{id}','initialExaminationStatus')->name('task.initialExaminationStatus');
        Route::get('government-portal/{id}','replyAdded')->name('task.replyAdded');
        Route::post('government-portal/status/{id}','replyAddedStatus')->name('task.replyAddedStatus');
        Route::get('government-portal/reply/{id}','govtPortalReply')->name('task.govtPortalReply');
        Route::Post('government-portal/reply/status/{id}','govtPortalReplyStatus')->name('task.govtPortalReplyStatus');
        Route::get('examintion/inform-client/{id}' , 'examinationInform')->name('task.examinationInform');
        Route::Post('examintion/inform-client/status/{id}' , 'examinationInformStatus')->name('task.examinationInformStatus');
        Route::get('examination/payment/{id}' , 'examinationPayment')->name('task.examinationPayment');
        Route::Post('examination/payment/status/{id}' , 'examinationPaymentStatus')->name('task.examinationPaymentStatus');
        Route::get('hearing-date/{id}','hearingDate')->name('task.hearingDate');
        Route::Post('hearing-date/status/{id}','hearingDateStatus')->name('task.hearingDateStatus');
        Route::get('show-case-hearing/{id}','showCaseHearing')->name('task.showCaseHearing');
        Route::Post('show-case-hearing/status/{id}','showCaseHearingStatus')->name('task.showCaseHearingStatus');
        Route::get('mark-publish/{id}','markAsPublish')->name('task.markAsPublish');
        Route::post('mark-publish/status/{id}','markAsPublishStatus')->name('task.markAsPublishStatus');
        Route::get('opposition/mark-publish/{id}','markPublishOpposition')->name('task.markPublishOpposition');
        Route::Post('opposition/mark-publish/status{id}','markPublishOppositionStatus')->name('task.markPublishOppositionStatus');
        Route::get('opposition/inform-client/{id}','informClientAfterPublish')->name('task.informClientAfterPublish');
        Route::Post('opposition/inform-client/status{id}','informClientAfterPublishStatus')->name('task.informClientAfterPublishStatus');
        Route::get('opposition/payment/{id}','oppositionPayment')->name('task.oppositionPayment');
        Route::Post('opposition/payment/status/{id}', 'oppositionPaymentStatus')->name('task.oppositionPaymentStatus');
        Route::get('opposition/counter-statement/{id}', 'oppositionCounterStatement')->name('task.oppositionCounterStatement');
        Route::Post('opposition/counter-statement/status/{id}', 'oppositionCounterStatementStatus')->name('task.oppositionCounterStatementStatus');
        Route::get('opposition/evidence-submission/{id}', 'opponentEvidenceSubmission')->name('task.opponentEvidenceSubmission');
        Route::Post('opposition/evidence-submission/status/{id}', 'opponentEvidenceSubmissionStatus')->name('task.opponentEvidenceSubmissionStatus');
        Route::get('applicant/evidence-submission/{id}', 'applicantEvidenceSubmission')->name('task.applicantEvidenceSubmission');
        Route::Post('applicant/evidence-submission/status/{id}', 'applicantEvidenceSubmissionStatus')->name('task.applicantEvidenceSubmissionStatus');//
        // Route::Post('opposition/hearing/{id}', 'oppositionHearing')->name('task.oppositionHearing');
        Route::get('/opposition-notice/received {id?}','oppositionNoticeDate')->name('task.oppositionNoticeDate');
        Route::Post('/opposition-notice/received /status{id?}','oppositionNoticeDateStatus')->name('task.oppositionNoticeDateStatus');
        Route::get('/notice-sent/{id?}','noticeSent')->name('task.noticeSent');
        Route::Post('/notice-sent/Status/{id?}','noticeSentStatus')->name('task.noticeSentStatus');
        Route::get('/opposition/re-submission/evidence/{id?}','oppositionResubmissionEvedince')->name('task.oppositionResubmissionEvedince');
        Route::Post('/opposition/re-submission/evidence/status/{id?}','oppositionResubmissionEvidenceStatus')->name('task.oppositionResubmissionEvidenceStatus');
        Route::get('/non-Compliance/{id?}','nonCompliance')->name('task.nonCompliance');
        Route::Post('/noncompliance/status/{id?}','nonComplianceStatus')->name('task.nonComplianceStatus');
        Route::get('/interlocutory_petition/{id?}','interlocuteryOpposition')->name('task.interlocuteryOpposition');
        Route::Post('/interlocutory_petition/status/{id?}','interlocuteryOppositionStatus')->name('task.interlocuteryOppositionStatus');
        Route::get('/opposition/hearing/{id?}','oppositionHearingDate')->name('task.oppositionHearingDate');
        Route::Post('/opposition/hearing/Status{id?}','oppositionHearingDateStatus')->name('task.oppositionHearingDateStatus');
        Route::get('/client-approval/opposition/hearing/{id?}','clientApprovalOnHearing')->name('task.clientApprovalOnHearing');
        Route::Post('/client-approval/opposition/hearing/status/{id?}','clientApprovalOnHearingStatus')->name('task.clientApprovalOnHearingStatus');
        Route::get('/client-inform/hearing/{id?}','clientInformForHearingCharge')->name('task.clientInformForHearingCharge');
        Route::Post('/client-inform/hearing/status{id?}','clientInformForHearingChargeStatus')->name('task.clientInformForHearingChargeStatus');
        Route::get('/hearing/charge/{id?}','hearingChargeUpdate')->name('task.hearingChargeUpdate');
        Route::Post('/hearing/charge/status{id?}','hearingChargeStatus')->name('task.hearingChargeStatus');
        Route::get('/hearing/{id?}','hearing')->name('task.hearing');
        Route::Post('/hearing/status/{id?}','hearingStatus')->name('task.hearingStatus');
        Route::get('/agreement/submission/{id?}','agreementSubmission')->name('task.agreementSubmission');
        Route::get('/trademark/{id?}','tradeMark')->name('task.tradeMark');
        Route::Post('/trademark/status/{id?}','trademarkStatus')->name('task.trademarkStatus');
        Route::get('/trademark/post-registration/{id?}','postRegistration')->name('task.postRegistration');
        Route::Post('/trademark/post-registration/status/{id?}','postRegistrationStatus')->name('task.postRegistrationStatus');
        Route::get('/rectification/{id?}','rectification')->name('task.rectification');// 2
        Route::Post('/rectification/status/{id?}','rectificationStatus')->name('task.rectificationStatus');
        Route::get('/rectification/inform-client/{id?}' , 'informClientRectification')->name('task.informClientRectification');
        Route::Post('/rectification/inform-client/status/{id?}' , 'informClientRectificationStatus')->name('task.informClientRectificationStatus');
        Route::get('/rectification/payment-confirmation/{id?}' , 'paymentOnRectification')->name('task.paymentOnRectification');
        Route::Post('/rectification/payment-confirmation/status{id?}' , 'paymentOnRectificationStatus')->name('task.paymentOnRectificationStatus');
        Route::get('/rectification/notice-recevied/{id?}' , 'rectificationNoticeReceived')->name('task.rectificationNoticeReceived');
        Route::Post('/rectification/notice-recevied/status{id?}' , 'rectificationNoticeReceivedStatus')->name('task.rectificationNoticeReceivedStatus');
        Route::get('/rectification/counter-statement/{id?}' , 'rectificationCounterStatement')->name('task.rectificationCounterStatement');
        Route::Post('/rectification/counter-statement/status{id?}' , 'rectificationCounterStatementStatus')->name('task.rectificationCounterStatementStatus');
        Route::get('/rectification/notice-sent/{id?}' , 'rectificationNoticeSent')->name('task.rectificationNoticeSent');
        Route::Post('/rectification/notice-sent/{id?}' , 'rectificationNoticeSentStatus')->name('task.rectificationNoticeSentStatus');
        Route::get('/rectification/opponent/evidence-submission/{id?}' , 'rectificationOpponentEvidenceSubmission')->name('task.rectificationOpponentEvidenceSubmission');
        Route::Post('/rectification/opponent/evidemce-submission/status/{id?}' , 'rectificationOpponentEvidenceSubmissionStatus')->name('task.rectificationOpponentEvidenceSubmissionStatus');
        Route::get('/rectification/apllicant/evidence-submission/{id?}' , 'rectifiactionApplicationEvidenceSubmission')->name('task.rectifiactionApplicationEvidenceSubmission');
        Route::Post('/rectification/apllicant/evidence-submission/status/{id?}' , 'rectifiactionApplicationEvidenceSubmissionStatus')->name('task.rectifiactionApplicationEvidenceSubmissionStatus');
        Route::get('/rectification/opponent/evidence/re-submission/{id?}' , 'rectificationOpponentResubmisson')->name('task.rectificationOpponentResubmisson');
        Route::Post('/rectification/opponent/evidence/re-submission/status/{id?}' , 'rectificationOpponentResubmissonStatus')->name('task.rectificationOpponentResubmissonStatus');
        Route::get('/rectification-hearing/{id?}' , 'rectificationHearing')->name('task.rectificationHearing');
        Route::Post('/rectification-hearing/status{id?}' , 'rectificationHearingStatus')->name('task.rectificationHearingStatus');
        Route::get('/rectification/client-approval/{id?}' , 'clientApprovalOnHearingRectification')->name('task.clientApprovalOnHearingRectification');
        Route::Post('/rectification/client-approval/status{id?}' , 'clientApprovalOnHearingRectificationStatus')->name('task.clientApprovalOnHearingRectificationStatus');
        Route::get('/rectification/proforma-invoice/{id?}' , 'clientInformExtraChargeOnHearingRectification')->name('task.clientInformExtraChargeOnHearingRectification');
        Route::Post('/rectification/proforma-invoice/status{id?}' , 'clientInformExtraChargeOnHearingRectificationStatus')->name('task.clientInformExtraChargeOnHearingRectificationStatus');
        Route::get('/rectification/hearing-charge/{id?}' , 'rectificationClientHearingCharge')->name('task.rectificationClientHearingCharge');
        Route::Post('/rectification/hearing-charge/status/{id?}' , 'rectificationClientHearingChargeStatus')->name('task.rectificationClientHearingChargeStatus');
        Route::get('/rectification/hearing/{id?}' , 'rectificationUpdateHearing')->name('task.rectificationUpdateHearing');
        Route::Post('/rectification/hearing/status/{id?}' , 'rectificationUpdateHearingStatus')->name('task.rectificationUpdateHearingStatus');
        Route::get('/rectification/written-submission/{id?}' , 'rectficationWrittenSubmission')->name('task.rectficationWrittenSubmission');
        Route::Post('/rectification/written-submission/status/{id?}' , 'rectficationWrittenSubmissionStatus')->name('task.rectficationWrittenSubmissionStatus');
        Route::get('/rectification/trademark/{id?}' , 'rectificationTrademark')->name('task.rectificationTrademark');
        Route::Post('/rectification/trademark-status/{id?}' , 'rectificationTrademarkStatus')->name('task.rectificationTrademarkStatus');//3
        Route::get('/trademark/renewal/{id?}' , 'trademarkRenewal')->name('task.trademarkRenewal');
        Route::Post('/trademark/renewal/status/{id?}' , 'trademarkRenewalStatus')->name('task.trademarkRenewalStatus');
        Route::get('/trademark/renewal/client-approval/{id?}' , 'clientApprovalOnRenewal')->name('task.clientApprovalOnRenewal');
        Route::Post('/trademark/renewal/client-approval/status/{id?}' , 'clientApprovalOnRenewalStatus')->name('task.clientApprovalOnRenewalStatus');
        Route::get('/trademark/renewal/payment/{id?}' , 'renewalPaymentConfirmation')->name('task.renewalPaymentConfirmation');
        Route::Post('/trademark/renewal/payment/status/{id?}' , 'renewalPaymentConfirmationStatus')->name('task.renewalPaymentConfirmationStatus');
        Route::get('/trademark/renewal/filed/{id?}' , 'renewalFiled')->name('task.renewalFiled');
        Route::Post('/trademark/renewal/filed/status/{id?}' , 'renewalFiledStatus')->name('task.renewalFiledStatus');
        Route::get('/trademark/renewal/inform-client/{id?}' , 'informClientRenwalApproved')->name('task.informClientRenwalApproved');
        Route::Post('/trademark/renewal/inform-client/status/{id?}' , 'informClientRenwalApprovedStatus')->name('task.informClientRenwalApprovedStatus');
        Route::get('/trademark/change-address/{id?}' , 'changeAddress')->name('task.changeAddress');
        Route::Post('/trademark/change-address/status/{id?}' , 'changeAddressStatus')->name('task.changeAddressStatus');
        Route::get('/trademark/change-address/payment/{id?}' , 'changeAddressPayment')->name('task.changeAddressPayment');
        Route::Post('/trademark/change-address/payment/status/{id?}' , 'changeAddressPaymentStatus')->name('task.changeAddressPaymentStatus');
        Route::get('/trademark/change-address/query-form/{id?}' , 'changeAddressQueryForm')->name('task.changeAddressQueryForm');
        Route::Post('/trademark/change-address/query-form/status/{id?}' , 'changeAddressQueryFormStatus')->name('task.changeAddressQueryFormStatus');
        Route::get('/trademark/change-address/filing/{id?}' , 'changeAddressFiled')->name('task.changeAddressFiled');
        Route::Post('/trademark/change-address/filing/status/{id?}' , 'changeAddressFiledStatus')->name('task.changeAddressFiledStatus');
        Route::get('/trademark/change-address/inform-client/{id?}' , 'addressChanged')->name('task.addressChanged');
        Route::Post('/trademark//inform-client/status/{id?}' , 'addressChangedStatus')->name('task.addressChangedStatus');
        Route::get('/trademark/assignment/{id?}' , 'assignmentUserRegsiter')->name('task.assignmentUserRegsiter');
        Route::Post('/trademark/assignment/status/{id?}' , 'assignmentUserRegsiterStatus')->name('task.assignmentUserRegsiterStatus');
        Route::get('/trademark/assignment/payment/{id?}' , 'assignmentUserRegsiterPayment')->name('task.assignmentUserRegsiterPayment');
        Route::Post('/trademark/assignment/payment/status/{id?}' , 'assignmentUserRegsiterPaymentStatus')->name('task.assignmentUserRegsiterPaymentStatus');
        Route::get('/trademark/assignment/query-form/{id?}' , 'assignmentQueryForm')->name('task.assignmentQueryForm');
        Route::Post('/trademark/assignment/query-form/status{id?}' , 'assignmentQueryFormStatus')->name('task.assignmentQueryFormStatus');
        Route::get('/trademark/assignment/affidavit-process/status/{id?}' , 'assignmentAffidavit')->name('task.assignmentAffidavit');
        Route::Post('/trademark/assignment/affidavit-process/status{id?}' , 'assignmentAffidavitStatus')->name('task.assignmentAffidavitStatus');
        Route::get('/trademark/assignment/file-process/{id?}' , 'assignmentFileProcess')->name('task.assignmentFileProcess');
        Route::Post('/trademark/assignment/file-process/status/{id?}' , 'assignmentFileProcessStatus')->name('task.assignmentFileProcessStatus');
        Route::get('/trademark/assignment/update-client/{id?}' , 'assignmentUpdateClient')->name('task.assignmentUpdateClient');
        Route::Post('/trademark/assignment/update-client/status/{id?}' , 'assignmentUpdateClientStatus')->name('task.assignmentUpdateClientStatus');
        Route::get('/trademark/assignment/inform-client/{id?}' , 'assignmentIntimateClient')->name('task.assignmentIntimateClient');
        Route::Post('/trademark/assignment/inform-client/status/{id?}' , 'assignmentIntimateClientStatus')->name('task.assignmentIntimateClientStatus');// 4
        Route::get('/trademark/expidet-process/{id?}' , 'expidetProcess')->name('task.expidetProcess');
        Route::Post('/trademark/expidet-process/status/{id?}' , 'expidetProcessStatus')->name('task.expidetProcessStatus');
        Route::get('/trademark/expidet-process/payment/{id?}' , 'expidetProcessPayment')->name('task.expidetProcessPayment');
        Route::Post('/trademark/expidet-process/payment/satus/{id?}' , 'expidetProcessPaymentStatus')->name('task.expidetProcessPaymentStatus');
        Route::get('/trademark/expidet-process/file-process/{id?}' , 'expidetProcessFile')->name('task.expidetProcessFile');
        Route::Post('/trademark/expidet-process/file-process/status/{id?}' , 'expidetProcessFileStatus')->name('task.expidetProcessFileStatus');
        Route::get('/trademark/expidet-process/inform-client/{id?}' , 'expidetProcessFileInformClient')->name('task.expidetProcessFileInformClient');
        Route::Post('/trademark/expidet-process/inform-client/status/{id?}' , 'expidetProcessFileInformClientStatus')->name('task.expidetProcessFileInformClientStatus');
        Route::get('/trademark/ip-watch/{id?}' , 'ipWatch')->name('task.ipWatch');
        Route::post('/trademark/ip-watch/status/{id?}' , 'ipWatchStatus')->name('task.ipWatchStatus');
        Route::get('/trademark/ip-watch/payment/{id?}' , 'ipWatchPayment')->name('task.ipWatchPayment');
        Route::Post('/trademar/ip-watch/payment/status/{id?}' , 'ipWatchPaymentStatus')->name('task.ipWatchPaymentStatus');
        Route::get('/trademark/ip-watch/frequency/{id?}' , 'ipWatchFrequency')->name('task.ipWatchFrequency');
        Route::Post('/trademark/ip-watch/frequency/status/{id?}' , 'ipWatchFrequencyStatus')->name('task.ipWatchFrequencyStatus');
        Route::get('/rectification/non-complianc/{id?}' , 'rectificationNonCompliance')->name('task.rectificationNonCompliance');
        Route::Post('/rectification/non-complianc/status/{id?}' , 'rectificationNonComplianceStatus')->name('task.rectificationNonComplianceStatus');
        Route::get('/rectification/interlocutory-petition/{id?}' , 'rectificationInterlocutory')->name('task.rectificationInterlocutory');
        Route::Post('/rectification/interlocutory-petition/status/{id?}' , 'rectificationInterlocutoryStatus')->name('task.rectificationInterlocutoryStatus');







        
        



        // For patent..........     
        // For payment verification.........
        Route::get('/patent/send-quotation/{id?}','patentSendQuotation')->name('task.patentSendQuotation');
        Route::get('/patent/payment-verification/{id?}','patentPaymentVerification')->name('task.patentPaymentVerification');
        Route::get('/patent/prior-art/{id?}','patentPriorArt')->name('task.patentPriorArt');
        Route::post('/patent/submit-prior-art/{id?}','patentSubmitPriorArt')->name('task.patentSubmitPriorArt');
        Route::get('/patent/documentation/{id?}','patentDocumentation')->name('task.patentDocumentation');
        Route::get('/patent/draft/{id?}','patentDraft')->name('task.patentDraft');
        Route::post('/patent/submit-draft/{id?}','patentSubmitDraft')->name('task.patentSubmitDraft');
        Route::get('/patent/client-approval/{id?}','patentclientapproval')->name('task.patentClientApproval');
        Route::post('/patent/submit-client-approval/{id?}','patentSubmitClientApproval')->name('task.patentSubmitClientApproval');
        Route::get('/patent/approval/{id?}','clentApprovalOnPatent')->name('task.clentApprovalOnPatent');
        Route::Post('/patent/approval/status{id?}','clentApprovalOnPatentStatus')->name('task.clentApprovalOnPatentStatus');
        Route::get('/patent/filing/{id?}','patentfilingProccess')->name('task.patentfilingProccess');
        Route::Post('/patent/filing/status{id?}','patentfilingProccessStatus')->name('task.patentfilingProccessStatus');
        Route::get('/patent/complete-specification/{id?}','patentCompleteSpecification')->name('task.patentCompleteSpecification');
        Route::Post('/patent/complete-specification-submit{id?}','patentCompletedSpecificationSubmit')->name('task.patentCompletedSpecificationSubmit');
        Route::get('/patent/form-9/{id?}','patentForm9')->name('task.patentForm9');
        Route::Post('/patent/form-9-submit/{id?}','patentForm9Submit')->name('task.patentForm9Submit');
        Route::get('/patent/early-publication/{id?}','patentEarlyPublication')->name('task.patentearlypublication');
        Route::Post('/patent/early-publication-submit/{id?}','patentEarlyPublicationSubmit')->name('task.patentearlypublicationSubmit');
        Route::get('/patent/standard-publication/{id?}','patentStandardPublication')->name('task.patentStandardpublication');
        Route::Post('/patent/standard-publication-submit/{id?}','patentStandardPublicationSubmit')->name('task.patentStandardpublicationSubmit');
        Route::get('/patent/form-18/{id?}','patentForm18')->name('task.patentForm18');
        Route::Post('/patent/form-18-submit/{id?}','patentForm18Submit')->name('task.patentForm18Submit');
        Route::get('/patent/first-examination-report/{id?}','patentFER')->name('task.patentFER');
        Route::Post('/patent/first-examination-report-submit/{id?}','patentFERSubmit')->name('task.patentFERSubmit');
        Route::get('/patent/second-examination-report/{id?}','patentSER')->name('task.patentSER');
        Route::Post('/patent/second-examination-report-submit/{id?}','patentSERSubmit')->name('task.patentSERSubmit');

        Route::get('/patent/hearing-send-quotation/{id?}','patentHearingSendQuotation')->name('task.patentHearingSendQuotation');
        Route::get('/patent/hearing-payment-verification/{id?}','patentHearingPaymentVerification')->name('task.patentHearingPaymentVerification');
        Route::get('/patent/await-hearing-date/{id?}','patentawaitHearingDate')->name('task.patentawaitHearingDate');
        Route::Post('/patent/await-hearing-date-submit/{id?}','patentawaitHearingDateSubmit')->name('task.patentawaitHearingDateSubmit');

        Route::get('/patent/hearing/{id?}','patentHearing')->name('task.patentHearing');
        Route::Post('/patent/hearing-submit/{id?}','patentHearingSubmit')->name('task.patentHearingSubmit');
        Route::get('/patent/pre-grant-opposition/{id?}','patentPreGrantOpposition')->name('task.patentPreGrantOpposition');
        Route::Post('/patent/pre-grant-opposition-submit/{id?}','patentPreGrantOppositionSubmit')->name('task.patentPreGrantOppositionSubmit');

        Route::get('/patent/opposition-send-quotation/{id?}','patentoppositionSendQuotation')->name('task.patentoppositionSendQuotation');
        Route::get('/patent/opposition-payment-verification/{id?}','patentoppositionPaymentVerification')->name('task.patentoppositionPaymentVerification');

        Route::get('/patent/opposition-notice-date/{id?}','patentOppositionNoticeDate')->name('task.patentoppositionNoticeDate');
        Route::Post('/patent/opposition-notice-date-submit/{id?}','patentOppositionNoticeDateSubmit')->name('task.patentoppositionNoticeDateSubmit');

        Route::get('/patent/opposition-counter-statement/{id?}','patentOppositionCounterStatement')->name('task.patentoppositionCounterStatement');
        Route::Post('/patent/opposition-counter-statement-submit/{id?}','patentOppositionCounterStatementSubmit')->name('task.patentoppositionCounterStatementSubmit');
        
        Route::get('/patent/opposition-non-compilance/{id?}','patentOppositionNonCompliance')->name('task.patentoppositionNonCompliance');
        Route::Post('/patent/opposition-non-compilance-submit/{id?}','patentOppositionNonComplianceSubmit')->name('task.patentoppositionNonComplianceSubmit');

        Route::get('/patent/opposition-awaiting-hearing/{id?}','patentOppositionAwaitingHearing')->name('task.patentoppositionAwaitingHearing');
        Route::Post('/patent/opposition-awaiting-hearing-submit/{id?}','patentOppositionAwaitingHearingSubmit')->name('task.patentoppositionAwaitingHearingSubmit');

        Route::get('/patent/opposition-client-confirmation/{id?}','patentOppositionClientConfirmation')->name('task.patentoppositionClientConfirmation');

        Route::get('/patent/opposition-client-payment-verification/{id?}','patentOppositionClientPaymentVerification')->name('task.patentoppositionClientPaymentVerification');

        Route::get('/patent/opposition-hearing/{id?}','patentOppositionHearing')->name('task.patentoppositionHearing');
        Route::Post('/patent/opposition-hearing-submit/{id?}','patentOppositionHearingSubmit')->name('task.patentoppositionHearingSubmit');

        Route::get('/patent/opposition-written-submission/{id?}','patentOppositionWrittenSubmission')->name('task.patentoppositionWrittenSubmission');
        Route::Post('/patent/opposition-written-submission-submit/{id?}','patentOppositionWrittenSubmissionSubmit')->name('task.patentoppositionWrittenSubmissionSubmit');

        Route::get('/patent/opposition-registry/{id?}','patentOppositionRegistry')->name('task.patentoppositionRegistry');
        Route::Post('/patent/opposition-registry-submit/{id?}','patentOppositionRegistrySubmit')->name('task.patentoppositionRegistrySubmit');

        Route::get('/patent/opposition-rule45/{id?}','patentOppositionRule45')->name('task.patentoppositionEvidencerule45');
        Route::Post('/patent/opposition-rule45-submit/{id?}','patentOppositionRule45Submit')->name('task.patentoppositionEvidencerule45Submit');

        Route::get('/patent/opposition-rule46/{id?}','patentOppositionRule46')->name('task.patentoppositionEvidencerule46');
        Route::Post('/patent/opposition-rule46-submit/{id?}','patentOppositionRule46Submit')->name('task.patentoppositionEvidencerule46Submit');
        
        Route::get('/patent/opposition-rule47/{id?}','patentOppositionRule47')->name('task.patentoppositionEvidencerule47');
        Route::Post('/patent/opposition-rule47-submit/{id?}','patentOppositionRule47Submit')->name('task.patentoppositionEvidencerule47Submit');

        Route::get('/patent/registered/{id?}','patentregistered')->name('task.patentStatus');
        Route::Post('/patent/registered-submit/{id?}','patentregisteredSubmit')->name('task.patentStatusSubmit');

        Route::get('/patent/post-registration-status/{id?}','patentPostRegistrationAction')->name('task.patentPostRegistrationAction');
        Route::Post('/patent/post-registration-status-submit/{id?}','patentPostRegistrationActionSubmit')->name('task.patentPostRegistrationActionSubmit');

        Route::get('/patent/interlocatory-petition/{id?}','patentInterlocatoryPetitionCounter')->name('task.patentInterlocatoryPetitionCounter');
        Route::Post('/patent/interlocatory-petition-submit/{id?}','patentInterlocatoryPetitionCounterSubmit')->name('task.patentInterlocatoryPetitionCounterSubmit');

        Route::get('/patent/counter-statement-awaiting-hearing/{id?}','patentCounterStatementAwaitingHearing')->name('task.patentCounterStatementAwaitingHearing');
        Route::Post('/patent/counter-statement-awaiting-hearing-submit/{id?}','patentCounterStatementAwaitingHearingSubmit')->name('task.patentCounterStatementAwaitingHearingSubmit');

        Route::get('/patent/counter-statement-client-confirmation/{id?}','patentcounterStatementClientConfirmation')->name('task.patentCounterStatementClientConfirmation');

        Route::get('/patent/counter-statement-client-payment-verification/{id?}','patentcounterStatementClientPaymentVerification')->name('task.patentCounterStatementClientPaymentVerification');

        Route::get('/patent/counter-statement-hearing/{id?}','patentCounterStatementHearing')->name('task.patentCounterStatementHearing');
        Route::Post('/patent/counter-statement-hearing-submit/{id?}','patentCounterStatementHearingSubmit')->name('task.patentCounterStatementHearingSubmit');


        // Post Grant Register
        
        Route::get('/patent/post-grant-opposition/{id?}','patentPostGrantOpposition')->name('task.patentPostGrantOpposition');
        Route::Post('/patent/post-grant-opposition-submit/{id?}','patentPostGrantOppositionSubmit')->name('task.patentPostGrantOppositionSubmit');

        Route::get('/patent/registered-post-registered/{id?}','patentregisteredPostRegistered')->name('task.patentStatusPostRegister');
        Route::Post('/patent/registered-post-registered-submit/{id?}','patentregisteredPostRegisteredSubmit')->name('task.patentStatusPostRegisterSubmit');

        
        Route::get('/patent/client-intimation-post-register/{id?}','patentClientIntimationPostRegistered')->name('task.patentClientIntimationPostRegister');
        Route::Post('/patent/client-intimation-post-register-submit/{id?}','patentClientIntimationPostRegisteredSubmit')->name('task.patentClientIntimationPostRegisterSubmit');
       
        Route::get('/patent/payment-confirmation-post-register/{id?}','patentPaymentConfirmationPostRegistered')->name('task.patentPaymentConfirmationPostRegister');
        Route::Post('/patent/payment-confirmation-post-register-submit/{id?}','patentPaymentConfirmationPostRegisteredSubmit')->name('task.patentPaymentConfirmationPostRegisterSubmit');
   
        Route::get('/patent/notice-date-post-register/{id?}','patentnoticeDatePostRegistered')->name('task.patentNoticeDatePostRegister');
        Route::Post('/patent/notice-date-post-register-submit/{id?}','patentnoticeDatePostRegisteredSubmit')->name('task.patentNoticeDatePostRegisterSubmit');
   
        Route::get('/patent/non-compilance-post-register/{id?}','patentOppositionNonCompliancePostRegister')->name('task.patentNonCompliancePostRegister');
        Route::Post('/patent/non-compilance-post-register-submit/{id?}','patentOppositionNonCompliancePostRegisterSubmit')->name('task.patentNonCompliancePostRegisterSubmit');
       
        Route::get('/patent/interlocatory-petition-post-register/{id?}','patentInterlocatoryPetitionPostRegister')->name('task.patentInterlocatoryPetitionPostRegistered');
        Route::Post('/patent/interlocatory-petition-post-register-submit/{id?}','patentInterlocatoryPetitionPostRegisterSubmit')->name('task.patentInterlocatoryPetitionPostRegisteredSubmit');
        
        Route::get('/patent/counter-statement-post-registration/{id?}','patentCounterStatementPostRegister')->name('task.patentCounterStatementPostRegister');
        Route::Post('/patent/opposition-counter-statement-post-registration-submit/{id?}','patentCounterStatementPostRegisterSubmit')->name('task.patentCounterStatementPostRegisterSubmit');
        
        Route::get('/patent/registry-post-register/{id?}','patentRegistryPostRegister')->name('task.patentPatentRegistryPostRegister');
        Route::Post('/patent/registry-post-register-submit/{id?}','patentRegistryPostRegisterSubmit')->name('task.patentPatentRegistryPostRegisterSubmit');
      
        Route::get('/patent/post-register-rule45/{id?}','patentRule45PostRegister')->name('task.patentOpponentEvidenceRule45PostRegistered');
        Route::Post('/patent/post-register-rule45-submit/{id?}','patentRule45PostRegisterSubmit')->name('task.patentOpponentEvidenceRule45PostRegisteredSubmit');

        Route::get('/patent/post-register-rule46/{id?}','patentRule46PostRegister')->name('task.patentApplicantEvidenceRule46PostRegistered');
        Route::Post('/patent/post-register-rule46-submit/{id?}','patentRule46PostRegisterSubmit')->name('task.patentApplicantEvidenceRule46PostRegisteredSubmit');
        
        Route::get('/patent/post-register-rule47/{id?}','patentRule47PostRegister')->name('task.patentOpponentEvidenceRule47PostRegistered');
        Route::Post('/patent/post-register-rule47-submit/{id?}','patentRule47PostRegisterSubmit')->name('task.patentOpponentEvidenceRule47PostRegisteredSubmit');
      
        Route::get('/patent/await-hearing-post-register/{id?}','patentawaitHearingPostRegister')->name('task.patentAwaitHearingPostRegistration');
        Route::Post('/patent/await-hearing-post-register-submit/{id?}','patentawaitHearingPostRegisterSubmit')->name('task.patentAwaitHearingPostRegistrationSubmit');
        
        Route::get('/patent/client-decision-post-register/{id?}','patentClientDecisionPostRegister')->name('task.patentClientDecisionPostRegistration');
        Route::post('/patent/client-decision-post-register-submit/{id?}','patentClientDecisionPostRegisterSubmit')->name('task.patentClientDecisionPostRegistrationSubmit');
        Route::get('/patent/assignment/{id?}','patentAssignmentRegisteredUser')->name('task.patentAssignmentRegisteredUser');
        Route::Post('/patent/assignment/status/{id?}','patentAssignmentRegisteredUserStatus')->name('task.patentAssignmentRegisteredUserStatus');
        Route::get('/patent/assignment/payment/{id?}','patentAssignmentPayemnt')->name('task.patentAssignmentPayemnt');
        Route::Post('/patent/assignment/payment/status{id?}','patentAssignmentPayemntStatus')->name('task.patentAssignmentPayemntStatus');
        Route::get('/patent/assignment/query-form/{id?}','patentAssignmentQueryForm')->name('task.patentAssignmentQueryForm');
        Route::Post('/patent/assignment/query-form/status/{id?}','patentAssignmentQueryFormStatus')->name('task.patentAssignmentQueryFormStatus');
        Route::get('/patent/assignment/affidavit/{id?}','patentAssignmentAffidavit')->name('task.patentAssignmentAffidavit');
        Route::Post('/patent/assignment/affidavit/status/{id?}','patentAssignmentAffidavitStatus')->name('task.patentAssignmentAffidavitStatus');
        Route::get('/patent/assignment/filing-process/{id?}','patentAssignmentFilingProcess')->name('task.patentAssignmentFilingProcess');
        Route::Post('/patent/assignment/filing-process/status/{id?}','patentAssignmentFilingProcessStatus')->name('task.patentAssignmentFilingProcessStatus');
        Route::get('/patent/assignment/client-update/{id?}','patentAssignmentUpdateClientDetails')->name('task.patentAssignmentUpdateClientDetails');
        Route::Post('/patent/assignment/client-update/status/{id?}','patentAssignmentUpdateClientDetailsStatus')->name('task.patentAssignmentUpdateClientDetailsStatus');
        Route::get('/patent/assignment/client-intimate/{id?}','patentAssignmentInitmateClient')->name('task.patentAssignmentInitmateClient');
        Route::Post('/patent/assignment/client-intimate/status/{id?}','patentAssignmentInitmateClientStatus')->name('task.patentAssignmentInitmateClientStatus');
        Route::get('/patent/address/{id?}','patentAddress')->name('task.patentAddress');
        Route::Post('/patent/address/status/{id?}','patentAddressStatus')->name('task.patentAddressStatus');
        Route::get('/patent/address/payment/{id?}','patentAddressPayment')->name('task.patentAddressPayment');
        Route::Post('/patent/address/payment/status/{id?}','patentAddressPaymentStatus')->name('task.patentAddressPaymentStatus');
        Route::get('/patent/address/query-form/{id?}','patentAddressQueryForm')->name('task.patentAddressQueryForm');
        Route::Post('/patent/address/query-form/status/{id?}','patentAddressQueryFormStatus')->name('task.patentAddressQueryFormStatus');
        Route::get('/patent/address/filing-process/{id?}','patentAddressFilingProcess')->name('task.patentAddressFilingProcess');
        Route::Post('/patent/address/filing-process/status/{id?}','patentAddressFilingProcessStatus')->name('task.patentAddressFilingProcessStatus');
        Route::get('/patent/address/client-intimate/{id?}','patentClientIntimationAddressChange')->name('task.patentClientIntimationAddressChange');
        Route::Post('/patent/address/client-intimate/status/{id?}','patentClientIntimationAddressChangeStatus')->name('task.patentClientIntimationAddressChangeStatus');
        
        

        


        
        


        


       
        Route::get('/patent/notify-extra-charge-post-register/{id?}','patentNotifyExtraChargePostRegister')->name('task.patentNotifyClientForExtraChargePostRegister');
        Route::post('patent/notify-extra-charge-post-register-submit/{id}','patentNotifyExtraChargePostRegisterSubmit')->name('task.patentNotifyClientForExtraChargePostRegisterSubmit');
       
        Route::get('/patent/hearing-charge-status-post-register/{id?}','patentHearingChargeStatusPostStatus')->name('task.patentUpdateHearingChargeStatusPostRegister');
        Route::post('/patent/hearing-charge-status-post-register/{id}','patentHearingChargeStatusPostStatusSubmit')->name('task.patentUpdateHearingChargeStatusPostRegisterSubmit');

        Route::get('/patent/hearing-post-register/{id?}','patentHearingPostRegister')->name('task.patentHearingPostRegister');
        Route::Post('/patent/hearing-post-register-submit/{id?}','patentHearingPostRegisterSubmit')->name('task.patentHearingPostRegisterSubmit');

        Route::get('/patent/written-submission-post-register/{id?}','patentWrittenSubmissionPostRegister')->name('task.patentWrittenSubmissionPostRegistered');
        Route::Post('/patent/written-submission-post-register-submit/{id?}','patentWrittenSubmissionPostRegisterSubmit')->name('task.patentWrittenSubmissionPostRegisteredSubmit');

        Route::get('/patent/renewal/{id?}' , 'patentRenewal')->name('task.patentRenewal');
        Route::Post('/patent/renewal-submit/{id?}' , 'patentRenewalSubmit')->name('task.patentRenewalSubmit');       

        Route::get('/patent/renewal-client-approval/{id?}' , 'patentRenewalPaymentConfirmation')->name('task.patentPaymentConfirmationRenewal');
        Route::Post('/patent/renewal-client-approval-submit/{id?}' , 'patentRenewalPaymentConfirmationSubmit')->name('task.patentPaymentConfirmationRenewalSubmit');
        
        Route::get('/patent/renewal-filed/{id?}' , 'patentRenewalFiled')->name('task.patentRenewalFiled');
        Route::Post('/patent/renewal-filed-submit/{id?}' , 'patentRenewalFiledSubmit')->name('task.patentRenewalFiledSubmit');
       
        Route::get('/patent/renewal-inform-client/{id?}' , 'informClientRenwalApprovedPostRegistration')->name('task.patentClientIntimationRenewal');
        Route::Post('/patent/renewal-inform-client-submit/{id?}' , 'informClientRenwalApprovedPostRegistrationSubmit')->name('task.patentClientIntimationRenewalSubmit');

     
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

