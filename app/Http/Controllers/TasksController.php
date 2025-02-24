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

        $leadParam = $request->leadId;
        $statusParam = $request->status;
        $userParam = $request->user;



        $DistinctleadId = LeadTask::with('lead')
            ->select('lead_id')
            ->distinct()
            ->get();
        $users =  User::get();

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
                })
                ->whereHas('lead', function ($q) use ($request) {
                    $q->where('user_id', $request->user);
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
        } else if ($request->user) {
            $taskDetails = $taskDetails->whereHas('lead', function ($q) use ($request) {
                $q->where('user_id', $request->user);
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
                'DistinctleadId' => $DistinctleadId,
                'users' => $users,
                'leadParam' => $leadParam,
                'statusParam' => $statusParam,
                'userParam' => $userParam
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
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
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
            if ($request->ifRegister == 'Abandoned') {
                $existedTaskDetails->task_description = $request->description;
                if ($existedTaskDetails->save()) {
                    $existedLeadTaskDetails->status = 1;
                    $existedLeadTaskDetails->status_date = $verifiedDate;
                    $existedLeadTaskDetails->dead_line = null;
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
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id = $existedTaskDetails->user_id;
                        $LeadLog->lead_id =  $existedTaskDetails->lead_id;
                        $LeadLog->task_id = $existedTaskDetails->id;
                        $LeadLog->assign_by = Auth::id();
                        $LeadLog->description = 'This trademark is already registered, and therefore, it is abandoned after the clients approval.';
                        if ($LeadLog->save()) {
                            return redirect()->route('task.index')->with('error', "Trademark is already Register");
                        }
                    }
                }
            }
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
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $assignUser;
                                $newassignlog->lead_id = $existedTaskDetails->lead_id;
                                $newassignlog->task_id = $newExistedTaskDetails->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->description = "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    $id = $newExistedTaskDetails->id;
                                    return redirect()->route('task.index')
                                        ->with('success', 'Document verified successfully');
                                    // return redirect()->route('task.index', ['id' => $id ,'serviceId' => $serviceId,'stageId'=> $stageId])
                                    // ->with('success', 'Document verified successfully');
                                }
                            }
                        }
                    }
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
            $clientName = $task->lead->client_name;
        }

        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        foreach ($taskDetails as $value) {
            $stage_id = $value->service_stage_id;
        }
        $getStage = ServiceStages::where('service_id', $serviceID)->where('id', '>', $stage_id)->first();


        $leadTaskdetials = LeadTaskDetail::find($taskDetailsId);
        return view('tasks.tradeMark.send_quotation', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'serviceName', 'clientName'));
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
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedTask->user_id;
                                $newassignlog->lead_id = $existedTask->lead_id;
                                $newassignlog->task_id = $newTaskAssigned->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    // if ($mail== true) {
                                    //     SendTaskCommanMailJob::dispatch($subject, $service, $service_price, $govt_price, $clientName, $clientEmail, $userName);
                                    // }
                                }
                                $id = $newTaskAssigned->id;
                                return redirect()->route('task.index')
                                    ->with('success', 'Quotation sent successfully');
                            } else {
                                return redirect()->back()->error('message', " there is something wrong during update logs ");
                            }
                        } else {
                            return redirect()->back()->error('message', " there is something wrong during update notification ");
                        }
                    } else {
                        return redirect()->back()->error('message', " there is something wrong during update existed tasl details ");
                    }
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
        // dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $paymentDeadlineDate = Carbon::createFromFormat('d M Y', $request->input('paymentDeadline'))->format('Y-m-d');

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
            if ($request->checkStatus == 3) {
                $rule = [
                    'payment' => 'required',
                ];
                $validator = Validator::make($request->all(), $rule);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $existedLeaedTaskDetails->status = $request->payment;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                if ($request->payment == 3) {
                    $existedLeaedTaskDetails->reminderDate = $paymentDeadlineDate;
                } else {
                    $existedLeaedTaskDetails->reminderDate = null;
                }
                if ($existedLeaedTaskDetails->save()) {
                    $newassignlog = new leadLog();
                    $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                    $newassignlog->lead_id = $existedLeaedTask->lead_id;
                    $newassignlog->lead_id = $existedLeaedTask->lead_id;
                    $newassignlog->task_id = $existedLeaedTask->id;
                    $newassignlog->assign_by = Auth::id();
                    $newassignlog->description =  " Payment status marked as on Credit";
                    if ($newassignlog->save()) {
                        return redirect()->route('task.index')->with('success', 'payment status is Updated');
                    } else {
                        return redirect()->route('task.index')->with('error', 'there is something wrong while updateing logs');
                    }
                } else {
                    return redirect()->route('task.index')->with('error', 'there is something wrong while updateing  lead task details ');
                }
            }
            $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
            $newLeadtask->lead_id = $existedLeaedTask->lead_id;
            $newLeadtask->service_id = $serviceId;
            $newLeadtask->subservice_id = $subServiceId;
            $newLeadtask->service_stage_id = $stageId;
            $newLeadtask->assign_by = Auth::id();
            $newLeadtask->task_title = $assignedStageName->description;
            $newLeadtask->task_description = $request->description ?? null;
            if ($newLeadtask->save()) {
                $existedLeaedTaskDetails->status = $request->payment;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                if ($request->payment == 3) {
                    $existedLeaedTaskDetails->reminderDate = $paymentDeadlineDate;
                } else {
                    $existedLeaedTaskDetails->reminderDate = null;
                }
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
                    $notification->status = 0;
                    if ($notification->save()) {
                        $type = 'Client';
                        $mail = true;
                        $randomNumber = substr(str_shuffle('9abcdefghijklmnopq045678rstuvwxyzABCDEFG123HIJKLMNOPQRSTUVWXYZ'), 0, 8);
                        $hashedPassword = Hash::make($randomNumber);
                        $newClient = new User();
                        $newClient->uni_user_id =  $this->generateUniqueUserCode('C', '=', 2);
                        $newClient->name = $existedLeaedTask->lead->client_name;
                        $newClient->role = 2;
                        $newClient->email = $existedLeaedTask->lead->email;
                        $newClient->mobile = $existedLeaedTask->lead->mobile_number;
                        $newClient->companyName = $existedLeaedTask->lead->company_name;
                        $newClient->password = $hashedPassword;
                        if ($newClient->save()) {
                            if ($mail == true) {
                                SendClientWelcomeEmail::dispatch($newClient, $randomNumber, $filePath = null, $type);
                            }
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
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {

                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'payment status is Updated');
                                } else {
                                    return redirect()->back()->with('error', " there is something wrong during update logs ");
                                }
                            } else {
                                return redirect()->back()->with('error', " there is something wrong during update logs ");
                            }
                            // }
                        } else {
                            return redirect()->back()->with('error', " there is something wrong during update notification ");
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
    }


    public function documentation($id)
    {

        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.document_verfication', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }

    public function documenStatus(Request $request, $id)
    {
        // dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $reminder_date = Carbon::createFromFormat('d M Y', $request->input('reminder_date'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $rule = [
            'document' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            if ($request->document == 2) {
                $rule = [
                    'document' => 'required',
                    'verified' => 'required',
                    'reminder_date' => 'required',

                ];
                $validator = Validator::make($request->all(), $rule);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $existedLeaedTaskDetails->status = $request->document;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $existedLeaedTaskDetails->reminderDate = $reminder_date;
                $existedLeaedTaskDetails->comment = $request->description;
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
                if ($existedLeaedTaskDetails->save()) {
                    $newNotification->user_id = $existedLeaedTask->user_id;
                    $newNotification->lead_id = $existedLeaedTask->lead_id;
                    $newNotification->task_id = $existedLeaedTask->id;
                    $newNotification->title = 'task On Hold ';
                    $newNotification->description = 'document  verification is on hold for uncomplete document';
                    if ($newNotification->save()) {
                        $newLog =  new LeadLog();
                        $newLog->user_id = $existedLeaedTask->user_id;
                        $newLog->lead_id = $existedLeaedTask->lead_id;
                        $newLog->task_id = $existedLeaedTask->id;
                        $newLog->assign_by = Auth::id();
                        $newLog->description = "Document status marked as incomplete and missing documents are asked from the client";

                        if ($newLog->save()) {
                            return redirect()->route('task.index')->with('success', 'payment status is Updated');
                        } else {
                            return redirect()->back()->error('message', " there is something wrong during log generate ");
                        }
                    }
                } else {
                    return redirect()->back()->error('message', " there is something wrong during hold the task ");
                }
            } else if ($request->document == 1) {
                $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $existedLeaedTask->service_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->service_stage_id = $request->stage_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->assign_by = Auth::id();
                $newLeadtask->task_title = $newTaskTitle->title;
                if ($newLeadtask->save()) {
                    $existedLeaedTaskDetails->status = $request->document;
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
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
                    if ($existedLeaedTaskDetails->save()) {

                        $newLeadTaskDeatails->task_id = $newLeadtask->id;
                        $newLeadTaskDeatails->dead_line = $deadlineDate;
                        $newLeadTaskDeatails->status = 0;

                        if ($newLeadTaskDeatails->save()) {
                            $newNotification->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                            $newNotification->lead_id = $existedLeaedTask->lead_id;
                            $newNotification->task_id = $newLeadtask->id;
                            $newNotification->title = 'Task Assigned';
                            $newNotification->description =  $userName . ' assigned you ' . $newTaskTitle->title . ' task';
                            $newNotification->status = 0;
                            if ($newNotification->save()) {
                                $LeadLog =  new LeadLog();
                                $LeadLog->user_id = $existedLeaedTask->user_id;
                                $LeadLog->lead_id = $existedLeaedTask->lead_id;
                                $LeadLog->task_id = $existedLeaedTask->id;
                                $LeadLog->assign_by = Auth::id();
                                $LeadLog->description = " Document status marked as complete ";
                                if ($LeadLog->save()) {
                                    $newassignlog = new leadLog();
                                    $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                    $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                    $newassignlog->task_id = $newLeadtask->id;
                                    $newassignlog->assign_by = Auth::id();
                                    $newassignlog->description =  "Lead assigned for next task";
                                    if ($newassignlog->save()) {
                                        $id = $newLeadtask->id;
                                        return redirect()->route('task.index')->with('success', 'document verification completed');
                                    }
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong during upadate logs');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong during notification logs');
                            }
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong during update  new task details');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong during update  existed task details');
                    }
                }
            } else {
                return redirect()->back()->with('error', 'there is something wrong during update  new task');
            }
        } else {
            return redirect()->back()->with('error', 'no task found');
        }
    }

    public function clientApproval($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.client_approval', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }
    public function clientApprovalStatus(Request $request, $id)
    {
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $userName = Auth::user()->name;
        $newTaskTitle = ServiceStages::find($request->stage_id);
        $rule = [

            'verified' => 'required',
            'deadline' => 'required',
        ];
        $validator =  Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            $newLeadtask->user_id = $request->assignUser;
            $newLeadtask->lead_id = $existedLeaedTask->lead_id;
            $newLeadtask->service_id = $existedLeaedTask->service_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->service_stage_id = $request->stage_id;
            $newLeadtask->assign_by = Auth::id();
            $newLeadtask->task_title = $newTaskTitle->title;
            if ($newLeadtask->save()) {
                $existedLeaedTaskDetails->status =  1;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
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
                if ($existedLeaedTaskDetails->save()) {
                    $newLeadTaskDeatails->task_id = $newLeadtask->id;
                    $newLeadTaskDeatails->dead_line = $deadlineDate;
                    $newLeadTaskDeatails->status = 0;
                    if ($newLeadTaskDeatails->save()) {
                        $newNotification->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                        $newNotification->lead_id = $existedLeaedTask->lead_id;
                        $newNotification->task_id = $newLeadtask->id;
                        $newNotification->title = 'Task Assigned';
                        $newNotification->description =  $userName . ' assigned you ' . $newTaskTitle->title . ' task';
                        $newNotification->status = 0;
                        if ($newNotification->save()) {
                            $LeadLog =  new LeadLog();
                            $LeadLog->user_id = $existedLeaedTask->user_id;
                            $LeadLog->lead_id = $existedLeaedTask->lead_id;
                            $LeadLog->task_id = $existedLeaedTask->id;
                            $LeadLog->assign_by = Auth::id();
                            $LeadLog->description = " Client document verification marked as correct   ";
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'document verification completed');
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong while updatng loag');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong while updatng loag');
                            }
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong while updatng notification');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong while updating new task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is something wrong while updating existed task details');
                }
            } else {
                return redirect()->back()->with('error', 'there is something wrong while updating new task');
            }
        } else {
            return redirect()->back()->with('error', 'no task found');
        }
    }

    public function draftApplication($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();

        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.draft_application', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }

    public function draftApplicationStatus(Request $request, $id)
    {
        // dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $userName = Auth::user()->name;
        $newTaskTitle = ServiceStages::find($request->stage_id);
        $rule = [

            'verified' => 'required',
            'deadline' => 'required',
        ];
        $validator =  Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            $newLeadtask->user_id = $request->assignUser;
            $newLeadtask->lead_id = $existedLeaedTask->lead_id;
            $newLeadtask->service_id = $existedLeaedTask->service_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->service_stage_id = $request->stage_id;
            $newLeadtask->assign_by = Auth::id();
            $newLeadtask->task_title = $newTaskTitle->title;
            if ($newLeadtask->save()) {
                $existedLeaedTaskDetails->status = 1;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
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
                if ($existedLeaedTaskDetails->save()) {
                    $newLeadTaskDeatails->task_id = $newLeadtask->id;
                    $newLeadTaskDeatails->dead_line = $deadlineDate;
                    $newLeadTaskDeatails->status = 0;
                    if ($newLeadTaskDeatails->save()) {
                        $newNotification->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                        $newNotification->lead_id = $existedLeaedTask->lead_id;
                        $newNotification->task_id = $newLeadtask->id;
                        $newNotification->title = 'Task Assigned';
                        $newNotification->description =  $userName . ' assigned you ' . $newTaskTitle->title . ' task';
                        $newNotification->status = 0;
                        if ($newNotification->save()) {
                            $LeadLog =  new LeadLog();
                            $LeadLog->user_id = $existedLeaedTask->user_id;
                            $LeadLog->lead_id = $existedLeaedTask->lead_id;
                            $LeadLog->task_id = $existedLeaedTask->id;
                            $LeadLog->assign_by = Auth::id();
                            $LeadLog->description = "Documents drafted on portal successfully";
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'document verification completed');
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong while updatng loag');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong while updatng loag');
                            }
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong while updatng notification');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong while updating new task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is something wrong while updating existed task details');
                }
            } else {
                return redirect()->back()->with('error', 'there is something wrong while updating new task');
            }
        } else {
            return redirect()->back()->with('error', 'no task found');
        }
    }

    public function formalityCheck($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.formality_check', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }

    public function formalityCheckStatus(Request $request, $id)
    {
        //  dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $reminder_date = Carbon::createFromFormat('d M Y', $request->input('reminder_date'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $rule = [
            'formality_check' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            if ($request->formality_check == 2) {
                $rule = [
                    'formality_check' => 'required',
                    'verified' => 'required',
                    'reminder_date' => 'required',

                ];
                $validator = Validator::make($request->all(), $rule);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $existedLeaedTaskDetails->status = $request->formality_check;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $existedLeaedTaskDetails->reminderDate = $reminder_date;
                $existedLeaedTaskDetails->comment = $request->description;
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
                if ($existedLeaedTaskDetails->save()) {
                    $newNotification->user_id = $existedLeaedTask->user_id;
                    $newNotification->lead_id = $existedLeaedTask->lead_id;
                    $newNotification->task_id = $existedLeaedTask->id;
                    $newNotification->title = 'task On Hold ';
                    $newNotification->description = 'Formality check is on hold';
                    if ($newNotification->save()) {
                        $newLog =  new LeadLog();
                        $newLog->user_id = $existedLeaedTask->user_id;
                        $newLog->lead_id = $existedLeaedTask->lead_id;
                        $newLog->task_id = $existedLeaedTask->id;
                        $newLog->assign_by = Auth::id();
                        $newLog->description = "Formality check marked as incomplete ";

                        if ($newLog->save()) {
                            return redirect()->route('task.index')->with('success', 'Formality check status is Updated');
                        } else {
                            return redirect()->back()->error('message', " there is something wrong during log generate ");
                        }
                    }
                } else {
                    return redirect()->back()->error('message', " there is something wrong during hold the task ");
                }
            } else if ($request->formality_check == 1) {
                $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $existedLeaedTask->service_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->service_stage_id = $request->stage_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->assign_by = Auth::id();
                $newLeadtask->task_title = $newTaskTitle->title;
                if ($newLeadtask->save()) {
                    $existedLeaedTaskDetails->status = $request->formality_check;
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
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
                    if ($existedLeaedTaskDetails->save()) {

                        $newLeadTaskDeatails->task_id = $newLeadtask->id;
                        $newLeadTaskDeatails->dead_line = $deadlineDate;
                        $newLeadTaskDeatails->status = 0;

                        if ($newLeadTaskDeatails->save()) {
                            $newNotification->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                            $newNotification->lead_id = $existedLeaedTask->lead_id;
                            $newNotification->task_id = $newLeadtask->id;
                            $newNotification->title = 'Task Assigned';
                            $newNotification->description =  $userName . ' assigned you ' . $newTaskTitle->title . ' task';
                            $newNotification->status = 0;
                            if ($newNotification->save()) {
                                $LeadLog =  new LeadLog();
                                $LeadLog->user_id = $existedLeaedTask->user_id;
                                $LeadLog->lead_id = $existedLeaedTask->lead_id;
                                $LeadLog->task_id = $existedLeaedTask->id;
                                $LeadLog->assign_by = Auth::id();
                                $LeadLog->description = " formality check  marked as complete ";
                                if ($LeadLog->save()) {
                                    $newassignlog = new leadLog();
                                    $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                    $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                    $newassignlog->task_id = $newLeadtask->id;
                                    $newassignlog->assign_by = Auth::id();
                                    $newassignlog->description =  "Lead assigned for next task";
                                    if ($newassignlog->save()) {
                                        $id = $newLeadtask->id;
                                        return redirect()->route('task.index')->with('success', 'document verification completed');
                                    }
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong during upadate logs');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong during notification logs');
                            }
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong during update  new task details');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong during update  existed task details');
                    }
                }
            } else {
                return redirect()->back()->with('error', 'there is something wrong during update  new task');
            }
        } else {
            return redirect()->back()->with('error', 'no task found');
        }
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
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 4) {
            return redirect()->route('task.documentation', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 5) {
            return redirect()->route('task.clientApproval', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 6) {
            return redirect()->route('task.draftApplication', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 7) {
            return redirect()->route('task.formalityCheck', ['id' => $id]);
        }
        // For Patent...............
        else if ($taskDetails && $serviceId == 2 && $stageId == 19) {
            return redirect()->route('task.patentSendQuotation', ['id' => $id]);
        }
        else if ($taskDetails && $serviceId == 2 && $stageId == 20) {
            return redirect()->route('task.patentPaymentVerification', ['id' => $id]);
        }
        else if ($taskDetails && $serviceId == 2 && $stageId == 21) {
            return redirect()->route('task.patentPriorArt', ['id' => $id]);
        }
    }

    public function patentSendQuotation(Request $request,$id){
        $taskId = $id;
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
            $clientName = $task->lead->client_name;
        }
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        foreach ($taskDetails as $value) {
            $stage_id = $value->service_stage_id;
        }
        $getStage = ServiceStages::where('service_id', $serviceID)->where('id', '>', $stage_id)->first();
        $leadTaskdetials = LeadTaskDetail::find($taskDetailsId);
        return view('tasks/patent/send-quotation', compact('taskId', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'serviceName', 'clientName'));
        }

    public function patentPaymentVerification(Request $request, $id = null)
    {
        $taskId = $id;
        $taskList = LeadTask::find($taskId);
        $serviceStage = ServiceStages::where('id', '>', $taskList->service_stage_id)->where('service_id', 2)->first();
        $userList = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        $currentUser = User::find($taskList->user_id);
        $header_title_name = "Payment Verification";
        return view('tasks/patent/payment-verification', compact('header_title_name', 'taskId', 'taskList', 'serviceStage', 'userList', 'currentUser'));
    }

    public function patentPriorArt(Request $request ,$id){
        $taskId = $id;
        $taskDetails = LeadTask::with(['user', 'lead', 'leadTaskDetails', 'services', 'subService', 'serviceSatge'])
            ->where('id', $id)
            ->get();
        foreach ($taskDetails as $task) {
            $taskDetailsId = $task->id;
            $serviceName = $task->services->serviceName;
            $serviceID = $task->services->id;
            $clientName = $task->lead->client_name;
        }
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        foreach ($taskDetails as $value) {
            $stage_id = $value->service_stage_id;
        }
        $getStage = ServiceStages::where('service_id', $serviceID)->where('id', '>', $stage_id)->first();
        $header_title_name = "Prior Art";
        return view('tasks/patent/prior-art',compact('header_title_name','taskId','users','taskDetails','getStage'));
    }

    public function patentSubmitPriorArt(Request $request,$id){
        if($request->isMethod('post')){
            dd($request);
            $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
            $dead_line = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');

            $existedLeaedTask = LeadTask::with(['lead', 'services', 'subService', 'serviceSatge'])->where('id', $id)->first();
            $newLeadtask = new LeadTask();
            $newLeadTaskDeatails  = new LeadTaskDetail();
            $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
            $stageId = (int) $request->stage_id;
            $serviceId = $existedLeaedTask->services->id;
            $subServiceId = $existedLeaedTask->subService->id;
            $assignedStageName = ServiceStages::where('id', $stageId)->first();
           
            if($request->relevantpriorart == 1){
                $existedLeaedTaskDetails->update(['status' => 1,'status_date' => $verifiedDate]);
                    $newLeadtask->user_id = $request->assignUser;
                    $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                    $newLeadtask->service_id = $serviceId;
                    $newLeadtask->subservice_id = $subServiceId;
                    $newLeadtask->service_stage_id = $stageId;
                    $newLeadtask->assign_by = Auth::id();
                    $newLeadtask->task_title = $assignedStageName->description;
                    $newLeadtask->task_description = $request->description;
                if($newLeadtask->save()){
                    $newLeadTaskDeatails->task_id = $newLeadtask->id;
                    $newLeadTaskDeatails->status = 0;
                    $newLeadTaskDeatails->dead_line = $dead_line;
                    $newLeadTaskDeatails->save();                    
                }else{
                    return redirect()->route('task.index')->with('error','Some error is occure while updating lead task.');
                }
            }else{
                $existedLeaedTaskDetails->update(['status' => 1,'status_date' => $verifiedDate]);
            }            
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
                $imgData = json_encode($filePaths);    
                $existedLeaedTaskDetails->update(['attachment'=> $imgData]);
            }
            return redirect()->route('task.index')->with('success', 'Task is completed!');
        }
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

    public function sendNotification()
    {

        $followUpDate = LeadTaskDetail::with('leadTask')->where('status', 3)
        ->orderBy('id', 'desc')
        ->get();
        foreach ($followUpDate as $followDate) {            
            $currentDate = Carbon::now(); 
            $deadlineDate = Carbon::parse($followDate->reminderDate); 
            if ($currentDate->diffInDays($deadlineDate) <= 2) {
                LeadNotification::create([
                    'user_id' => $followDate->leadTask->user_id,
                    'lead_id' =>  $followDate->leadTask->lead_id,
                    'title' => 'dead line reminder',
                    'description' => 'apki plan ki vaidhata jald hi samapt hojye gi kirpya jaldi recharge krein',
                    'task_id' => $followDate->task_id,

                    'status' => 0,
                ]);

                dd('New notification created for task_id ' . $followDate->task_id);
            }
            // foreach ($followDate->leadNotifications as $deadLineDate) {
            //     
            // }
        }
    }
}



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