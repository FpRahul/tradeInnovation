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
        $header_title_name = 'Leads';
        $moduleName = "Manage Leads";
        return view('leads/index',compact('header_title_name','moduleName'));
    }

    public function add(Request $request,$id=null){
        if($id > 0){
            $leadData = Lead::find($id)->get();
            $leadServiceData = LeadService::where('service_id',$id)->get();
        }else{
            $leadData = new Lead();
            $leadServiceData = new LeadService();
        }
        $sourceList = CategoryOption::where('type',3)->where('status',1)->get();
        $serviceList = Service::where('status',1)->get();
        $userList = User::where('role',2)->where('status',1)->get();
        if($request->isMethod('POST')){            
            // $leadData-> = 
        }
        $header_title_name = 'Lead';
        $moduleName = "Add Leads";
        return view('leads/add',compact('header_title_name','moduleName','sourceList','serviceList','userList'));
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
        $moduleName = "Quote";
        return view('leads/sendquote', compact('header_title_name', 'moduleName'));
    }
    public function leadLogs()
    {
        $header_title_name = 'Lead';
        $moduleName = "Logs";
        return view('leads/logs', compact('header_title_name', 'moduleName'));
    }
}
