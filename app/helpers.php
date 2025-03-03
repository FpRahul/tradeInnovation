<?php

use App\Models\Service;
use App\Models\CategoryOption;
use App\Models\User;
use App\Models\Log;
use App\Models\LeadTask;
use App\Models\ServiceStages;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\SubService;
use Termwind\Components\Raw;

if (!function_exists('getServiceData')) {
    function getServiceData($serviceDetails)
    {
        $serviceArray = [];
        foreach ($serviceDetails as $serviceKey => $serviceValue){
            $serviceData = Service::where('id',$serviceValue->service_id)->first();
            $serviceArray[] = $serviceData->serviceName ?? 'N/A';
        }
        return implode(',',$serviceArray);
    }
}
if(!function_exists('getSourceData')){
    function getSourceData($sourceId){
        $sourceData = CategoryOption::where('id',$sourceId)->first();
        // dd($sourceData);
        return $sourceData;
    }
}
if(!function_exists('getSourceTypeName')){
    function getSourceTypeName($sourceTypeId){
        $value='0';
        if($sourceTypeId == 18){
            $value = 3;
        }elseif($sourceTypeId == 19){
            $value = 4;
        }elseif($sourceTypeId == 17){
            $value = 2;
        }
        return User::where('role',$value)->get();
    }
}
if(!function_exists('getUserSourceTypeName')){
    function getUserSourceTypeName($sourceTypeId){
        $value='0';
        if($sourceTypeId == 18){
            $value = 3;
        }elseif($sourceTypeId == 19){
            $value = 4;
        }elseif($sourceTypeId == 17){
            $value = 2;
        }
        return User::where('role',$value)->get();
    }
}
if(!function_exists('getSubService')){
    function getSubService($serviceId){
        return SubService::where('serviceId',$serviceId)->get();
    }
}

if (!function_exists('getOperatingSystem')) {
    function getOperatingSystem($userAgent)
    {
        $osArray = [
            'Windows' => 'Windows',
            'Mac' => 'Mac',
            'Linux' => 'Linux',
            'Android' => 'Android',
            'iPhone' => 'iPhone',
            'iPad' => 'iPad',
            'Unix' => 'Unix',
        ];

        foreach ($osArray as $key => $os) {
            if (stripos($userAgent, $key) !== false) {
                return $os;
            }
        }

        return 'Unknown';
    }


}
if(!function_exists('getUserNameBySource')){
    function getUserNameBySource($userId){
        $userData = User::find($userId);
        return $userData;
    }
}

if(!function_exists('getStageData')){
    function getStageData($taskLeadData){        
        $stageData = ServiceStages::where('id',$taskLeadData->service_stage_id)->first()->title;        
        $serviceData = Service::where('id',$taskLeadData->service_id)->first()->serviceName;
        return $serviceData.'-'.$stageData;
    }
}

if(!function_exists('getTotalClientCount')){
    function getTotalClientCount($assocId){
        $clientCount = UserDetail::where('referralPartner',18)->where('source_type_id',$assocId)->count();
        return $clientCount;
    }
}

if(!function_exists('getScopeOfBusinessData')){
    function getScopeOfBusinessData($scopeId){
        $scopeNames = []; // Array to store names
        $scopeData = CategoryOption::whereIn('id', explode(',', $scopeId))
                    ->where('type', 4)
                    ->get();    
        foreach ($scopeData as $val) {
            $scopeNames[] = $val['name']; 
        }    
        return implode(', ', $scopeNames); 
    }
    
}
// function getSubStage($serviceID , $stageId){
   
//     $data =  ServiceStages::select('stage_id')->where('service_id', $serviceID)->where('sub_stage_id', $stageId)->first();
//     return $data;
// }