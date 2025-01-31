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
use App\Models\LeadAssign;
use App\Models\LeadLog;
use App\Models\LeadNotification;
use App\Models\LeadTask;
use App\Models\LeadTaskDetail;

class LeadsController extends Controller
{
    public function index(Request $request){   
            
        if(base64_decode($request->id) > 0){
            $baseNotifyId = base64_decode($request->NotifyId);
            $notifyData = LeadNotification::where('id',$baseNotifyId)->update(['status'=>1]);
            $baseId = base64_decode($request->id);
            $leadList = Lead::with('leadService')->where('id',$baseId)->where('archive',1);
        }else{            
            if(auth()->user()->role != 1 && auth()->user()->role != 5){
                $leadList = Lead::with('leadService')->where('assign_to',auth()->user()->id)->where('archive',1);
            }else{
                $leadList = Lead::with('leadService')->where('archive',1);
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
        
        $leadList = $leadList->paginate(env("PAGINATION_COUNT"));
        if(empty($requestType)){
            $sourceList = CategoryOption::where('type',3)->where('status',1)->get();
            $serviceList = Service::where('status',1)->get();
            $userList = User::where('role',4)->get();
            $header_title_name = 'Leads';
            return view('leads/index',compact('header_title_name','leadList','sourceList','serviceList','userList','sourceKey','serviceKey','statusKey'));
        }else{
            $trData = view('leads/lead-page-filter-data',compact('leadList'))->render();
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
            $leadServiceData = LeadService::where('lead_id',$id)->get();
            $leadAttachment = LeadAttachment::where('lead_id',$id)->get();
            $leadAssign = LeadAssign::where('lead_id',$id)->first();
            $LeadLog = LeadLog::where('lead_id',$id)->first();
            $successMsg = 'Your lead is succussfully updated!';
        }else{
            $leadData = [];
            $leadServiceData =[];
            $leadAttachment = [];
            $leadAssign = [];
            $LeadLog = [];
            $successMsg = 'Your lead is succussfully inserted!';
        }
        $sourceList = CategoryOption::where('type',3)->where('status',1)->get();
        $serviceList = Service::where('status',1)->get();
        $userList = User::where('role','>',3)->where('status',1)->get();
        if($request->isMethod('POST')){ 
            if($id == '' || $id == 0){
                $leadData = new Lead();
            }
            if($request->sourcetypenamelist > 0){
                $sourceId = $request->sourcetypenamelist;              
            }else{
                $sourceId = 0;
            }
            $leadData->user_id = auth()->user()->id;
            $leadData->source = $request->source;
            $leadData->source_id = $sourceId;
            $leadData->client_name = $request->clientname;
            $leadData->company_name = $request->companyname;
            $leadData->mobile_number = $request->mobilenumber;
            $leadData->email = $request->email;
            $leadData->assign_to = $request->assign;
            $leadData->description = $request->description;
            
            if($leadData->save()){
                $serviceidArray = [];
                // lead attachment repeater...         
                if(!empty($request->leadAttachment)){
                    foreach($request->leadAttachment as $attachmentKey => $attachmentVal){
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
                // lead service repeater...
                if(!empty($request->leadRepeater)){
                    foreach($request->leadRepeater as $serviceKey => $serviceVal){
                        if($serviceVal['lead_id'] > 0){
                            $leadServiceData = LeadService::where('id',$serviceVal['lead_id'])->first();
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
                // lead assign to user...
                if(!empty($leadAssign)){
                    $leadAssign->user_id = $request->assign;
                    $leadAssign->lead_id = $leadData->id;
                    $leadAssign->assign_by = auth()->user()->id;
                    $leadAssign->description = $request->taskdescription;
                    $leadAssign->dead_line = date('Y-m-d',strtotime($request->taskdeadline));
                    $leadAssign->save();
                }else{
                    $leadAssign = new LeadAssign();
                    $leadAssign->user_id = $request->assign;
                    $leadAssign->lead_id = $leadData->id;
                    $leadAssign->assign_by = auth()->user()->id;
                    $leadAssign->description = $request->taskdescription;
                    $leadAssign->dead_line = date('Y-m-d',strtotime($request->taskdeadline));
                    $leadAssign->save();
                }               

                // lead task...
                $LeadTask = new LeadTask();
                $LeadTask->user_id = $request->assign;
                $LeadTask->lead_id = $leadData->id;
                $LeadTask->assign_by = auth()->user()->id;
                $LeadTask->task_title = '';
                $LeadTask->task_description = $request->taskdescription;
                if($LeadTask->save()){

                    $LeadTaskDetail = new LeadTaskDetail();
                    $LeadTaskDetail->task_id = $LeadTask->id;
                    $LeadTaskDetail->dead_line = date('Y-m-d',strtotime($request->taskdeadline));
                    $LeadTaskDetail->status = 0;
                    $LeadTaskDetail->status_date = date('Y-m-d');
                     $LeadTaskDetail->save();
                    // lead logs...
                    $LeadLog = new LeadLog();
                    $LeadLog->user_id = $request->assign;
                    $LeadLog->lead_id =  $leadData->id;
                    $LeadLog->task_is = $LeadTask->id;
                    $LeadLog->assign_by = auth()->user()->id;
                    $LeadLog->description = '';
                    $LeadLog->save();
                    // lead notification...
                    $serviceNames = '';
                    if(!empty($serviceidArray)){                        
                        $serviceNames = Service::whereIn('id', $serviceidArray)->get()->pluck('serviceName')->implode(', ');                        
                    }
                    $LeadNotification = new LeadNotification();
                    $LeadNotification->user_id = $request->assign;
                    $LeadNotification->lead_id = $leadData->id;
                    $LeadNotification->task_id = 0;
                    $LeadNotification->title = 'Lead Assigned';
                    $LeadNotification->description = 'New lead is assigned to you by '.auth()->user()->name.' for '.$serviceNames;
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
               
                    return redirect()->route('leads.index')->withSuccess($successMsg);
            }
        }
        $header_title_name = 'Lead';
        return view('leads/add',compact('header_title_name','sourceList','serviceList','userList','leadData','leadServiceData','leadAttachment'));
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

    public function leadLogs(){
        $header_title_name = 'Lead';
        return view('leads/logs', compact('header_title_name'));
    }

    public function getSourceTypeName(Request $request){
        if($request->value == 14){
            $value = 3;
        }elseif($request->value == 15){
            $value = 4;
        }elseif($request->value == 19){
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
        // $leadServiceDel = LeadService::where('id',$request->id);
        // if($leadServiceDel->delete()){
        //     echo "1";
        // }else{
        //     echo "0";
        // }
    }

    public function archiveLead(Request $request,$id=null){
      $leadData = Lead::where('id',$id)->first();
      $leadData->archive = 0;
      if($leadData->save()){
        return back()->with('success','Now your data is in archived!');
      }
    }

    public function setAssignToUser(Request $request){
        $assignData = new LeadAssign();
        $assignData->user_id = $request->selectuser;
        $assignData->lead_id = $request->lead_id;
        $assignData->assign_by = $request->assign_by;
        $assignData->description = $request->description;
        $assignData->dead_line = date('Y-m-d',strtotime($request->deadline));
        if($assignData->save()){
            return back()->with('success','Your lead successfully assigned!');
        }
    }
   
}
