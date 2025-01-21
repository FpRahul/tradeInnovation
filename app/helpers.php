<?php

use App\Models\Service;
use App\Models\CategoryOption;
use App\Models\User;
use App\Models\SubService;

if (!function_exists('getServiceData')) {
    function getServiceData($serviceDetails)
    {
        $serviceArray = [];
        foreach ($serviceDetails as $serviceKey => $serviceValue){
            $serviceData = Service::where('id',$serviceValue['service_id'])->first();
            $serviceArray[] = $serviceData;
        }
        return $serviceArray;
    }
}
if(!function_exists('getSourceData')){
    function getSourceData($sourceId){
        $sourceData = CategoryOption::where('id',$sourceId)->first();
        return $sourceData;
    }
}
if(!function_exists('getSourceTypeName')){
    function getSourceTypeName($sourceTypeId){
        
        if($sourceTypeId == 14){
            $value = 3;
        }elseif($sourceTypeId == 15){
            $value = 4;
        }elseif($sourceTypeId == 19){
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