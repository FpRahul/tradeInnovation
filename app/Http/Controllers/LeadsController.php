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
        $leadList = Lead::with('leadService');
        $leadList = $leadList->paginate(env("PAGINATION_COUNT"));
        // dd($leadList);
        $header_title_name = 'Leads';
        return view('leads/index',compact('header_title_name','leadList'));
    }

    public function add(Request $request,$id=null){
        if($id > 0){
            $leadData = Lead::where('id',$id)->get();
            $leadServiceData = LeadService::where('lead_id',$id)->get();
            $successMsg = 'Your lead is succussfully updated!';
        }else{
            $leadData = new Lead();
            $leadServiceData = new LeadService();
            $successMsg = 'Your lead is succussfully inserted!';
        }
        $sourceList = CategoryOption::where('type',3)->where('status',1)->get();
        $serviceList = Service::where('status',1)->get();
        $userList = User::where('role','>',3)->where('status',1)->get();
        if($request->isMethod('POST')){ 
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
                            $leadServiceData1 = LeadService::where('id',$serviceVal['lead_id'])->first();
                        }else{
                            $leadServiceData1 = new LeadService();
                        }
                        
                        $leadServiceData1->lead_id = $leadData->id;
                        $leadServiceData1->service_id = $serviceVal['serviceid'];
                        $leadServiceData1->subservice_id = $serviceVal['subserviceid'];
                     
                        $leadServiceData1->save();
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
}
