<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadTask;
use App\Models\LeadTaskDetail;
use App\Models\LeadLog;
use App\Models\User;
use App\Models\LeadService;
use App\Models\ServiceStages;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;



class TasksController extends Controller
{
    private $viewPath = "tasks.";
    public function index(Request $request)
    {
        $header_title_name = "Tasks";
        $taskDetails = LeadTask::with(['user', 'lead', 'leadTaskDetails', 'leadServices.service', 'leadServices.subservice', 'serviceSatge']);
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
            return view($this->viewPath . 'index', [
                'header_title_name' => $header_title_name,
                'taskDetails' => $taskDetails,
                'searchKey' => $searchKey,
                'taskDetailsDrp' => $taskDetailsDrp
            ]);
        } else {
            $trData = view($this->viewPath . 'task_fillter_data_listing', compact('taskDetails', 'searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
        }
    }

    public function logs()
    {
        $header_title_name = "Lead Logs";
        return view($this->viewPath . 'logs', compact('header_title_name'));
    }
    public function detail($id)
    {
        $header_title_name = "Lead action & details";
        $taskDetails = LeadTask::with(['user', 'lead', 'leadTaskDetails', 'leadServices.service', 'leadServices.subservice'])
            ->where('id', $id)
            ->get();
        return view('tasks/detail', compact('header_title_name', 'taskDetails'));
    }
    public function chekDuplication($id)
    {
        $header_title_name = "Lead action & details";
        $taskDetails = LeadTask::with(['user', 'lead', 'leadTaskDetails', 'leadServices.service', 'leadServices.subservice', 'serviceSatge'])
            ->where('id', $id)
            ->get();
        $taskID = $id;
        foreach ($taskDetails as $value) {
            $serviceID = $value->serviceSatge->service_id;
        }
        $getStage = ServiceStages::where('service_id', $serviceID)->where('id', '>', $serviceID)->get();

        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        return view('tasks/tradeMark/check_duplication', compact('header_title_name', 'taskID', 'taskDetails', 'users', 'getStage'));
    }

    public function duplicateVerified(Request $request, $id)
    {
        // dd($id);
        // dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadLineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedTaskDetails = LeadTask::find($id);
        $newExistedTaskDetails = new LeadTask();
        $lead_id = $existedTaskDetails->lead_id;
        if (!empty($request->input('assignUser'))) {
            $assignUser = $request->input('assignUser');
        } else {
            $assignUser = $request->input('alreadyAssign');
        }
        $rule = [
            'status' => 'required',
            'verified' => 'required',
            'assignUser' => 'nullable',
            'deadline' => 'required',
            'attachment' => 'array',
            'attachment.*' => 'nullable',
            'description' => 'nullable',
            'stage_id' => 'required',
        ];
        $validtor = Validator::make($request->all(), $rule);
        if ($validtor->fails()) {
            return redirect()->back()->withErrors($validtor)->withInput();
        }
        if ($id) {
            $existedLeadTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
            $newExistedTaskDetails->user_id = $assignUser;
            $newExistedTaskDetails->lead_id = $existedTaskDetails->lead_id;
            $newExistedTaskDetails->assign_by = Auth::id();
            $newExistedTaskDetails->task_description = $request->description;
            $newExistedTaskDetails->service_stage_id = $request->stage_id;
            if ($newExistedTaskDetails->save()) {
                $existedLeadTaskDetails->status = $request->status;
                $existedLeadTaskDetails->status_date = $verifiedDate;
                $existedLeadTaskDetails->comment = $request->ifRegister ?? null;
                if ($request->hasFile('attachment')) {
                    $folderPath = public_path('Image/leads/lead_' . $lead_id);
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0755, true);
                    }
                    $filePaths = [];
                    foreach ($request->file('attachment') as $file) {
                        if ($file->isValid()) {
                            $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                            $file->move($folderPath, $fileName);
                            $filePaths[] = $fileName;
                        }
                    }
                    $existedLeadTaskDetails->attachment = json_encode($filePaths);
                }
                if ($existedLeadTaskDetails->save()) {
                    $newTaskassign = new LeadTaskDetail();
                    $newTaskassign->task_id = $newExistedTaskDetails->id;
                    $newTaskassign->dead_line = $deadLineDate;
                    $newTaskassign->status = 0;
                }
                
                $newTaskassign->save();
                $LeadLog = new LeadLog();
                $LeadLog->user_id = $assignUser;
                $LeadLog->lead_id =  $existedTaskDetails->lead_id;
                $LeadLog->task_id = $newTaskassign->id;
                $LeadLog->assign_by = Auth::id();
                $LeadLog->description = $request->description;
                $LeadLog->save();
                $id = $newExistedTaskDetails->id;
                return redirect()->route('task.documentVerifiedChildSatge', ['id' => $id])
                    ->with('success', 'Document verified successfully');
            } else {
                // dd($id);
                return redirect()->back()->with('error', "there is something wrong");
            }
        } else {
            return redirect()->back()->with('error', "there is something wrong");
        }
    }

    public function documentVerifiedChildSatge($id)
    {
        $header_title_name = "Client Approval";
        $taskDetails = LeadTask::with(['user', 'lead', 'leadTaskDetails', 'leadServices.service', 'leadServices.subservice', 'serviceSatge'])
            ->where('id', $id)
            ->get();
        foreach ($taskDetails as $task) {
            $taskDetailsId = $task->id;
        }
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        foreach ($taskDetails as $value) {
            $stageId = $value->service_stage_id;
        }

        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->get();
        $leadTaskdetials = LeadTaskDetail::find($taskDetailsId);
        return view('tasks.tradeMark.send_quotation', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }
    public function sendQuotation(Request $request, $id)
    {
        // dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');


        $existedTask = LeadTask::find($id);
        $existedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newTaskAssigned = new LeadTask();
        $newTaskDetails = new LeadTaskDetail();
        $rule = [
            'verified' => 'required',
            'attachment' => 'array',
            'attachment.*' => 'nullable',
        ];

        $validtor =  Validator::make($request->all(), $rule);
        if ($validtor->fails()) {
            return redirect()->back()->withErrors($validtor)->withInput();
        }
        if ($id) {
            $newTaskAssigned->user_id = $request->assignUser ?? $existedTask->user_id;
            $newTaskAssigned->lead_id = $existedTask->lead_id;
            $newTaskAssigned->service_stage_id = $existedTask->service_stage_id;
            $newTaskAssigned->assign_by = Auth::id();
            $newTaskAssigned->task_description = $request->description;
            if ($newTaskAssigned->save()) {
                $newTaskDetails->task_id = $newTaskAssigned->id;
                $newTaskDetails->status = 0;
                $newTaskDetails->dead_line = $deadlineDate;
                if ($request->hasFile('attachment')) {
                    $folderPath = public_path('Image/leads/lead_' . $existedTask->lead_id);
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0755, true);
                    }
                    $filePaths = [];
                    foreach ($request->file('attachment') as $file) {
                        if ($file->isValid()) {
                            $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                            $file->move($folderPath, $fileName);
                            $filePaths[] = $fileName;
                        }
                    }
                    $newTaskDetails->attachment = json_encode($filePaths);
                }
                if ($newTaskDetails->save()) {
                    $existedTaskDetails->status_date = $verifiedDate;
                    $existedTaskDetails->status = 1;
                    if ($existedTaskDetails->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id = $request->assignUser ?? $existedTask->user_id;
                        $LeadLog->lead_id =  $existedTask->lead_id;
                        $LeadLog->task_id = $newTaskAssigned->id;
                        $LeadLog->assign_by = Auth::id();
                        $LeadLog->description = $request->description;
                        $LeadLog->save();
                    }
                    $id = $newTaskAssigned->id;
                    return redirect()->route('task.checkPayment', ['id' => $id])
                        ->with('success', 'Quotation sent successfully');
                } else {
                    return redirect()->back()->error('message', " there is something wrong ");
                }
            } else {
                return redirect()->back()->error('message', " there is something wrong ");
            }
        } else {
            return redirect()->back()->error('message', " Task not assigned");
        }
    }

    public function checkPayment($id)
    {
        $header_title_name = "payment Status";
        $taskDetails = LeadTask::with(['user', 'lead', 'leadTaskDetails', 'leadServices.service', 'leadServices.subservice', 'serviceSatge'])
            ->where('id', $id)
            ->get();
        foreach ($taskDetails as $task) {
            $taskDetailsId = $task->id;
        }
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        foreach ($taskDetails as $value) {
            $stageId = $value->service_stage_id;
        }

        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->get();
        $leadTaskdetials = LeadTaskDetail::find($taskDetailsId);
        return view('tasks.tradeMark.payment_status', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }

    public function paymentStatus(Request $request, $id)
    {
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $rule = [
            'payment' => 'required',
            'deadline' => 'required',
            'stage_id' => 'required',
            'attachment' => 'array',
            'attachment.*' => 'nullable',
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
            $newLeadtask->lead_id = $existedLeaedTask->lead_id;
            $newLeadtask->service_stage_id = $request->stage_id;
            $newLeadtask->assign_by = Auth::id();
            $newLeadtask->task_description = $request->description ?? null;
            if ($newLeadtask->save()) {
                $existedLeaedTaskDetails->status = $request->payment;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $newLeadTaskDeatails->task_id = $newLeadtask->id;
                $newLeadTaskDeatails->dead_line = $deadlineDate;
                if ($request->hasFile('attachment')) {
                    $folderPath = public_path('Image/leads/lead_' . $existedLeaedTask->lead_id);
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0755, true);
                    }
                    $filePaths = [];
                    foreach ($request->file('attachment') as $file) {
                        if ($file->isValid()) {
                            $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                            $file->move($folderPath, $fileName);
                            $filePaths[] = $fileName;
                        }
                    }
                    $newLeadTaskDeatails->attachment = json_encode($filePaths);
                }
                if ($existedLeaedTaskDetails->save() && $newLeadTaskDeatails->save()) {
                    $LeadLog = new LeadLog();
                    $LeadLog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                    $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                    $LeadLog->task_id = $newLeadtask->id;
                    $LeadLog->assign_by = Auth::id();
                    $LeadLog->description = $request->description;
                    $LeadLog->save();

                    $id = $newLeadtask->id;
                    return redirect()->route('task.documentation', ['id' => $id])->with('success', 'payment status is Updated');
                } else {
                    return redirect()->back()->error('message', " there is something wrong ");
                }
            } else {
                return redirect()->back()->error('message', " there is something wrong ");
            }
        } else {
            return redirect()->back()->error('message', " No task found ");
        }
    }


    public function documentation($id)
    {
        $header_title_name = "Document Verification";
        $taskDetails = LeadTask::with(['user', 'lead', 'leadTaskDetails', 'leadServices.service', 'leadServices.subservice', 'serviceSatge'])
            ->where('id', $id)
            ->get();
        foreach ($taskDetails as $task) {
            $taskDetailsId = $task->id;
        }
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        foreach ($taskDetails as $value) {
            $stageId = $value->service_stage_id;
        }
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->get();
        $leadTaskdetials = LeadTaskDetail::find($taskDetailsId);
        return view('tasks.tradeMark.document_verfication', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }

    public function documenStatus(Request $request, $id){
        
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
         
        
    }

    public function followUp($id){
        
    }
}
