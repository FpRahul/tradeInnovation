<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Lead;

class LeadsController extends Controller
{
    public function captureLead(Request $request){
        try {
            $formData = $request->all(); 
            $leadData = new Lead();
            $leadData->user_id = 4;
            $leadData->source = 14;
            $leadData->source_id = 0;
            $leadData->client_name = $formData['clientname'];
            $leadData->company_name = $formData['companyname'];
            $leadData->mobile_number = $formData['mobilenumber'];
            $leadData->email = $formData['email'];
            $leadData->description = $formData['description'];
            $leadData->save();
            return response()->json(['status' => 'success', 'message' => 'Form submitted successfully']);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['status' => 'error', 'message' => 'Error processing form submission'], 500);
        }
    }
}
