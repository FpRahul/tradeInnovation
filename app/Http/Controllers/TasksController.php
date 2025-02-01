<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadTask;
use App\Models\LeadTaskDetail;
use App\Models\LeadLog;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


use App;
class TasksController extends Controller
{
    private $viewPath = "tasks.";
    public function index(Request $request){
        $header_title_name="Tasks";
            $taskDetails = LeadTask::with([ 'user','lead' ,'leadTaskDetails' ,'leadServices.service','leadServices.subservice']);
            
            $searchKey = $request->input('key') ?? '';
            $requestType = $request->input('requestType') ?? '';
            if ($searchKey) {
                $taskDetails->whereHas('lead', function ($q) use ($searchKey) {
                    $q->where('client_name', 'LIKE', '%' . $searchKey . '%');
                });
                $taskDetails->orWhereHas('leadServices', function ($q) use ($searchKey) {
                    $q->whereHas('service', function ($q) use ($searchKey) {
                        $q->where('serviceName', 'LIKE', '%' . $searchKey . '%');
                    })
                    ->orWhereHas('subservice', function ($q) use ($searchKey) {
                        $q->where('subServiceName', 'LIKE', '%' . $searchKey . '%');
                    });
                });
            }
            
            $taskDetailsDrp = $taskDetails->get();
            $taskDetails = $taskDetails->paginate(env("PAGINATION_COUNT"));
            if (empty($requestType)) {                                                       
                $header_title_name = 'User';
                return view($this->viewPath.'index',[
                    'header_title_name' => $header_title_name,
                    'taskDetails' => $taskDetails,
                    'searchKey' => $searchKey,
                    'taskDetailsDrp' => $taskDetailsDrp
                ]);
            }else{
                $trData = view($this->viewPath.'task_fillter_data_listing', compact('taskDetails', 'searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
            }

    }

    public function logs(){
        $header_title_name="Lead Logs";
        return view($this->viewPath.'logs',compact('header_title_name'));
    }
    public function detail($id){
        $header_title_name="Lead action & details";
        $taskDetails = LeadTask::with(['user', 'lead', 'leadTaskDetails', 'leadServices.service', 'leadServices.subservice'])
        ->where('id', $id)
        ->get();
        return view('tasks/detail',compact('header_title_name', 'taskDetails'));
    }
    public function chekDuplication($id){
        $header_title_name="Lead action & details";
        $taskDetails = LeadTask::with(['user', 'lead', 'leadTaskDetails', 'leadServices.service', 'leadServices.subservice'])
        ->where('id', $id)
        ->get();
        $taskID = $id;
    
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        return view('tasks/tradeMark/check_duplication',compact('header_title_name', 'taskID','taskDetails', 'users'));
    }

    public function documentVerified(Request $request , $id){
        
        //   dd($request->all());
           $rule = [
            'status' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'nullable',
            'description' => 'nullable'
           ];

           $validtor = Validator::make($request->all(), $rule);
           if($validtor->fails()){
            return redirect()->back()->withErrors($validtor)->withInput();
           }
           if($id){
              $existedTaskDetails = LeadTask::find($id);
              $existedLeadTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
              $existedTaskDetails->user_id = $request->assignUser;
              $existedTaskDetails->assign_by = Auth::id();
              $existedTaskDetails->task_description = $request->description;
              if($existedTaskDetails->save()){
                $existedLeadTaskDetails->dead_line = $request->deadline;
                $existedLeadTaskDetails->status = $request->status;
                $existedLeadTaskDetails->status_date = $request->verified;
                $existedLeadTaskDetails->save();

                    $LeadLog = new LeadLog();
                    $LeadLog->user_id = $request->assignUser;
                    $LeadLog->lead_id =  $existedTaskDetails->lead_id;
                    $LeadLog->task_is = $existedTaskDetails->id;
                    $LeadLog->assign_by = Auth::id();
                    $LeadLog->description = $request->description;
                    $LeadLog->save();
                    return redirect()->back()->with('success', 'Document verified successfully');
                }

              }

              


           }



    }
   

    



