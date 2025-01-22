<?php

namespace App\Http\Controllers;
use App\Models\CategoryOption;
use App\Models\Service;
use App\Models\SubService;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\LeadService;

class LeadsController extends Controller
{
    public function index(){
        $leadList = Lead::with('leadService')->where('archive',1);
        $leadList = $leadList->paginate(env("PAGINATION_COUNT"));
        $sourceList = CategoryOption::where('type',3)->where('status',1)->get();
        $serviceList = Service::where('status',1)->get();

        $header_title_name = 'Leads';
        return view('leads/index',compact('header_title_name','leadList','sourceList','serviceList'));
    }

    public function add(Request $request, $id = null){
        if($id > 0){
            $leadData = Lead::where('id',$id)->first();
            $leadServiceData = LeadService::where('lead_id',$id)->get();
            $successMsg = 'Your lead is succussfully updated!';
        }else{
            $leadData = [];
            $leadServiceData =[];
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
                if(!empty($request->leadRepeater)){
                    foreach($request->leadRepeater as $serviceKey => $serviceVal){
                        if($serviceVal['lead_id'] > 0){
                            $leadServiceData = LeadService::where('id',$serviceVal['lead_id'])->first();
                        }else{
                            $leadServiceData = new LeadService();
                        }                        
                        $leadServiceData->lead_id = $leadData->id;
                        $leadServiceData->service_id = $serviceVal['serviceid'];
                        $leadServiceData->subservice_id = $serviceVal['subserviceid'];                     
                        $leadServiceData->save();
                    }                 
                }                
                return redirect()->route('leads.index')->withSuccess($successMsg);
            }
        }
        $header_title_name = 'Lead';
        return view('leads/add',compact('header_title_name','sourceList','serviceList','userList','leadData','leadServiceData'));
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
}
