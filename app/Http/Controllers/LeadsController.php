<?php
namespace App\Http\Controllers;
use App\Models\CategoryOption;
use Illuminate\Support\Facades\Auth;
use App\Models\Service; 
use App\Models\SubService;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Payment;
use Carbon\Carbon;
use App\Models\LeadService;
use App\Models\LeadAttachment;
use App\Models\LeadLog;
use App\Models\FollowUp;
use App\Models\LeadNotification;
use App\Models\LeadTask;
use App\Models\Firm;
use App\Models\LeadTaskDetail;
use App\Models\ServiceStages;
use Illuminate\Support\Facades\Validator;
class LeadsController extends Controller
{
    public function index(Request $request){
        $allRequestData = $request;
        if(!isset($request->tab)){
            $request->tab = 1;
        }
        if(base64_decode($request->id) > 0){
            $baseNotifyId = base64_decode($request->NotifyId);
            $notifyData = LeadNotification::where('id',$baseNotifyId)->update(['status'=>1]);
            $baseId = base64_decode($request->id);
            $leadList = Lead::with('leadTasks')->where('id',$baseId);
        }else{
            if(auth()->user()->role != 1 && auth()->user()->role != 4){
                $leadList = Lead::with('leadTask')->where('archive',$request->tab);
            }else{
                $leadList = Lead::with('leadTask')->where('archive',$request->tab);
            }           
        }
        
        $sourceKey = $request->input('source') ?? '';
        $serviceKey = $request->input('service') ?? '';
        $statusKey = $request->input('status') ?? '';
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if($sourceKey != '' || $serviceKey != '' || $statusKey != '' || $searchKey != ''){
                     
            $leadList->when(!empty($request->source), function ($q) use ($request) {
                $q->where('source', $request->source);
            })
            ->when(isset($request->status), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when(!empty($request->service), function ($q) use ($request) {
                $q->whereHas('leadTasks', function ($q) use ($request) {
                    $q->where('service_id', $request->service);
                });
            })
            ->when(!empty($request->key), function($q) use ($request) {
                $q->where('client_name', 'LIKE', '%' . $request->key . '%');
            }); 
        }
        $leadList = $leadList->latest()->paginate(env("PAGINATION_COUNT"));      
        if(empty($requestType)){
            $sourceList = Lead::where('user_id', auth()->user()->id)
            ->select('source')
            ->groupBy('source')
            ->with('categoryOptions',function($q){
                $q->where('type',3)->where('status',1);
            })->get();
            $serviceList = LeadTask::when(auth()->user()->id !== 1, function ($query) {
                return $query->where('user_id', auth()->user()->id);
            })
            ->get()
            ->pluck('service_id');
            $userList = User::where('role','>=',5)->get();
            $header_title_name = 'Leads';
            return view('leads/index',compact('allRequestData','header_title_name','leadList','sourceList','serviceList','userList','sourceKey','serviceKey','statusKey','searchKey'));
        }else{
            $trData = view('leads/lead-page-filter-data',compact('allRequestData','leadList','sourceKey','serviceKey','statusKey','searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
                'source'=>$request->source,
                'service'=>$request->service,
                'status'=>$request->status,
            ];
            return response()->json($dataArray);
        }   
        
    }

    public function add(Request $request, $id = null){
        if($id > 0){
            $leadData = Lead::where('id',$id)->first();
            if($leadData){
                if($leadData->status == 1){
                    return redirect()->back()->with('error','Authorized error!');
                }
            }else{
                return redirect()->route('leads.index')->with('error','Authorized error!');
            }          
            $leadOldData = Lead::where('id',$id)->first();
            $leadAttachment = LeadAttachment::where('lead_id',$id)->get();
            $LeadTask = LeadTask::with('leadTaskDetails')->where('lead_id',$id)->get();
            $email = "required|email";
            $successMsg = 'Lead updated!';
        }else{
            $leadData = new Lead();
            $leadOldData =new Lead();
            $leadAttachment = [];            
            $LeadTask = [];
            $email = "required|email|unique:users,email";
            $successMsg = 'Lead added!';
        }       
        $sourceList = CategoryOption::where('type',3)->where('status',1)->get();
        $scopeOfBussinessList = CategoryOption::where('status', 1)->where('type', 4)->get();
        $serviceList = Service::where('status',1)->get();
        $userList = User::where('role','>=',5)->where('status',1)->get();
        $clientList = User::where('role',2)->where('status',1)->get();
        $projectManagerList = User::where('role',4)->where('status',1)->get();
        $firmList = Firm::where('status',1)->get();

        if($request->isMethod('POST')){
            // dd($request);
            $scopeOfBusinessArray = $request->scopeofbusiness;            
            if(in_array('other', $request->scopeofbusiness)){
                $scopeOfBusinessArray = array_diff($scopeOfBusinessArray, ['other']);
                $categoryData = new CategoryOption();
                $categoryData->authId = Auth::id();
                $categoryData->type = 4;
                $categoryData->name = $request->otherscopeofbusiness;
                $categoryData->status = 1;
                if($categoryData->save()){
                    $scopeOfBusinessArray[] = $categoryData->id;
                }
            }
            $credentials = $request->validate([
                'email' => $email,
            ]);
            if($request->sourcetypenamelist > 0){
                $sourceId = $request->sourcetypenamelist;              
            }else{
                $sourceId = 0;
            }
            $leadData->user_id = auth()->user()->id;
            $clientName = strtoupper(substr($request->input('clientname'), 0, 3));  
            if(empty($clientName)){
                $clientName = 'LEA';
            }         
            $randomNumber = rand(10, 99);
            $lastLead = Lead::latest('id')->first();
            $lastLeadId = $lastLead ? $lastLead->id + 1 : 1; 
            $lead_id = $clientName . $randomNumber . $lastLeadId;
            $existingLead = Lead::where('lead_id', $lead_id)->first();
            if ($existingLead) {
                $lead_id = $clientName . $randomNumber . ($lastLeadId + 1);
            }
            $leadData->lead_id = $lead_id; 
            $leadData->source = $request->source;
            $leadData->source_id = $sourceId;
            $leadData->client_name = $request->clientname;
            $leadData->company_name = $request->companyname;
            $leadData->mobile_number = $request->mobilenumber;
            $leadData->email = $request->email;
            $leadData->description = $request->description;
            $leadData->msmem = $request->msmem;
            $leadData->business_scope = implode(',',$scopeOfBusinessArray);
            $leadData->status = $request->savetype;
            $leadData->firm = $request->firm;
            if($leadData->save()){
                $serviceidArray = [];
                // lead attachment repeater...         
                if(!empty($request->leadAttachment)){
                    foreach($request->leadAttachment as $attachmentKey => $attachmentVal){
                        if(isset($attachmentVal['attachmentFile']) && !empty($attachmentVal['attachmentFile'])){
                            if($attachmentVal['attachment_id'] > 0){
                                $leadAttachment = LeadAttachment::where('id',$attachmentVal['attachment_id'])->first();
                            }else{
                                $leadAttachment = new LeadAttachment();
                            } 
                            $leadAttachment->lead_id = $leadData->id;
                            if (isset($attachmentVal['attachmentFile']) && $attachmentVal['attachmentFile'] instanceof \Illuminate\Http\UploadedFile) {                               

                                $image_name = $attachmentVal['attachmentFile'];
                                $imageName = rand(100000, 999999).'.'.$image_name->getClientOriginalExtension();
                                $image_name->move(public_path('uploads/leads/'.$leadData->id), $imageName);
                                $leadAttachment->document = $imageName;
                            }                        
                            $leadAttachment->save();
                        }
                    }
                }  
                // lead service repeater...
                if(!empty($request->leadRepeater)){
                    foreach($request->leadRepeater as $serviceKey => $serviceVal){
                        if($serviceVal['lead_task_id'] > 0){
                            $leadTaskData = LeadTask::where('id',$serviceVal['lead_task_id'])->first();
                            $LeadTaskDetail = LeadTaskDetail::where('task_id',$serviceVal['lead_task_id'])->first();
                        }else{
                            $leadTaskData = new LeadTask();
                            $LeadTaskDetail = new LeadTaskDetail();
                        }     
                          
                        $leadTaskData->lead_id = $leadData->id; 
                        $leadTaskData->project_manager_id = $serviceVal['projectmanager'];
                        $leadTaskData->service_id = $serviceVal['serviceid'];
                        $serviceidArray[] = $serviceVal['serviceid'];
                        $leadTaskData->subservice_id = $serviceVal['subserviceid']; 
                        $leadTaskData->user_id = $serviceVal['assign'];
                        $leadTaskData->service_stage_id = $serviceVal['stage_id'];
                        $leadTaskData->assign_by = auth()->user()->id;
                        $leadTaskData->task_title = ServiceStages::find($serviceVal['stage_id'])->description;

                        if($leadTaskData->save()){                            
                            $LeadTaskDetail->task_id = $leadTaskData->id;
                            $LeadTaskDetail->dead_line = date('Y-m-d',strtotime($serviceVal['taskdeadline']));
                            $LeadTaskDetail->status = 0;
                            $LeadTaskDetail->status_date = NULL;
                            $LeadTaskDetail->save();
                            if($request->savetype == 1 && $leadOldData->status==0){
                                // lead logs...
                                $LeadLog = new LeadLog();
                                $LeadLog->user_id = $serviceVal['assign'];
                                $LeadLog->lead_id =  $leadData->id;
                                $LeadLog->task_id = $leadTaskData->id;
                                $LeadLog->assign_by = auth()->user()->id;
                                $LeadLog->description = 'Lead added in the system';
                                $LeadLog->status = 0;
                                $LeadLog->save();
                                $LeadLog = new LeadLog();
                                $LeadLog->user_id = $serviceVal['assign'];
                                $LeadLog->lead_id =  $leadData->id;
                                $LeadLog->task_id = $leadTaskData->id;
                                $LeadLog->assign_by = auth()->user()->id;
                                $LeadLog->description = 'Lead assigned to the user';
                                $LeadLog->status = 0;
                                $LeadLog->save();
                            
                                // lead notification...
                                $serviceNames = '';
                                if(!empty($serviceidArray)){                        
                                    $serviceNames = Service::whereIn('id', $serviceidArray)->get()->pluck('serviceName')->implode(', ');                        
                                }
                                
                                $LeadNotification = new LeadNotification();
                                $LeadNotification->user_id = $serviceVal['assign'];
                                $LeadNotification->lead_id = $leadData->id;
                                $LeadNotification->task_id = $leadTaskData->id;
                                $LeadNotification->title = 'Lead Assigned';
                                $LeadNotification->description = 'New lead is assigned to you for '.$serviceNames;
                                $LeadNotification->status = 0;
                                $LeadNotification->save();
        
                                $LeadNotification = new LeadNotification();
                                $LeadNotification->user_id = $serviceVal['assign'];
                                $LeadNotification->task_id = $leadTaskData->id;
                                $LeadNotification->lead_id = $leadData->id;
                                $LeadNotification->title = 'Task Assigned';
                                $LeadNotification->description = 'New task assigned - '.$leadTaskData->task_title;
                                $LeadNotification->status = 0;
                                $LeadNotification->save();
                            }           
                        } 
                    }                 
                }   
                 
                return redirect()->route("leads.index", ['tab' => 1])->withSuccess($successMsg);
            }
        }
        $header_title_name = 'Lead';
        return view('leads/add',compact('header_title_name','firmList','sourceList','serviceList','projectManagerList','userList','clientList','leadData','leadAttachment','LeadTask','scopeOfBussinessList'));
    }
    
    public function leadFetch(Request $request){
        $leadData = Lead::with(['leadTasks','leadAttachments'])->find($request->id);
        $serviceDataArray = [];
        foreach($leadData->leadTasks as $dataKey => $dataVal){
            $userData = User::find($dataVal->user_id);
            $serviceData = Service::find($dataVal->service_id);
            $serviceDataArray['users'][] = $userData->name;
            $serviceDataArray['services'][] = $serviceData->serviceName;
        }
        $serviceData='';
        foreach($serviceDataArray['users'] as $index => $user) {
            $service = $serviceDataArray['services'][$index] ?? 'No Service';
            $serviceData .= "
            <tr>                
                <td class='border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px] pl-[25px]'>{$service}</td>
                <td class='border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px] pl-[25px]'>{$user}</td>
            </tr>
            ";
        }        
        return response()->json([
            'serviceData'=>$serviceData,
            'data' =>$leadData
           ]);
    }

    public function checkEmailDuplicate(Request $request){
        if ($request->isMethod('POST')) {
            if ($request->id > 0) {
                $checkUserData = Lead::where('id', '!=', $request->id)->where('email', $request->email)->exists();
            } else {
                $checkUserData = Lead::where('email', $request->email)->exists();
            }
            return response()->json(['exists' => $checkUserData]);
        }
        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function edit(Request $request){
        $leadData = Lead::find($request->lead_id);
        if($request->isMethod('POST')){
            
            $leadData->update([
                'client_name' => $request->modalclientname,
                'company_name' => $request->modalcompanyname,
                'mobile_number' => $request->modalmobilenumber,
                'email' => $request->modalemail,
                'description' => $request->modaldescription
            ]);
           
            if($leadData->save()){
                if ($request->has('modalfileattachment')) {
                    foreach ($request->modalfileattachment as $key => $val) {
                        if ($val instanceof \Illuminate\Http\UploadedFile) {
                            $leadAttachment = new LeadAttachment();
                            $imageName = rand(100000, 999999) . '.' . $val->getClientOriginalExtension();
                            $uploadPath = public_path('uploads/leads/' . $leadData->id);
                            if (!file_exists($uploadPath)) {
                                mkdir($uploadPath, 0777, true);
                            }
                            $val->move($uploadPath, $imageName);
                            $leadAttachment->lead_id = $leadData->id;
                            $leadAttachment->document = $imageName;
                            $leadAttachment->save();
                        }
                    }
                    return redirect()->back()->with('success','Lead updated!');
                }else{
                    return redirect()->back()->with('success','Lead updated successfully!');
                }
            }else{
                return redirect()->back()->with('error','Some error is occur!');
            }
        }
    }

    public function getSubService(Request $request){
       $subService = SubService::where('serviceId',$request->serviceId)->get()->toArray();
       $options ='<option value="">Service Type</option>';
       foreach($subService as $k => $v){
            $options .= "
                <option value='{$v['id']}'>{$v['subServiceName']}</option>
            ";    
       }
       return response()->json([
        'data' =>$options
       ]);
    }

    public function sendQuote(){
        $header_title_name = 'Lead';
        return view('leads/sendquote', compact('header_title_name'));
    }

    public function leadLogs(Request $request){
        $header_title_name = 'Lead logs';
        $leadData = lead::all();
        $requestParams = $request->all();
        $leadLogs = LeadLog::with('leadTask','leadTask.leadTaskDetails','leadTask.serviceSatge')->get();
        if($request->lead_id > 0){
            $leadLogs = LeadLog::with('leadAttch','leadTask','leadTask.leadTaskDetails', 'leadTask.serviceSatge')->where('lead_id', $request->lead_id) ->orderBy('id', 'desc')->get();  
        }
        return view('leads.logs', compact('leadData','leadLogs', 'header_title_name','requestParams'));
    }

    public function getLogs(Request $request){
        if($request->lead_id > 0){
            $lead_id = $request->lead_id; 
            $leadLogs = LeadLog::with('leadTask','lead','leadTask.user','leadTask.leadTaskDetails', 'leadTask.serviceSatge')->where('id', $request->lead_id)->get();
            $allLeadData = [];
            foreach ($leadLogs as $lead) {
                $data = [
                    'client_name' => $lead->lead->client_name,
                    'lead_id' => $lead->lead->lead_id,
                    'stage' => $lead->leadTask->serviceSatge->title,
                    'assignTo' => $lead->leadTask->user->name,
                    'status' => $lead->leadTask->leadTaskDetails->status,
                    'deadLine' => $lead->leadTask->leadTaskDetails->dead_line,
                    'verifiedOn' => $lead->leadTask->leadTaskDetails->status_date,
                    'remark' => $lead->leadTask->task_description,
                    'logDescription' => $lead->description,
                    'services' => [] 
                ];
                
                $data['services'][] = $lead->leadTask->services->serviceName;  
                
                
                $allLeadData[] = $data;
            }
            return response()->json(['data' => $allLeadData, 'status' => 200]);

        }else{
            return response()->json(['data' => 0, 'status' => 400 ]);

        }
    }

    public function getSourceTypeName(Request $request){
        if($request->value == 18){
            $value = 3;
            $sign = '=';
        }elseif($request->value == 19){
            $value = 5;
            $sign = '>=';
        }elseif($request->value == 17){
            //CLIENT
            $value = 2;
            $sign = '=';
        }
        $userData = User::where('role',$sign,$value)->get();
        $options = '<option value="">Select</option>';
        foreach($userData as $k => $v){
            $options .="<option value=\"{$v['id']}\">{$v['name']}</option>";
        }
        return response()->json([
            'data'=>$options
        ]);
    }

    public function deleteRepeaterLead(Request $request){
        $leadTask = LeadTask::find($request->id);
        if($leadTask->delete()){
            $leadTaskDetails = LeadTaskDetail::where('task_id',$request->id);
            if($leadTaskDetails->delete()){
                echo "1";
            }else{
                return redirect()->back()->withError('Some error is occur while deleting lead service!');
            }
        }else{
            return redirect()->back()->withError('Some error is occur while deleting lead service!');
        }
    }

    public function deleteAttachmentRepeaterLead(Request $request){
        $leadServiceDel = LeadAttachment::where('id', $request->id)->first(); 

        if ($leadServiceDel && $leadServiceDel->delete()) {
            $remainData = LeadAttachment::where('lead_id', $request->leadId)->get();
        } else {
            $remainData = [];
        }

        return response()->json(['data' => $remainData]);
    }

    public function archiveLead(Request $request, $id = null){
        $lead = Lead::where('id', $id)->first();    
        if ($lead) {
            $newArchiveStatus = $lead->archive == 1 ? 0 : 1;
            Lead::where('id', $id)->update(['archive' => $newArchiveStatus]);
            $message = $newArchiveStatus == 1 ? 'Your data is now archived!' : 'Your data is now active!';   
            return back()->with('success', $message);
        }    
        return back()->with('error', 'Lead not found.');
    }    

    public function setAssignToUser(Request $request){       
    }  

    public function checkDuplicate(Request $request){
        if ($request->isMethod('POST')) {           
            $checkUserData = User::where('mobile', $request->val)->exists();            
            return response()->json(['exists' => $checkUserData]);
        }
        return response()->json(['error' => 'Invalid request'], 400);
    }
    
    public function leadFirm(Request $request){
        $firmData = Firm::where('id', '>', 0); // Query Builder instance
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';    
        if ($searchKey != '') {
            $firmData->where('name', 'LIKE', '%' . $searchKey . '%');
        }    
        $firmData = $firmData->latest()->paginate(env("PAGINATION_COUNT"));    
        $header_title_name = 'Manage Firm';    
        if (empty($requestType)) {
            return view('leads.firm', compact('header_title_name', 'firmData', 'searchKey'));
        } else {
            $trData = view('leads.firm-page-filter-data', compact('firmData', 'searchKey'))->render();
            return response()->json(['trData' => $trData]);
        }
    }    

    public function addLeadFirm(Request $request,$id=null){
        if($request->firm_id > 0){
            $firmData = Firm::find($request->firm_id);
        }else{
            $firmData = new Firm();
        }
        if($request->isMethod('post')){
           $firmData->name = $request->firmname;
           $firmData->city = $request->firmcity;
           $firmData->state = $request->firmstate;
           $firmData->zipcode = $request->firmzipcode;

           $firmData->acc_holder_name = $request->account_holder_name;
           $firmData->bank_name = $request->bank_name;
           $firmData->branch_name = $request->branch_name;
           $firmData->account_number = $request->account_number;
           $firmData->ifsc_code = $request->ifsc_code;
           $firmData->swift_code = $request->swift_code;
           $firmData->upi_id = $request->upi_id;

            if($firmData->save()){
                if($request->firm_id > 0){
                    return redirect()->back()->with('success','Succusfully Updated!');
                }else{
                    return redirect()->back()->with('success','Succusfully Inserted!');
                }
            }else{
                return redirect()->back()->with('error','Some error is occured!');
            }
        }
    }

    public function firmStatus($id){
        $firmData = Firm::find($id);
        if($firmData){
            $firmData->status = $firmData->status == 0 ? 1 : 0;
            if($firmData->save()){
                return redirect()->back()->with('success','Status is updated!');
            }else{
                return redirect()->back()->with('error','Some error is occur while updating status!');
            }
        }
    }

    public function leadInvoice(Request $request,$id=null){
        $header_title_name = 'Manage Invoice';
        return view('leads.invoice', compact('header_title_name'));
    }

    public function existedClientDetail(Request $request){
        if($request->isMethod('POST')){
            $existedClientDetails = User::with('userdetail')->where('id',$request->clientId)->first();
            if($existedClientDetails){
                $leadData = new Lead();
                $leadData->user_id = Auth::id();
                $clientName = strtoupper(substr($existedClientDetails->name, 0, 3));
                $randomNumber = rand(10, 99);
                $lastLead = Lead::latest('id')->first();
                $lastLeadId = $lastLead ? $lastLead->id + 1 : 1; 
                $lead_id = $clientName . $randomNumber . $lastLeadId;
                $existingLead = Lead::where('lead_id', $lead_id)->first();
                if ($existingLead) {
                    $lead_id = $clientName . $randomNumber . ($lastLeadId + 1);
                }
                $leadData->lead_id = $lead_id;
                $leadData->client_id = $request->clientId;
                $leadData->source = $existedClientDetails->userdetail->referralPartner ?? 0;
                $leadData->source_id = $existedClientDetails->userdetail->source_type_id ?? 0;
                $leadData->client_name = $existedClientDetails->name;
                $leadData->company_name = $existedClientDetails->companyName;
                $leadData->mobile_number = $existedClientDetails->mobile;
                $leadData->email = $existedClientDetails->email;
                $leadData->business_scope = $existedClientDetails->userdetail->business_scope ?? 0;
                $leadData->msmem = $existedClientDetails->userdetail->msmem ?? 0;
                if($leadData->save()){
                    return redirect()->route('leads.add', ['id' => $leadData->id])->with(compact('existedClientDetails'));
                }
            }else{
                return redirect()->back()->withError('Some errro is occur when getting the client details');
            }
        }
    }
    public function paymentStatus(Request $request){


        $header_title_name = 'Payment Status';
        
        $lead = Lead::all();       
        $payment_details = Payment::with('lead', 'leadTask.services','leadTask.serviceSatge')->where('reference_id', 0);
        if ($request->leadId || $request->dateRange) {
            // Apply the lead_id filter
            if ($request->leadId) {
                $payment_details = $payment_details->where('lead_id' , $request->leadId);
            }
            if (!empty($request->input('dateRange'))) {
   
                $dateRange = $request->dateRange;

                if (strpos($dateRange, ' - ') !== false) {
                    $dateParts = explode(" - ", $dateRange);

                    if (count($dateParts) == 2) {
                        $startDate = date('Y-m-d', strtotime($dateParts[0]));
                        $endDate = date('Y-m-d', strtotime($dateParts[1]));

                        $payment_details = $payment_details->whereDate('created_at', '>=', $startDate)
                                                        ->whereDate('created_at', '<=', $endDate);
                    } 
                }
            }
        }
        $selectedLead = $request->leadId;
        $selectedDate = $request->dateRange;
        $payment_details = $payment_details->paginate(env("PAGINATION_COUNT"));
        return view('leads.payment-details', compact('header_title_name', 'payment_details', 'lead','selectedLead' , 'selectedDate'));
    }

    public function paymentDetails(Request $request){
          if($request->payment_id){
            $data = Payment::with('lead')->where('reference_id', $request->payment_id)->orWhere('id', $request->payment_id)->orderBy('id', 'ASC')->get();
            return response()->json(['data' => $data  , 'status' => 200], 200);
          }else{
            return response()->json(['data' => null , 'status' => 404], 404);
          }
    }
   
}
