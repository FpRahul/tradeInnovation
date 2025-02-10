<?php
namespace App\Http\Controllers;
use App\Models\CategoryOption;
use App\Models\Service;
use App\Models\SubService;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\LeadService;
use App\Models\LeadAttachment;
use App\Models\LeadLog;
use App\Models\FollowUp;
use App\Models\LeadNotification;
use App\Models\LeadTask;
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
            $leadList = Lead::with(['leadService','leadTask'])->where('id',$baseId);
        }else{            
            if(auth()->user()->role != 1 && auth()->user()->role != 4){
                $leadList = Lead::with(['leadService','leadTask'])->where('archive',$request->tab);
            }else{
                $leadList = Lead::with(['leadService','leadTask'])->where('archive',$request->tab);
            }           
        }

        $sourceKey = $request->input('source') ?? '';
        $serviceKey = $request->input('service') ?? '';
        $statusKey = $request->input('status') ?? '';
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        // dd($request);
        if($sourceKey != '' || $serviceKey != '' || $statusKey != '' || $searchKey != ''){
                     

            $leadList->when(!empty($request->source), function ($q) use ($request) {
            $q->where('source', $request->source);
            })
            ->when(isset($request->status), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when(!empty($request->service), function ($q) use ($request) {
                $q->whereHas('leadService', function ($q) use ($request) {
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
            
            $serviceList = Lead::with(['leadService.service'])
            ->whereHas('leadService')
            ->where('user_id', auth()->user()->id)
            ->get()
            ->pluck('leadService')
            ->flatten()
            ->pluck('service');

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
            $leadOldData = Lead::where('id',$id)->first();
            $leadServiceData = LeadService::where('lead_id',$id)->get();
            $leadAttachment = LeadAttachment::where('lead_id',$id)->get();
            $LeadLog = LeadLog::where('lead_id',$id)->first();
            $LeadTask = LeadTask::where('lead_id',$id)->first();
            $leadTaskId = $LeadTask->id ?? 0;
            $LeadTaskDetail = LeadTaskDetail::where('task_id',$leadTaskId)->first();
            $leadSelectedStage = LeadTask::where('lead_id',$id)->first();
            $leadStages = ServiceStages::where('service_id',$leadServiceData[0]->service_id)->get();
            $successMsg = 'Lead updated!';
        }else{
            $leadData = new Lead();
            $leadOldData =new Lead();
            $leadServiceData =[];
            $leadAttachment = [];
            $LeadLog = [];
            $LeadTask = [];
            $LeadTaskDetail = [];
            $leadStages = [];
            $leadSelectedStage = [];
            $successMsg = 'Lead added!';
        }
        // $allStages = ServiceStages::get();
        $sourceList = CategoryOption::where('type',3)->where('status',1)->get();
        $serviceList = Service::where('status',1)->get();
        $userList = User::where('role','>=',5)->where('status',1)->get();
        if($request->isMethod('POST')){
            if($request->sourcetypenamelist > 0){
                $sourceId = $request->sourcetypenamelist;              
            }else{
                $sourceId = 0;
            }
            $leadData->user_id = auth()->user()->id;
            $clientName = strtoupper(substr($request->input('clientname'), 0, 3));
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
            $leadData->status = $request->savetype;
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
                        if($serviceVal['lead_service_id'] > 0){
                            $leadServiceData = LeadService::where('id',$serviceVal['lead_service_id'])->first();
                        }else{
                            $leadServiceData = new LeadService();
                        }            
                        $leadServiceData->lead_id = $leadData->id;                            
                        $leadServiceData->service_id = $serviceVal['serviceid'];
                        $serviceidArray[] = $serviceVal['serviceid'];
                        $leadServiceData->subservice_id = $serviceVal['subserviceid'];                   
                        $leadServiceData->save();
                    }                 
                }   
                if($id==null || empty($LeadTask)){
                    $LeadTask = new LeadTask();
                }     
                $LeadTask->user_id = $request->assign;
                $LeadTask->lead_id = $leadData->id;
                $LeadTask->service_stage_id = $request->stage_id;
                $LeadTask->assign_by = auth()->user()->id;
                $LeadTask->task_title = ServiceStages::find($request->stage_id)->title;
                if($LeadTask->save()){
                    if($id==null || empty($LeadTaskDetail)){
                        $LeadTaskDetail = new LeadTaskDetail();
                    }
                    $LeadTaskDetail->task_id = $LeadTask->id;
                    $LeadTaskDetail->dead_line = date('Y-m-d',strtotime($request->taskdeadline));
                    $LeadTaskDetail->status = 0;
                    $LeadTaskDetail->status_date = NULL;
                    $LeadTaskDetail->save();
                    $followUp = new FollowUp();
                    $followUp->task_id = $LeadTask->id;
                    $followUp->service_id = $leadServiceData->service_id;
                    $followUp->stage_id = $LeadTask->service_stage_id;
                    $followUp->save(); 

                    if($request->savetype == 1 && $leadOldData->status==0){
                        // lead logs...
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id = $request->assign;
                        $LeadLog->lead_id =  $leadData->id;
                        $LeadLog->task_id = $LeadTask->id;
                        $LeadLog->assign_by = auth()->user()->id;
                        $LeadLog->description = 'Lead added to the system';
                        $LeadLog->save();

                        $LeadLog = new LeadLog();
                        $LeadLog->user_id = $request->assign;
                        $LeadLog->lead_id =  $leadData->id;
                        $LeadLog->task_id = $LeadTask->id;
                        $LeadLog->assign_by = auth()->user()->id;
                        $LeadLog->description = 'Lead assigned to the user';
                        $LeadLog->save();
                    
                        // lead notification...
                        $serviceNames = '';
                        if(!empty($serviceidArray)){                        
                            $serviceNames = Service::whereIn('id', $serviceidArray)->get()->pluck('serviceName')->implode(', ');                        
                        }
                        
                        $LeadNotification = new LeadNotification();
                        $LeadNotification->user_id = $request->assign;
                        $LeadNotification->lead_id = $leadData->id;
                        $LeadNotification->task_id = $LeadTask->id;
                        $LeadNotification->title = 'Lead Assigned';
                        $LeadNotification->description = 'New lead is assigned to you for '.$serviceNames;
                        $LeadNotification->status = 0;
                        $LeadNotification->save();

                        $LeadNotification = new LeadNotification();
                        $LeadNotification->user_id = $request->assign;
                        $LeadNotification->task_id = $LeadTask->id;
                        $LeadNotification->lead_id = $leadData->id;
                        $LeadNotification->title = 'Task Assigned';
                        $LeadNotification->description = 'New task assigned - '.$LeadTask->task_title;
                        $LeadNotification->status = 0;
                        $LeadNotification->save();
                    }           
                }  
                return redirect()->route("leads.index", ['tab' => 1])->withSuccess($successMsg);
            }
        }
        $header_title_name = 'Lead';
        return view('leads/add',compact('header_title_name','sourceList','serviceList','userList','leadData','leadServiceData','leadAttachment','leadStages','LeadTask','LeadTaskDetail','leadSelectedStage'));
    }
    // lead fetch...........
    public function leadFetch(Request $request){
        $leadData = Lead::with('leadAttachments')->find($request->id);
        return response()->json([
            'data' =>$leadData
           ]);
    }

    public function edit(Request $request){       
        $leadData = Lead::find($request->lead_id);
        if ($leadData) {
            $leadData->update([
                'client_name' => $request->modalclientname,
                'company_name' => $request->modalcompanyname,
                'mobile_number' => $request->modalmobilenumber,
                'email' => $request->modalemail,
                'description' => $request->modaldescription
            ]);
            // if ($request->hasFile('modalfileattachment')) {
            //     $image_name = $request->modalfileattachment;
            //     $imageName = rand(100000, 999999) . '.' . $image_name->getClientOriginalExtension();
            //     $image_name->move(public_path('uploads/leads/'.$newUser->id), $imageName);
            //     $newUserDetails->uploadPhotograph = $imageName;
            // }
        }
        
        if($leadData->save()){
            // if(isset($request->modalfileattachment) && $request->modalfileattachment != null){
                // foreach($request->modalfileattachment as $key => $val){
                //     if (isset($val['attachmentFile']) && $val['attachmentFile'] instanceof \Illuminate\Http\UploadedFile) {
                //         $leadAttachment = new LeadAttachment();
                //         $leadAttachment->lead->id = $leadData->id;
                //         $leadAttachment->document = $val
                //     }
                // }
            // }
            // LeadAttachment
            return redirect()->back()->with('success','Lead updated!');
        }else{
            return redirect()->back()->with('error','Some error is occur!');
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
        $leadLogs = LeadLog::with('leadTask','leadTask.leadTaskDetails', 'leadTask.serviceSatge', 'leadService.service')->get();
        if($request->lead_id > 0){
            $leadLogs = LeadLog::with('leadTask','leadTask.leadTaskDetails', 'leadTask.serviceSatge', 'leadService.service')->where('lead_id', $request->lead_id) ->orderBy('created_at', 'desc')->get();
        }
        return view('leads.logs', compact('leadData', 'header_title_name','leadLogs','requestParams'));
    }

    public function getLogs(Request $request){
        if($request->lead_id > 0){
            $lead_id = $request->lead_id; 
            $leadLogs = LeadLog::with('leadTask','lead','leadTask.user','leadTask.leadTaskDetails', 'leadTask.serviceSatge', 'leadService.service')->where('id', $request->lead_id)->get();
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
                foreach ($lead->leadService as $services) {
                    $data['services'][] = $services->service->serviceName;  
                }
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
        }elseif($request->value == 19){
            $value = 4;
        }elseif($request->value == 17){
            //CLIENT
            $value = 2;
        }
        $userData = User::where('role',$value)->get();
        $options = '<option value="">Select</option>';
        foreach($userData as $k => $v){
            $options .="<option value=\"{$v['id']}\">{$v['name']}</option>";
        }
        return response()->json([
            'data'=>$options
        ]);
    }

    public function deleteRepeaterLead(Request $request){
        $leadServiceDel = LeadService::where('id',$request->id);
        if($leadServiceDel->delete()){
            echo "1";
        }else{
            echo "0";
        }
    }

    public function deleteAttachmentRepeaterLead(Request $request){
        $leadServiceDel = LeadAttachment::where('id',$request->id);
        if($leadServiceDel->delete()){
            echo "1";
        }else{
            echo "0";
        }
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

   
}
