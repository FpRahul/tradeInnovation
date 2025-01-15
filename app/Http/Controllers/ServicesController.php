<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\SubService;

class ServicesController extends Controller
{
    public function index(Request $request){
        $serviceData = Service::paginate(2);
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if ($searchKey) {
            $serviceData->where(function ($query) use ($searchKey) {
                $query->where('name', 'LIKE', $searchKey . '%')
                    ->orWhere('mobile', 'LIKE', $searchKey . '%');
            });
        }
        if($request->isMethod('POST')){
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
        if (empty($requestType)) {    
            $header_title_name = 'Services';
            $moduleName="Manage Services";
            return view('services.index', compact('serviceData', 'header_title_name', 'moduleName','searchKey'));       
        }else{
            $trData = view('users/service-page-search-data', compact('serviceData','searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
        }
        
    }

    public function add(){
        $header_title_name="Service";
        $moduleName="Create Service";
        return view('services/add',compact('header_title_name','moduleName'));
    }

    public function serviceDelete($id=null){
        dd($id);
    }
}
