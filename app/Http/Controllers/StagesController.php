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
        // dd($request);
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
            return redirect()->back()->withErrors($validator)->withInput();  
        }
        $existdata = Stage::select('id', 'parent_id', 'name', 'child_id')->where('id',$request->input('services'))->first();
        $newStage = new Stage();
        $newStage->parent_id = $newParentID;
        $newStage->name =  $request->name;
        $newStage->child_id = $existdata->parent_id;
        $newStage->description = $request->description;
        $newStage->is_move = $request->moveable;
        if ($newStage) {
            $newStage->save();
            return redirect()->route('dashboard');
        } else {
            // Optionally, you can return an error message or a redirect to another route
            return redirect()->back()->with('error', 'Stage not created.');
        }
        
        
    }
}
