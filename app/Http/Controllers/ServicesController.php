<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\SubService;

class ServicesController extends Controller
{
    public function index(Request $request){
        $serviceData = Service::paginate(env("PAGINATION_COUNT"));
        $searchKey = $request->input('key') ?? '';       
        if($request->isMethod('POST')){
            if($request->requestType == 'ajax'){        
                if ($searchKey) {
                    $serviceData = Service::where('serviceName', 'like', "%{$searchKey}%")->paginate(env("PAGINATION_COUNT"));         
                }
                $trData = view('services/service-page-search-data', compact('serviceData','searchKey'))->render();
                $dataArray = [
                    'trData' => $trData,
                ];
                return response()->json($dataArray);
            }     
        }
        $header_title_name = 'Services';
        return view('services.index', compact('serviceData', 'header_title_name','searchKey'));         
    }

    public function addService(Request $request){
        if($request->service_id > 0){
            $serviceData = Service::find($request->service_id);
        }else{
            $serviceData = new Service();
        }
        $serviceData->serviceName = $request->name;
        $serviceData->serviceDescription = $request->description;
        if($serviceData->save()){
            if($request->service_id > 0){
                return redirect()->route('services.index')->withSuccess('Your data is successfully updated!');
            }else{
                return redirect()->route('services.index')->withSuccess('Your data is successfully inserted!');
            }            
        }
        return view('services.index', compact('serviceData'));         
    }

    public function addSubService(Request $request,$id=null){
        if($id > 0){
            $subServiceList = Service::with('subService')->where('id', $id)->get()->toArray();
        }else{
            $subServiceList = [];
        }
        if($request->isMethod('POST')){
          
          foreach($request->subservice as $subServices){
            if($subServices['subservice_id'] > 0){
                $subServiceList = SubService::where('id', $subServices['subservice_id'])->first();
            }else{
                $subServiceList = new SubService();                
            }
            $subServiceList->serviceId = $id;
            $subServiceList->subServiceName = $subServices['subservice'];
            $subServiceList->save();

          }
          return redirect()->route('services.index')->withSuccess('Your data is successfully updated!');
        }
        $header_title_name="Service";
        return view('services/subservice',compact('subServiceList','header_title_name'));
    }

    public function serviceStatus(Request $request,$id=null){
       $serviceData = Service::find($id);
       if($request->val){
        $status = 0;
       }else{
        $status = 1;
       }
       $serviceData->status = $status;
       if($serviceData->save()){
        return redirect()->back()->with('success','Status is successfully updated!');
       }else{
        return redirect()->back()->with('error','Some error is occur!');
       }
    }
   
    public function deleteRepeaterSubserv(Request $request){
        $subServiceDel = SubService::where('id',$request->id);
        if($subServiceDel->delete()){
            echo "1";
        }else{
            echo "0";
        }
    }
}
