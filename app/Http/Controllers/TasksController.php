<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\LeadTask;
class TasksController extends Controller
{
    public function index(Request $request){
        // $header_title_name="Tasks";
            $taskDetails = LeadTask::select('task_description', 'user_id', 'lead_id', 'status') 
            ->with([ 'user','lead','leadAssign','leadServices.service','leadServices.subservice',]);
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
            $taskDetails = $taskDetails->paginate(env("PAGINATION_COUNT"));
            if (empty($requestType)) {                                                       
                $header_title_name = 'User';
                return view('tasks/index',compact('header_title_name', 'taskDetails','searchKey'));
            }else{
                // dd($taskDetails);
                $trData = view('tasks/task_fillter_data_listing', compact('taskDetails', 'searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
            }

    }

    public function logs(){
        $header_title_name="Lead Logs";
        return view('tasks/logs',compact('header_title_name'));
    }

    public function detail(){
        $header_title_name="Lead action & details";
        return view('tasks/detail',compact('header_title_name'));
    }
   

    
}


