<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadTask;
use App\Models\User;

use App;
class TasksController extends Controller
{
    private $viewPath = "tasks.";
    public function index(Request $request){
        $header_title_name="Tasks";
            $taskDetails = LeadTask::with([ 'user','lead','leadAssign' ,'leadTaskDetails' ,'leadServices.service','leadServices.subservice']);
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
        $taskDetails = LeadTask::with(['user', 'lead', 'leadAssign', 'leadTaskDetails', 'leadServices.service', 'leadServices.subservice'])
        ->where('id', $id)
        ->get();
        return view('tasks/detail',compact('header_title_name', 'taskDetails'));
    }

    public function chekDuplication($id){
        $header_title_name="Lead action & details";
        $taskDetails = LeadTask::with(['user', 'lead', 'leadAssign', 'leadTaskDetails', 'leadServices.service', 'leadServices.subservice'])
        ->where('id', $id)
        ->get();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1);
        return view('tasks/detail',compact('header_title_name', 'taskDetails', 'users'));
    }
   

    
}


