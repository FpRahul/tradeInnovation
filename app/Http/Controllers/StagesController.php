<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\log;



class StagesController extends Controller
{
    public function index(){
        $header_title_name = 'Stages';
        $services = Stage::select('id', 'parent_id', 'name', 'child_id')->get();
        return view('settings.stages.stages', compact('header_title_name','services'));
    }

    public function create(Request $request){
        $highestParentId = Stage::select('parent_id')->orderBy('parent_id','desc')->first();
        $newParentID = $highestParentId->parent_id + 1;
        
        $rule = [
            'services' => 'required',
            'name' => 'required',
            'description' => 'required',
            'moveable' => 'required',
        ];
        $validator = Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json(['success' => false,'errors' => $validator->errors(), ], 400);  
        }

        $existdata = Stage::select('id', 'parent_id', 'name', 'child_id')->where('id',$request->input('services'))->first();
        
        $newStage = new Stage();
        $newStage->parent_id = $newParentID;
        $newStage->name =  $request->name;
        $newStage->child_id = $existdata->parent_id;
        $newStage->description = $request->description;
        $newStage->is_move = $request->moveable;
        
        if($newStage){
            $newStage->save();
            return response()->json(['message' => 'success' , 'status' => 200],200);
        }else{
            return response()->json(['message' => 'error' , 'status' => 400],400);
        }


        


    }
}
