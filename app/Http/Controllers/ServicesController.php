<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\SubService;
use App\Models\ServiceStages;
use App\View\Components\LogActivity;

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

        $clientIP = \Request::ip();
        
        $userAgent = \Request::header('User-Agent');
     

    $operatingSystem = getOperatingSystem($userAgent);
        if($request->service_id > 0){
            $logAct = 'Update';
            $serviceData = Service::find($request->service_id);
        }else{
            $logAct = 'Add';
            $serviceData = new Service();
        }
        $serviceData->serviceName = $request->name;
        $serviceData->serviceDescription = $request->description;
        if($serviceData->save()){
            $logActivity[] = [
                'user_id' => auth()->user()->id,
                'title' => 'Add/Edit Services',
                'description' => auth()->user()->name .' '. $logAct .' '. $serviceData->serviceName .' '. ' (' . $serviceData->id . ')',
                'created_at' => date('Y-m-d H:i:s'),
                'ip_address' => $clientIP,
                'operating_system' => $operatingSystem
            ];
            $logActivity = new LogActivity($logActivity);
            $logActivity->log();
            if($request->service_id > 0){
                return redirect()->route('services.index')->withSuccess('Your data is successfully updated!');
            }else{
                return redirect()->route('services.index')->withSuccess('Your data is successfully inserted!');
            }            
        }
        return view('services.index', compact('serviceData'));         
    }

    public function addSubService(Request $request,$id=null)
    {
        $clientIP = \Request::ip();
        
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        if($id > 0){
            $logAct = 'Update';
            $subServiceList = Service::with('subService')->where('id', $id)->get()->toArray();
        }else{
            $logAct = 'Add';
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
          $logActivity[] = [
            'user_id' => auth()->user()->id,
            'title' => 'Add/Edit SubServices',
            'description' => auth()->user()->name .' '. $logAct . $subServiceList->subServiceName .' '.' (' . $subServiceList->id . ')',
            'created_at' => date('Y-m-d H:i:s'),
            'ip_address' => $clientIP,
            'operating_system' => $operatingSystem
        ];
        $logActivity = new LogActivity($logActivity);
        $logActivity->log();
          return redirect()->route('services.index')->withSuccess('Your data is successfully updated!');
        }
        $header_title_name="Service";
        return view('services/subservice',compact('subServiceList','header_title_name'));
    }

    public function serviceStatus(Request $request,$id=null){
        $clientIP = \Request::ip();
        
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        $logAct = 'Status Change';
       $serviceData = Service::find($id);
       if($request->val){
        $status = 0;
       }else{
        $status = 1;
       }
       $serviceData->status = $status;
       if($serviceData->save()){
        $logActivity[] = [
            'user_id' => auth()->user()->id,
            'title' => 'Update Service Status',
            'description' => auth()->user()->name .' '. $logAct .' '. $serviceData->serviceName .' '. ' (' . $serviceData->id . ')',
            'created_at' => date('Y-m-d H:i:s'),
            'ip_address' => $clientIP,
            'operating_system' => $operatingSystem
        ];
        $logActivity = new LogActivity($logActivity);
        $logActivity->log();
        return redirect()->back()->with('success','Status is successfully updated!');
       }else{
        return redirect()->back()->with('error','Some error is occur!');
       }
    }
   
    public function deleteRepeaterSubserv(Request $request){
        $subServiceDel = SubService::where('id',$request->id);
        $clientIP = \Request::ip();
        
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        if($subServiceDel->delete()){
           $logActivity[] = [
                'user_id' => auth()->user()->id,
                'title' => 'Archive SUbServices',
                'description' => auth()->user()->name . ' '. ' has deleted SubServices '.' '. $subServiceDel->subServiceName .' '.' (' . $subServiceDel->id . ')',
                'created_at' => date('Y-m-d H:i:s'),
                'ip_address' => $clientIP,
                'operating_system' => $operatingSystem
            ];
            $logActivity = new LogActivity($logActivity);
            $logActivity->log();
        }else{
            echo "0";
        }
    }

    public function serviceStages(Request $request){
        $allStages = ServiceStages::where('service_id',$request->serviceId)->get();
        return response()->json(['data'=>$allStages]);
    }
}
