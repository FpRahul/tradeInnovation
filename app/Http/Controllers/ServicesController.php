<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\SubService;

class ServicesController extends Controller
{
    public function index(Request $request){
        $serviceData = Service::paginate(2);
        $searchKey='';
        // $searchKey = $request->input('key') ?? '';
        // $requestType = $request->input('requestType') ?? '';
        // if ($searchKey) {
        //     $serviceData->where(function ($query) use ($searchKey) {
        //         $query->where('serviceName', 'LIKE', $searchKey . '%');
        //     });
        // }
        if($request->isMethod('POST')){
            if($request->requestType == 'ajax'){    // search filter
                $searchKey = $request->input('key') ?? '';
                if ($searchKey) {
                    $serviceData = Service::where('serviceName', 'like', "%{$searchKey}%")->get();         
                }
            }else{                                  // form submittion
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
            }
           

        }
       
        if (empty($requestType)) {    
            $header_title_name = 'Services';
            $moduleName="Manage Services";
            return view('services.index', compact('serviceData', 'header_title_name', 'moduleName','searchKey'));       
        }else{
            $trData = view('services/service-page-search-data.blade', compact('serviceData','searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
        }
        
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
        $moduleName="Sub Service";
        return view('services/subservice',compact('subServiceList','header_title_name','moduleName'));
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
   
}
