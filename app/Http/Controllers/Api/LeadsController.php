<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Lead;
use App\Models\LeadService;
use App\Models\User;

class LeadsController extends Controller
{
    public function captureLead(Request $request){
        // Log::info($request->all());
        // return response()->json(['status' => 'success', 'message' => 'hello']);
        // dd($request->all());
        try {
            $projectManager = User::where('role',4)->where('status',1)->get()->first();
            $formData = $request->all();
            
            //Lead Unique Id
            $clientName = strtoupper(substr($formData['mf_first_name'], 0, 3));
            $randomNumber = rand(10, 99);
            $lastLead = Lead::latest('id')->first();
            $lastLeadId = $lastLead ? $lastLead->id + 1 : 1; 
            $lead_id = $clientName . $randomNumber . $lastLeadId;
            //End

            $leadData = new Lead();
            $leadData->user_id = $projectManager->id;
            $leadData->lead_id = $lead_id;
            $leadData->source = 20;
            $leadData->source_id = 0;
            $leadData->client_name = $formData['mf_first_name'];
            $leadData->mobile_number = $formData['mf_telephone'];
            $leadData->email = $formData['mf_email'];
            $leadData->description = $formData['mf_comment'];
            $leadData->save();            
            
            return response()->json(['status' => 'success', 'message' => 'Form submitted successfully']);
        } catch (\Exception $e) {
            Log::error($e);
            return $e->getMessage();
            return response()->json(['status' => 'error', 'message' => 'Error processing form submission'], 500);
        }
    }
}
