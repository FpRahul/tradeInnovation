<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use App\Models\RoleMenu;
use App\Models\MenuAction;

class CheckPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        //Ensure user is authenticated
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }
        $routeName = $request->route()->getName();
        $permissionDetails = [
            'status' => false,
            'menuId' => [1],
            'accessableRoutes' => [
                0 => 'user.logout', //default logout permission
                1 => 'user.myprofile', //default profile permission
                2 => 'chart.data', //default chat preview permission
                3 => 'dashboard', //default dashboard permission
                4 => 'serviceStages', //default permission to get stages according to selected service on load add form
                5 => 'lead.subservice',  //default permission to get sub service on lead add form
                6 => 'lead.getsourcetypename', //default permission to get source name
                7 => 'users.deleterepeater', //default permission to delete user experience
                8 => 'lead.deleterepeater', //default permission to delete service from lead add page
                9 => 'lead.deleteattachmentrepeater', //default permission to delete attachmanet on lead add page
                10 => 'leads.fetch', // default permission to fetch lead data on popup
                11 => 'leads.edit', // default permission to lead edit on popup
                12 => 'lead.checkDuplicateEmail', // default permission to check duplicate email
                13 => 'user.checkDuplicate', //default permission to check mobile duplicate
                14 => 'lead.existedClientDetail', //default permission for client listing on lead module
            ]
        ];
        if($user->role==1){
            $permissionDetails['status'] = true;
            $systemMenus = Menu::get();
        }else{
            $permissions = RoleMenu::where('roleId',$user->role)->get();
            if($permissions->isNotEmpty()){
                foreach($permissions as $permission){
                    $menuDetail = Menu::find($permission->menuId);
                    $availableActions = explode(',',$permission->permission);
                    $menuActions = MenuAction::whereIn('id',$availableActions)->get();
                    if($menuActions->isNotEmpty()){
                        foreach($menuActions as $menuAction){
                            $permissionDetails['accessableRoutes'][] = $menuAction->route;
                        }
                        $permissionDetails['accessableRoutes'][] = $menuDetail->url;
                        $permissionDetails['menuId'][] = $menuDetail->id;
                        if($menuDetail->parentId>0){
                            $permissionDetails['menuId'][] = $menuDetail->parentId;
                        }
                    }else{
                        $permissionDetails['accessableRoutes'][] = $menuDetail->url;
                        $permissionDetails['menuId'][] = $permission->menuId;
                    }
                }
            }
            $permissionDetails['status'] = in_array($routeName,$permissionDetails['accessableRoutes']);

            //special condition for all forms step
            $affFormRoutes = $this->getAllFormRoutes();
            if(in_array('task.followup',$permissionDetails['accessableRoutes']) && in_array($routeName,$affFormRoutes)){
                $permissionDetails['status'] = true;
            }
            //End
            if (!$permissionDetails['status']) {
                abort(403, 'You do not have permission to access this page.');
            }
            $systemMenus = Menu::whereIn('id',$permissionDetails['menuId'])->get();
        }
        
        $serializeMenus = [];
        $menuSubMenuRoutes = [];
        $subMenuActions = [];
        if($systemMenus->isNotEmpty()){
            foreach($systemMenus as $k =>$v){
                if($v->parentId==0){
                    $serializeMenus[$v->id]['menu']['name'] = $v->menuName;
                    $serializeMenus[$v->id]['menu']['url'] = $v->url;
                    $serializeMenus[$v->id]['menu']['icon'] = $v->icon;
                    $serializeMenus[$v->id]['menu']['groupedRoutes'] = $v->actionRoutes;
                    $serializeMenus[$v->id]['menu']['sequence'] = $v->sequence;
                }
                if($v->parentId>0){
                    //Check if it is sub menu or sub sub menu
                    $checkLinkedMenus = Menu::find($v->parentId);
                    if($checkLinkedMenus->parentId>0){
                        //it is sub sub menu
                        $serializeMenus[$checkLinkedMenus->parentId]['subSubMenu'][$checkLinkedMenus->id][$v->id]['name'] = $v->menuName;
                        $serializeMenus[$checkLinkedMenus->parentId]['subSubMenu'][$checkLinkedMenus->id][$v->id]['url'] = $v->url;
                        $serializeMenus[$checkLinkedMenus->parentId]['subSubMenu'][$checkLinkedMenus->id][$v->id]['icon'] = $v->icon;
                    }else{
                        $serializeMenus[$v->parentId]['subMenu'][$v->id]['name'] = $v->menuName;
                        $serializeMenus[$v->parentId]['subMenu'][$v->id]['url'] = $v->url;
                        $serializeMenus[$v->parentId]['subMenu'][$v->id]['icon'] = $v->icon;
                    }
                }

                //Grouping routes per menu for active class
                if($v->parentId>0){
                    $groupedRoutes = explode(',',$v->actionRoutes);
                    $subMenuActions[$v->id] = $groupedRoutes;
                    if(!empty($groupedRoutes)){
                        foreach($groupedRoutes as $groupedRoute){
                            $menuSubMenuRoutes[$v->parentId][] = $groupedRoute;
                            if($v->url=='javascript:void(0);'){
                                $menuSubMenuRoutes[$v->parentId][$v->id][] = $groupedRoute;
                            }
                        }
                    }
                }
                //End
            }
            uasort($serializeMenus, function ($a, $b) {
                return $a['menu']['sequence'] <=> $b['menu']['sequence'];
            });
            view()->share(compact('serializeMenus','menuSubMenuRoutes','permissionDetails','subMenuActions'));
        }

        return $next($request);
    } 

    public function getAllFormRoutes(){
        return [
            /** trademark start ** */
            '0' => 'task.chekDuplication',
            '1' => 'task.documentVerified',
            '2' => 'task.documentVerifiedChildSatge',
            '3' => 'task.sendQuotation',
            '4' => 'task.checkPayment',
            '5' => 'task.paymentStatus',
            '6' => 'task.patentSendQuotation',
            '7' => 'task.patentPaymentVerification',
            '8' => 'task.patentPriorArt',
            '9' => 'task.documentation',
            '10' => 'task.documenStatus',
            '11' => 'task.clientApproval',
            '12' => 'task.clientApprovalStatus', 
            '13' => 'task.draftApplication', 
            '14' => 'task.draftApplicationStatus', 
            '15' => 'leads.getLogs', 
            '16' => 'task.formalityCheck',
            '17' => 'task.formalityCheckStatus',
            /** trademark stop ** */

            /** Patent Start ** */

            '18' => 'task.patentSubmitPriorArt',
            '19' => 'task.negotiatePrice',
            '20' =>  'task.patentDocumentation',
            '21' =>  'task.patentDraft',
            '22' =>  'task.patentSubmitDraft',
            '23' =>  'task.patentClientApproval',
            '24' =>  'task.patentSubmitClientApproval',
            '25' =>  'task.patentfilingProccess',
            '26' =>  'task.patentfilingProccessStatus',
            '27' =>  'task.patentCompleteSpecification',
            '28' =>  'task.patentCompletedSpecificationSubmit',
            '29' =>  'task.patentForm9',
            '30' =>  'task.patentForm9Submit',
            '31' =>  'task.patentearlypublication',
            '32' =>  'task.patentearlypublicationSubmit',
            '33' =>  'task.patentStandardpublication',
            '34' =>  'task.patentStandardpublicationSubmit',
            '35' =>  'task.patentForm18',
            '36' =>  'task.patentForm18Submit',
            '37' =>  'task.patentFER',
            '38' =>  'task.patentFERSubmit',
            '39' =>  'task.patentSER',
            '40' =>  'task.patentSERSubmit',
            '41' =>  'task.patentHearingSendQuotation',
            '42' =>  'task.patentHearingPaymentVerification',
            '43' =>  'task.patentawaitHearingDate',
            '44' =>  'task.patentawaitHearingDateSubmit',
            '45' =>  'task.patentHearing',
            '46' =>  'task.patentHearingSubmit',
            '47' =>  'task.patentPreGrantOpposition',
            '48' =>  'task.patentPreGrantOppositionSubmit',
            '49' =>  'task.patentoppositionSendQuotation',
            '50' =>  'task.patentoppositionPaymentVerification',
            '51' =>  'task.patentoppositionNoticeDate',
            '52' =>  'task.patentoppositionNoticeDateSubmit',
            '53' =>  'task.patentoppositionCounterStatement',
            '54' =>  'task.patentoppositionCounterStatementSubmit',
            '55' =>  'task.patentoppositionNonCompliance',
            '56' =>  'task.patentoppositionNonComplianceSubmit',
            '57' =>  'task.patentoppositionAwaitingHearing',
            '58' =>  'task.patentoppositionAwaitingHearingSubmit',
            '59' =>  'task.patentoppositionClientConfirmation',
            '60' =>  'task.patentoppositionClientPaymentVerification',
            '61' =>  'task.patentoppositionHearing',
            '62' =>  'task.patentoppositionHearingSubmit',
            '63' =>  'task.patentoppositionWrittenSubmission',
            '64' =>  'task.patentoppositionWrittenSubmissionSubmit',
            '65' =>  'task.patentoppositionRegistry',
            '66' =>  'task.patentoppositionRegistrySubmit',
            '67' =>  'task.patentoppositionEvidencerule45',
            '68' =>  'task.patentoppositionEvidencerule45Submit',
            '69' =>  'task.patentoppositionEvidencerule46',
            '70' =>  'task.patentoppositionEvidencerule46Submit',
            '71' =>  'task.patentoppositionEvidencerule47',
            '72' =>  'task.patentoppositionEvidencerule47Submit',
            '73' =>  'task.patentStatus',
            '74' =>  'task.patentStatusSubmit',
            '75' =>  'task.patentPostRegistrationAction',
            '76' =>  'task.patentPostRegistrationActionSubmit',
            '77' =>  'task.patentInterlocatoryPetitionCounter',
            '78' =>  'task.patentInterlocatoryPetitionCounterSubmit',
            '79' =>  'task.patentCounterStatementAwaitingHearing',
            '80' =>  'task.patentCounterStatementAwaitingHearingSubmit',
            '81' =>  'task.patentCounterStatementClientConfirmation',
            '82' =>  'task.patentCounterStatementClientPaymentVerification',
            '83' =>  'task.patentCounterStatementHearing',
            '84' =>  'task.patentCounterStatementHearingSubmit',
            '85' =>  'task.patentPostGrantOpposition',
            '86' =>  'task.patentPostGrantOppositionSubmit',
            '87' =>  'task.patentStatusPostRegister',
            '88' =>  'task.patentStatusPostRegisterSubmit',
            '89' =>  'task.patentClientIntimationPostRegister',
            '90' =>  'task.patentClientIntimationPostRegisterSubmit',
            '91' =>  'task.patentPaymentConfirmationPostRegister',
            '92' =>  'task.patentPaymentConfirmationPostRegisterSubmit',
            '93' =>  'task.patentNoticeDatePostRegister',
            '94' =>  'task.patentNoticeDatePostRegisterSubmit',
            '95' =>  'task.patentNonCompliancePostRegister',
            '96' =>  'task.patentNonCompliancePostRegisterSubmit',
            '97' =>  'task.patentInterlocatoryPetitionPostRegistered',
            '98' =>  'task.patentInterlocatoryPetitionPostRegisteredSubmit',
            '99' =>  'task.patentCounterStatementPostRegister',
            '100' =>  'task.patentCounterStatementPostRegisterSubmit',
            '101' =>  'task.patentPatentRegistryPostRegister',
            '102' =>  'task.patentPatentRegistryPostRegisterSubmit',
            '103' =>  'task.patentOpponentEvidenceRule45PostRegistered',
            '104' =>  'task.patentOpponentEvidenceRule45PostRegisteredSubmit',
            '105' =>  'task.patentApplicantEvidenceRule46PostRegistered',
            '106' =>  'task.patentApplicantEvidenceRule46PostRegisteredSubmit',
            '107' =>  'task.patentOpponentEvidenceRule47PostRegistered',
            '108' =>  'task.patentOpponentEvidenceRule47PostRegisteredSubmit',
            '109' =>  'task.patentAwaitHearingPostRegistration',
            '110' =>  'task.patentAwaitHearingPostRegistrationSubmit',

            /* ***patent stop */

            /* ***trademrk route */
            '111' => 'task.initialExamination',
            '112' => 'task.initialExaminationStatus',
            '113' => 'task.replyAdded',
            '114' => 'task.replyAddedStatus',
            '115' => 'task.govtPortalReply',
            '116' => 'task.govtPortalReplyStatus',
            '117' => 'task.examinationInform',
            '118' => 'task.examinationInformStatus',
            '119' => 'task.examinationPayment',
            '120' => 'task.examinationPaymentStatus',
            '121' => 'task.hearingDate',
            '122' => 'task.hearingDateStatus',
            '123' => 'task.showCaseHearing',
            '124' => 'task.showCaseHearingStatus',
            '125' => 'task.markAsPublish',
            '126' => 'task.markAsPublishStatus',
            '127' => 'task.markPublishOpposition',
            '128' => 'task.markPublishOppositionStatus',
            '129' => 'task.informClientAfterPublish',
            '130' => 'task.informClientAfterPublishStatus',
            '131' => 'task.oppositionPayment',
            '132' => 'task.oppositionPaymentStatus',
            '133' => 'task.oppositionCounterStatement',
            '134' => 'task.oppositionCounterStatementStatus',
            '135' => 'task.opponentEvidenceSubmission',
            '136' => 'task.opponentEvidenceSubmissionStatus',
            '137' => 'task.applicantEvidenceSubmission',
            '138' => 'task.applicantEvidenceSubmissionStatus',
            '139' =>  'task.hold',
            '140' =>  'task.reject',
            '141' =>  'task.DocumentDraft',
            '142' =>  'task.DocumentDraftStatus',
            '143' =>  'task.DocumentDraftStatus',
            '143' => 'task.oppositionNoticeDate',
            '144' => 'task.oppositionNoticeDateStatus',
            '145' => 'task.noticeSent',
            '146' => 'task.noticeSentStatus',
            '147' => 'task.oppositionResubmissionEvedince',
            '148' => 'task.oppositionResubmissionEvidenceStatus',
            '149' => 'task.nonCompliance',
            '150' => 'task.nonComplianceStatus',
            '151' => 'task.interlocuteryOpposition',
            '152' => 'task.interlocuteryOppositionStatus',
            '153' => 'task.oppositionHearingDate',
            '154' => 'task.oppositionHearingDateStatus',
            '155' => 'task.clientApprovalOnHearing',
            '156' => 'task.clientApprovalOnHearingStatus',
            '157' => 'task.clientInformForHearingCharge',
            '158' => 'task.clientInformForHearingChargeStatus',
            '159' => 'task.hearingChargeUpdate',
            '160' => 'task.hearingChargeStatus',
            '161' => 'task.hearing',
            '162' => 'task.hearingStatus',
            '163' => 'task.agreementSubmission',
            '164' => 'task.tradeMark',
            '165' => 'task.trademarkStatus',
            '166' => 'task.postRegistration',
            '167' => 'task.postRegistrationStatus',
            '168' => 'task.rectification',
            '169' => 'task.rectificationStatus',
            '170' => 'task.informClientRectification',
            '171' => 'task.informClientRectificationStatus',
            '172' => 'task.paymentOnRectification',
            '173' => 'task.paymentOnRectificationStatus',
            '174' => 'task.rectificationNoticeReceived',
            '175' => 'task.rectificationNoticeReceivedStatus',
            '176' => 'task.rectificationCounterStatement',
            '177' => 'task.rectificationCounterStatementStatus',
            '178' => 'task.rectificationNoticeSent',
            '179' => 'task.rectificationNoticeSentStatus',
            '180' => 'task.rectificationOpponentEvidenceSubmission',
            '181' => 'task.rectificationOpponentEvidenceSubmissionStatus',
            '182' => 'task.rectifiactionApplicationEvidenceSubmission',
            '183' => 'task.rectifiactionApplicationEvidenceSubmissionStatus',
            '184' => 'task.rectificationOpponentResubmisson',
            '185' => 'task.rectificationOpponentResubmissonStatus',
            '186' => 'task.rectificationHearing',
            '187' => 'task.rectificationHearingStatus',
            '188' => 'task.clientApprovalOnHearingRectification',
            '189' => 'task.clientApprovalOnHearingRectificationStatus',
            '190' => 'task.clientInformExtraChargeOnHearingRectification',
            '191' => 'task.clientInformExtraChargeOnHearingRectificationStatus',
            '192' => 'task.rectificationClientHearingCharge',
            '193' => 'task.rectificationClientHearingChargeStatus',
            '194' => 'task.rectificationUpdateHearing',
            '195' => 'task.rectificationUpdateHearingStatus',
            '196' => 'task.rectficationWrittenSubmission',
            '197' => 'task.rectficationWrittenSubmissionStatus',
            '198' => 'task.rectificationTrademark',
            '199' => 'task.rectificationTrademarkStatus',
            '200' => 'task.trademarkRenewal',
            '201' => 'task.trademarkRenewalStatus',
            '202' => 'task.clientApprovalOnRenewal',
            '203' => 'task.clientApprovalOnRenewalStatus',
            '204' => 'task.renewalPaymentConfirmation',
            '205' => 'task.renewalPaymentConfirmationStatus',
            '206' => 'task.renewalFiled',
            '207' => 'task.renewalFiledStatus',
            '208' => 'task.informClientRenwalApproved',
            '209' => 'task.informClientRenwalApprovedStatus',
            '210' => 'task.changeAddress',
            '211' => 'task.changeAddressStatus',
            '212' => 'task.changeAddressPayment',
            '213' => 'task.changeAddressPaymentStatus',
            '214' => 'task.changeAddressQueryForm',
            '215' => 'task.changeAddressQueryFormStatus',
            '216' => 'task.changeAddressFiled',
            '217' => 'task.changeAddressFiledStatus',
            '218' => 'task.addressChanged',
            '219' => 'task.addressChangedStatus',
            '220' => 'task.assignmentUserRegsiter',
            '221' => 'task.assignmentUserRegsiterStatus',
            '222' => 'task.assignmentUserRegsiterPayment',
            '223' => 'task.assignmentUserRegsiterPaymentStatus',
            '224' => 'task.assignmentQueryForm',
            '225' => 'task.assignmentQueryFormStatus',
            '226' => 'task.assignmentAffidavit',
            '227' => 'task.assignmentAffidavitStatus',
            '228' => 'task.assignmentFileProcess',
            '229' => 'task.assignmentFileProcessStatus',
            '230' => 'task.assignmentUpdateClient',
            '231' => 'task.assignmentUpdateClientStatus',
            '232' => 'task.assignmentIntimateClient',
            '233' => 'task.assignmentIntimateClientStatus',
            '234' => 'task.expidetProcess',
            '235' => 'task.expidetProcessStatus',
            '236' => 'task.expidetProcessPayment',
            '237' => 'task.expidetProcessPaymentStatus',
            '238' => 'task.expidetProcessFile',
            '239' => 'task.expidetProcessFileStatus',
            '240' => 'task.expidetProcessFileInformClient',
            '241' => 'task.expidetProcessFileInformClientStatus',
            '242' => 'task.ipWatch',
            '243' => 'task.ipWatchStatus',
            '244' => 'task.ipWatchPayment',
            '245' => 'task.ipWatchPaymentStatus',
            '246' => 'task.ipWatchFrequency',
            '247' => 'task.ipWatchFrequencyStatus',
            '248' => 'task.rectificationNonCompliance',
            '249' => 'task.rectificationNonComplianceStatus',
            '250' => 'task.rectificationInterlocutory',
            '251' => 'task.rectificationInterlocutoryStatus',

            '252' => 'task.patentClientDecisionPostRegistration',
            '253' => 'task.patentClientDecisionPostRegistrationSubmit',

            '254' => 'task.patentAssignmentRegisteredUser',
            '255' => 'task.patentAssignmentRegisteredUserStatus',
            '256' => 'task.patentAssignmentPayemnt',
            '257' => 'task.patentAssignmentPayemntStatus',
            '258' => 'task.patentAssignmentQueryForm',
            '259' => 'task.patentAssignmentQueryFormStatus',
            '260' => 'task.patentAssignmentAffidavit',
            '261' => 'task.patentAssignmentAffidavitStatus',
            '262' => 'task.patentAssignmentFilingProcess',
            '263' => 'task.patentAssignmentFilingProcessStatus',
            '264' => 'task.patentAssignmentUpdateClientDetails',
            '265' => 'task.patentAssignmentUpdateClientDetailsStatus',
            '266' => 'task.patentAssignmentInitmateClient',
            
            '267' => 'task.patentAssignmentInitmateClientStatus',
            '268' => 'task.patentAddress',
            '269' => 'task.patentAddressStatus',
            '270' => 'task.patentAddressPayment',
            '271' => 'task.patentAddressPaymentStatus',
            '272' => 'task.patentAddressQueryForm',
            '273' => 'task.patentAddressQueryFormStatus',
            '274' => 'task.patentAddressFilingProcess',
            '275' => 'task.patentAddressFilingProcessStatus',
            '276' => 'task.patentClientIntimationAddressChange',
            '277' => 'task.patentClientIntimationAddressChangeStatus',
            '278' => 'task.patentNotifyClientForExtraChargePostRegister',
            '279' => 'task.patentNotifyClientForExtraChargePostRegisterSubmit',
            '280' => 'task.patentUpdateHearingChargeStatusPostRegister',

            '281' => 'task.patentUpdateHearingChargeStatusPostRegisterSubmit',
            '282' => 'task.patentHearingPostRegister',
            '283' => 'task.patentHearingPostRegisterSubmit',
            '284' => 'task.patentWrittenSubmissionPostRegistered',
            '285' => 'task.patentWrittenSubmissionPostRegisteredSubmit',
            '286' => 'task.patentRenewal',
            '287' => 'task.patentRenewalSubmit',
            '288' => 'task.patentPaymentConfirmationRenewal',
            '289' => 'task.patentPaymentConfirmationRenewalSubmit',
            '290' => 'task.patentRenewalFiled',

            '291' => 'task.patentRenewalFiledSubmit',
            '292' => 'task.patentClientIntimationRenewal',
            '293' => 'task.patentClientIntimationRenewalSubmit',
            '294' => 'task.patentInventorCertificate',
            '295' => 'task.patentInventorCertificateSubmit',
            '296' => 'task.patentInCertiPayment',
            '297' => 'task.patentInCertiPaymentSubmit',
            '298' => 'task.patentInCertiFiled',
            '299' => 'task.patentInCertiFiledSubmit',
            '300' => 'task.patentQuotationRevocation',

            '301' => 'task.patentQuotationRevocationSubmit',
            '302' => 'task.patentPaymentRevocation',
            '303' => 'task.patentPaymentRevocationSubmit',
            '304' => 'task.patentRevocation',
            '305' => 'task.patentRevocationSubmit',
            '306' => 'task.patentRestoration',
            '307' => 'task.patentRestorationSubmit',

            /** trademark stop ** */
            

        ];
    }
}
