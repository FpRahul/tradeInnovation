<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadTask;
use App\Models\LeadTaskDetail;
use App\Models\LeadLog;
use App\Models\User;
use App\Models\LeadService;
use App\Models\LeadNotification;
use App\Jobs\SendClientWelcomeEmail;

use App\Models\ServiceStages;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;
use App\Jobs\SendTaskCommanMailJob;

class TasksController extends Controller
{
    private $viewPath = "tasks.";
    public function index(Request $request)
    {
        // dd($request->all());
        $DistinctleadId = LeadTask::with('lead')
            ->select('lead_id')
            ->distinct()
            ->get();

        if ($request->id && $request->NotifyId) {
            $baseNotifyId = base64_decode($request->NotifyId);
            $notifyData = LeadNotification::where('id', $baseNotifyId)->update(['status' => 1]);
        }

        $header_title_name = "Tasks";
        $assignUser = auth()->user();
        $taskDetails = LeadTask::with(['user', 'lead', 'leadTaskDetails', 'services', 'subService', 'serviceSatge'])->orderBy('created_at', 'desc');
        if ($assignUser->role != 1) {
            $taskDetails = $taskDetails->where('user_id', $assignUser->id);
        }
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if ($searchKey) {
            $taskDetails->where(function ($query) use ($searchKey) {
                $query->whereHas('lead', function ($q) use ($searchKey) {
                    $q->where('client_name', 'LIKE', '%' . $searchKey . '%');
                })
                    ->orWhereHas('services', function ($q) use ($searchKey) {
                        $q->where('serviceName', 'LIKE', '%' . $searchKey . '%');
                    })
                    ->orWhereHas('subService', function ($q) use ($searchKey) {
                        $q->where('subServiceName', 'LIKE', '%' . $searchKey . '%');
                    });
            });
        }


        if ($request->leadId && $request->status) {
            $taskDetails = $taskDetails->whereHas('lead', function ($q) use ($request) {
                $q->where('lead_id', $request->leadId);
            })
                ->whereHas('leadTaskDetails', function ($q) use ($request) {
                    $q->where('status', $request->status);
                });
        } else if ($request->leadId) {
            $taskDetails = $taskDetails->whereHas('lead', function ($q) use ($request) {
                $q->where('lead_id', $request->leadId);
            })
                ->whereHas('leadTaskDetails', function ($query) {
                    $query->where('status', '!=', 1);
                });
        } else if ($request->status) {
            $taskDetails = $taskDetails->whereHas('leadTaskDetails', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        } else {

            $taskDetails = $taskDetails->whereHas('leadTaskDetails', function ($query) {
                $query->where('status', '!=', 1);
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
                'taskDetailsDrp' => $taskDetailsDrp,
                'DistinctleadId' => $DistinctleadId
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
        if ($id) {
            $notifyData = LeadNotification::where('id', $id)->update(['status' => 1]);
        }
        $header_title_name = "search For Trademark";
        $taskDetails = LeadTask::with(['user', 'lead', 'leadTaskDetails', 'services', 'subService', 'serviceSatge'])
            ->where('id', $id)
            ->get();
        $taskID = $id;
        foreach ($taskDetails as $value) {
            $serviceID = $value->serviceSatge->service_id;
            $stage_id = $value->service_stage_id;
        }
        $getStage = ServiceStages::where('service_id', $serviceID)->where('id', '>', $stage_id)->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        return view('tasks/tradeMark/check_duplication', compact('header_title_name', 'taskID', 'taskDetails', 'users', 'getStage'));
    }

    public function duplicateVerified(Request $request, $id)
    {


        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadLineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedTaskDetails = LeadTask::with(['user', 'services', 'subService', 'serviceSatge'])->where('id', $id)->first();

        $serviceId = $existedTaskDetails->services->id;
        $subServiceId = $existedTaskDetails->subService->id;

        $stageId = (int) $request->stage_id;

        $assignedStageName = ServiceStages::where('id', $stageId)->first();
        $user_assign_by = Auth::user()->name;
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
            $newExistedTaskDetails->service_id = $serviceId;
            $newExistedTaskDetails->subservice_id = $subServiceId;
            $newExistedTaskDetails->assign_by = Auth::id();
            $newExistedTaskDetails->task_title = $assignedStageName->description;
            $newExistedTaskDetails->task_description = $request->description;
            $newExistedTaskDetails->service_stage_id = $request->stage_id;
            if ($newExistedTaskDetails->save()) {
                $existedLeadTaskDetails->status = 1;
                $existedLeadTaskDetails->status_date = $verifiedDate;
                $existedLeadTaskDetails->comment = $request->ifRegister ?? null;
                if ($request->hasFile('attachment')) {
                    $folderPath = public_path('uploads/leads/' . $lead_id);
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
                if ($newTaskassign->save()) {
                    $userAssign = $request->assignUser;
                    if (empty($userAssign)) {
                        $userAssign =  $request->alreadyAssign;
                    }

                    $notification = new LeadNotification();
                    $notification->user_id = $userAssign;
                    $notification->lead_id = $existedTaskDetails->lead_id;
                    $notification->task_id = $newTaskassign->id;
                    $notification->title = "Task Assigned";

                    $notification->description =  $user_assign_by . ' assigned you ' . $assignedStageName->title . ' task';;
                    $notification->status = 0;
                    if ($notification->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id = $existedTaskDetails->user_id;
                        $LeadLog->lead_id =  $existedTaskDetails->lead_id;
                        $LeadLog->task_id = $existedTaskDetails->id;
                        $LeadLog->assign_by = Auth::id();
                        if ($request->status == 1) {
                            $LeadLog->description = "Trademark search status marked as not registered";
                        } else if ($request->status == 0) {
                            $LeadLog->description = "Trademark mark as registered and conversion status marked as" . ' ' . $request->ifRegister;
                        }
                        $LeadLog->save();
                    }
                    $id = $newExistedTaskDetails->id;
                    return redirect()->route('task.index')
                        ->with('success', 'Document verified successfully');
                    // return redirect()->route('task.index', ['id' => $id ,'serviceId' => $serviceId,'stageId'=> $stageId])
                    // ->with('success', 'Document verified successfully');
                }
            } else {

                return redirect()->back()->with('error', "there is something wrong");
            }
        } else {
            return redirect()->back()->with('error', "there is something wrong");
        }
    }

    public function documentVerifiedChildSatge($id)
    {

        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }
        $header_title_name = "Send Quotation";
        $taskDetails = LeadTask::with(['user', 'lead', 'leadTaskDetails', 'services', 'subService', 'serviceSatge'])
            ->where('id', $id)
            ->get();
        foreach ($taskDetails as $task) {
            $taskDetailsId = $task->id;
            $serviceName = $task->services->serviceName;
            $serviceID = $task->services->id;
        }

        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        foreach ($taskDetails as $value) {
            $stage_id = $value->service_stage_id;
        }
        $getStage = ServiceStages::where('service_id', $serviceID)->where('id', '>', $stage_id)->first();


        $leadTaskdetials = LeadTaskDetail::find($taskDetailsId);
        return view('tasks.tradeMark.send_quotation', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'serviceName'));
    }
    public function sendQuotation(Request $request, $id)
    {
        // dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedTask = LeadTask::with(['services', 'subService', 'lead', 'serviceSatge'])->where('id', $id)->first();
        $existedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();

        $newTaskAssigned = new LeadTask();
        $newTaskDetails = new LeadTaskDetail();
        $serviceId = $existedTask->services->id;
        $subServiceID = $existedTask->subService->id;
        $rule = [
            'verified' => 'required',
            'attachment' => 'array',
            'attachment.*' => 'nullable',
            'subject' => 'required',
            'service_price' => 'required|numeric',
            'govt_price' => 'required|numeric',

        ];
        $validtor =  Validator::make($request->all(), $rule);
        if ($validtor->fails()) {
            return redirect()->back()->withErrors($validtor)->withInput();
        }
        $mail = false;
        $subject = $request->subject;
        $service = $request->service;
        $service_price = $request->service_price;
        $govt_price = $request->govt_price;
        $gst = $request->gst ?? 'Not Apply';
        if (!empty($gst)) {
            $service_price = $service_price + ($service_price * 0.18);
        } else {
            $service_price = $request->service_price;
        }
        $quoted_price = $service_price +  $govt_price;
        $clientName = $existedTask->lead->client_name;
        $clientEmail = $existedTask->lead->email;
        $stageId = (int) $request->stage_id;

        $userName = Auth::user()->name;
        $assignedStageName = ServiceStages::where('id', $stageId)->first();
        if ($id) {
            $mail = true;
            $newTaskAssigned->user_id = $request->assignUser ?? $existedTask->user_id;
            $newTaskAssigned->lead_id = $existedTask->lead_id;
            $newTaskAssigned->service_id = $serviceId;
            $newTaskAssigned->subservice_id = $subServiceID;
            $newTaskAssigned->service_stage_id = $stageId;
            $newTaskAssigned->assign_by = Auth::id();
            $newTaskAssigned->task_title = $assignedStageName->description;
            $newTaskAssigned->task_description = $request->description;
            $existedTask->quoted_price = $quoted_price;
            if ($newTaskAssigned->save() && $existedTask->save()) {
                $newTaskDetails->task_id = $newTaskAssigned->id;
                $newTaskDetails->status = 0;
                $newTaskDetails->dead_line = $deadlineDate;
                if ($newTaskDetails->save()) {
                    $existedTaskDetails->status_date = $verifiedDate;
                    $existedTaskDetails->status = 1;
                    $existedTaskDetails->mail_subject = $request->subject;
                    $existedTaskDetails->service_price = $request->service_price;
                    $existedTaskDetails->govt_price = $request->govt_price;
                    $existedTaskDetails->gst = $request->gst ?? null;
                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedTask->lead_id);
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
                        $existedTaskDetails->attachment = json_encode($filePaths);
                    }
                    if ($existedTaskDetails->save()) {

                        $userAssign =  $request->assignUser ?? $existedTask->user_id;
                        $notification = new LeadNotification();
                        $notification->user_id =  $userAssign;
                        $notification->lead_id = $existedTask->lead_id;
                        $notification->task_id = $newTaskAssigned->id;
                        $notification->title = "Task Assigned";
                        $notification->description =  $userName . ' assigned you ' . $assignedStageName->title . ' task';
                        $notification->status = 0;
                        if ($notification->save()) {

                            $LeadLog = new LeadLog();
                            $LeadLog->user_id =  $existedTask->user_id;
                            $LeadLog->lead_id =  $existedTask->lead_id;
                            $LeadLog->task_id =  $existedTask->id;
                            $LeadLog->assign_by = Auth::id();
                            $LeadLog->description = "Quotation sent to the client";
                            $LeadLog->save();
                            if ($mail) {
                                SendTaskCommanMailJob::dispatch($subject, $service, $service_price, $govt_price, $clientName, $clientEmail, $userName);
                            }
                        }
                    }
                    $id = $newTaskAssigned->id;
                    return redirect()->route('task.index')
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
        if ($id) {

            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }
        $header_title_name = "payment Status";
        $taskDetails = LeadTask::with(['user', 'lead', 'leadTaskDetails', 'services', 'subService', 'serviceSatge'])
            ->where('id', $id)
            ->get();
        foreach ($taskDetails as $task) {
            $taskDetailsId = $task->id;
        }
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        foreach ($taskDetails as $value) {
            $stageId = $value->service_stage_id;
        }
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($taskDetailsId);
        return view('tasks.tradeMark.payment_status', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }

    public function paymentStatus(Request $request, $id)
    {
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::with(['lead', 'services', 'subService', 'serviceSatge'])->where('id', $id)->first();
        $mail = false;
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $serviceId = $existedLeaedTask->services->id;
        $subServiceId = $existedLeaedTask->subService->id;
        $stageId = (int) $request->stage_id;
        $userName =  Auth::user()->name;
        $assignedStageName = ServiceStages::where('id', $stageId)->first();
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
            $newLeadtask->service_id = $serviceId;
            $newLeadtask->subservice_id = $subServiceId;
            $newLeadtask->service_stage_id = $stageId;
            $newLeadtask->assign_by = Auth::id();
            $newLeadtask->task_title = $assignedStageName->description;
            $newLeadtask->task_description = $request->description ?? null;
            if ($newLeadtask->save()) {
                $existedLeaedTaskDetails->status = 1;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $newLeadTaskDeatails->task_id = $newLeadtask->id;
                $newLeadTaskDeatails->dead_line = $deadlineDate;
                $newLeadTaskDeatails->status = 0;
                if ($request->hasFile('attachment')) {
                    $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
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
                    $existedLeaedTaskDetails->attachment = json_encode($filePaths);
                }
                if ($existedLeaedTaskDetails->save() && $newLeadTaskDeatails->save()) {
                    $userAssign =  $request->assignUser ?? $existedLeaedTask->user_id;
                    $notification = new LeadNotification();
                    $notification->user_id = $userAssign;
                    $notification->lead_id = $existedLeaedTask->lead_id;
                    $notification->task_id = $newLeadtask->id;
                    $notification->title = "Task Assigned";
                    $notification->description =  $userName . ' assigned you ' . $assignedStageName->title . ' task';
                    if($request->payment == 0){

                        $notification->deadline_date = $deadlineDate;
                    }else{
                        $notification->deadline_date = null;

                    }
                    $notification->status = 0;

                    if ($notification->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                        $LeadLog->task_id =  $existedLeaedTask->id;
                        $LeadLog->assign_by = Auth::id();
                        if ($request->payment == 1) {
                            $LeadLog->description = " Payment status marked as Paid ";
                        } else if ($request->payment == 0) {
                            $LeadLog->description = " Payment status marked as on Credit ";
                        }
                        $LeadLog->save();
                        // $type = 'Client';
                        // $mail = true;
                        // $randomNumber = substr(str_shuffle('9abcdefghijklmnopq045678rstuvwxyzABCDEFG123HIJKLMNOPQRSTUVWXYZ'), 0, 8);
                        // $hashedPassword = Hash::make($randomNumber);
                        // $newClient = new User();
                        // $newClient->uni_user_id =  $this->generateUniqueUserCode('C', '=', 2);
                        // $newClient->name = $existedLeaedTask->lead->client_name;
                        // $newClient->role = 2;
                        // if (User::where('email', $existedLeaedTask->lead->email)->exists()) {
                        //     $newClient->email = null;
                        // }else{
                        //     $newClient->email = $existedLeaedTask->lead->email;
                     
                        // }
                        // $newClient->mobile = $existedLeaedTask->lead->mobile_number;
                        // $newClient->companyName = $existedLeaedTask->lead->company_name;
                        // $newClient->password = $hashedPassword;
                        // if($mail == true && $newClient->save() ){
                        //     SendClientWelcomeEmail::dispatch($newClient,$randomNumber, $filePath = null,$type);
                        // }
                        // if($LeadLog->save()){
                            $id = $newLeadtask->id;
                            return redirect()->route('task.index')->with('success', 'payment status is Updated');
                        // }
                    }
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
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }
        $header_title_name = "Document Drafting";
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

    public function documenStatus(Request $request, $id)
    {
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
    }

    public function assignTask(Request $request)
    {
        $header_title_name = "Assign Task";
        return view('settings.assign_stage', compact('header_title_name'));
    }

    public function followUp($id, $serviceId, $stageId)
    {

        $taskDetails = LeadTask::find($id);
        if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 1) {
            return redirect()->route('task.chekDuplication', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 2) {
            return redirect()->route('task.documentVerifiedChildSatge', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 3) {
            return redirect()->route('task.checkPayment', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 3) {
            return redirect()->route('task.documentation', ['id' => $id]);
        }
        // For Patent...............
        else if ($taskDetails && $serviceId == 2 && $stageId == 6) {
            return redirect()->route('task.patentPaymentVerification', ['id' => $taskId]);
        }
    }

    public function patentPaymentVerification(Request $request, $id = null)
    {
        $taskId = $id;
        $taskList = LeadTask::find($taskId);
        $serviceStage = ServiceStages::where('id', '>', $taskList->service_stage_id)->where('service_id', 2)->get();
        $userList = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        $header_title_name = "Payment Verification";
        return view('tasks/patent/payment-verification', compact('header_title_name', 'taskId', 'taskList', 'serviceStage', 'userList'));
    }

    public function holdtask(Request $request)
    {
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $rule = [
            'verified' => 'required',
            'description' => 'required'
        ];
        $validtor =  Validator::make($request->all(), $rule);
        if ($validtor->fails()) {
            return redirect()->back()->withErrors($validtor)->withInput();
        }
        $taskDetails = LeadTaskDetail::where('task_id', $request->task_hidden_id)->first();
        $taskDetails->status = 2;
        $taskDetails->status_date = $verifiedDate;
        $taskDetails->comment = $request->description;
        if ($taskDetails->save()) {
            return redirect()->route('task.index')->with('success', 'Task on hold');
        } else {
            return redirect()->back()->error('message', " No task found ");
        }
    }

    public function generateUniqueUserCode($type, $symb, $role)
    {
        $lastUser = User::where('role', $symb, $role)->latest()->first();
        if ($lastUser && $lastUser->uni_user_id) {
            $lastNumber = (int) substr($lastUser->uni_user_id, 1);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        return $type . $newNumber;
    }
}
