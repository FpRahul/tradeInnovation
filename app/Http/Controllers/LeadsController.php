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
            if(auth()->user()->role != 1 && auth()->user()->role != 5){
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
        if($sourceKey != '' || $serviceKey != '' || $statusKey != '' || $searchKey != ''){
                     

            $leadList->when(!empty($request->source), function ($q) use ($request) {
            $q->where('source', $request->source);
            })
            ->when(!empty($request->status), function ($q) use ($request) {
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
            $sourceList = CategoryOption::where('type',3)->where('status',1)->get();
            $serviceList = Service::where('status',1)->get();
            $userList = User::where('role',4)->get();
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
            $LeadTaskDetail = LeadTaskDetail::where('task_id',$LeadTask->id)->first();
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
        $userList = User::where('role',4)->where('status',1)->get();
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
                                $image_name->move(public_path('Image'),$imageName);
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
                if($id==null){
                    $LeadTask = new LeadTask();
                }     
                $LeadTask->user_id = $request->assign;
                $LeadTask->lead_id = $leadData->id;
                $LeadTask->service_stage_id = $request->stage_id;
                $LeadTask->assign_by = auth()->user()->id;
                $LeadTask->task_title = ServiceStages::find($request->stage_id)->title;
                if($LeadTask->save()){
                    if($id==null){
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

    public function getLogs(Request $request){
        
        $rule = [
            'lead_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lead = Lead::find($request->lead_id);

        // foreach($lead->lead_logs as $log){
        //     $a = getTask($log->task_id);
        // }
        
        // $data = LeadLog::where('lead_id', $request->input('lead_id'))->get();
        return response()->json([
            'status'=> 200,
            'lead'=> $lead,
            'logs' => $lead->lead_logs
        ]);
    }

    public function leadLogs(Request $request)
    {
        $leads = Lead::orderBy('created_at', 'DESC')->get();
        $query = LeadLog::orderBy('created_at');

        if (!empty($request->query('lead_id'))) {
            $lead_id = $request->query('lead_id');
            $query->whereLeadId($lead_id);
        }

        $logs = $query->paginate('10');

        if(request()->ajax()){
            return view('leads.logs.list', [
                'logs' => $logs,
                'leads' => $leads
            ]);
        }

        return view('leads.logs.index', [
            'logs' => $logs,
            'leads' => $leads,
            'header_title_name' => 'Lead Logs'
        ]);
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
