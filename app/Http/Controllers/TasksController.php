<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadTask;
use App\Models\LeadTaskDetail;
use App\Models\LeadLog;
use App\Models\User;
use App\Models\Payment;
use App\Models\Lead;
use App\Models\Evidence;
use App\Models\HearingDateDetails;
use App\Models\LeadNotification;
use App\Jobs\SendClientWelcomeEmail;
use App\Models\ServiceStages;
use App\Models\ServiceDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;
use App\Jobs\SendTaskCommanMailJob;
use App\Jobs\CommanDraftSend;


class TasksController extends Controller
{   
    private $viewPath = "tasks.";
    public function index(Request $request, $request_type = null)
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

        $taskDetails = LeadTask::with(['user', 'lead', 'leadTaskDetails', 'services', 'subService', 'serviceSatge'])->whereHas('lead', function ($q) {
            $q->where('status', 1);
        })->orderBy('created_at', 'desc');

        if ($assignUser->role != 1) {
            $taskDetails = $taskDetails->where('user_id', $assignUser->id);
        }
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';



        if ($request->key) {
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

        if ($request->leadId && $request->status && $request->user) {
            $taskDetails = $taskDetails->whereHas('lead', function ($q) use ($request) {
                $q->where('lead_id', $request->leadId);
            })
                ->whereHas('leadTaskDetails', function ($q) use ($request) {
                    $q->where('status', $request->status);
                })
                ->where('user_id', $request->user);
        } else if ($request->leadId && $request->status != null) {
            $taskDetails = $taskDetails->whereHas('lead', function ($q) use ($request) {
                $q->where('lead_id', $request->leadId);
            })
                ->whereHas('leadTaskDetails', function ($q) use ($request) {
                    $q->where('status', $request->status);
                });
        } else if ($request->leadId && $request->user) {


            $taskDetails = $taskDetails->whereHas('lead', function ($q) use ($request) {
                $q->where('lead_id', $request->leadId);
            })
                ->where('user_id', $request->user);
        } else if ($request->status && $request->user) {
            $taskDetails = $taskDetails->whereHas('leadTaskDetails', function ($q) use ($request) {
                $q->where('status', $request->status);
            })
                ->where('user_id', $request->user);
        } else if ($request->leadId) {
            $taskDetails = $taskDetails->whereHas('lead', function ($q) use ($request) {
                $q->where('lead_id', $request->leadId);
            });
        } else if ($request->status) {
            $taskDetails = $taskDetails->whereHas('leadTaskDetails', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        } else if ($request->user) {
            $taskDetails = $taskDetails->where('user_id', $request->user);
        } else {
            $taskDetails = $taskDetails->whereHas('leadTaskDetails', function ($query) {
                $query->where('status', '!=', 1)->where('status', '!=', 4);
            });
        }



        $taskDetailsDrp = $taskDetails->get();
        $taskDetails = $taskDetails->paginate(env("PAGINATION_COUNT"));

        if (empty($request_type) && $request_type != 'ajax') {
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
        if ($request->deadline) {

            $deadLineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        }
        $existedTaskDetails = LeadTask::with(['user', 'services', 'subService', 'serviceSatge', 'userAssignBy'])->where('id', $id)->first();
        $serviceId = $existedTaskDetails->services->id;
        $subServiceId = $existedTaskDetails->subService->id;
        $stageId = (int) $request->stage_id;
        $assignedStageName = ServiceStages::where('id', $stageId)->first();
        $user_assign_by = Auth::user()->name;
        $newExistedTaskDetails = new LeadTask();
        $lead_id = $existedTaskDetails->lead_id;
        $formattedCreatedDate = $existedTaskDetails->created_at->format('d M Y');

        if (!empty($request->input('assignUser'))) {
            $assignUser = $request->input('assignUser');
        } else {
            $assignUser = $request->input('alreadyAssign');
        }
        $rule = [
            'status' => 'required',
            'verified' => 'required',
            'assignUser' => 'nullable',
            'deadline' => 'nullable',
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
                    $existedLeadTaskDetails->status_date = $verifiedDate ?? null;
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
                        $LeadLog->remark = "Rejected";
                        $oldValue = [
                            'Status' => 'Pending',
                            'Assigned On' => $formattedCreatedDate,
                            'Assigned By' => $existedTaskDetails->userAssignBy->name,
                        ];
                        $newValue = [
                            'Status' => 'Rejected',
                            'Verified On' => $verifiedDate ?? null,
                            'Assigned To' => $existedTaskDetails->user->name,
                        ];

                        $LeadLog->old_value = json_encode($oldValue);
                        $LeadLog->new_value = json_encode($newValue);
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

            $newExistedTaskDetails->service_stage_id = $request->stage_id;
            if ($newExistedTaskDetails->save()) {
                $existedLeadTaskDetails->status = 1;
                $existedLeadTaskDetails->status_date = $verifiedDate ?? null;
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
                    $newTaskassign->dead_line = $deadLineDate ?? null;
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
                            $LeadLog->remark = "Completed";
                            $oldValue = [
                                'Status' => 'Pending',
                                'Assigned On' => $formattedCreatedDate,
                                'Assigned By' => $existedTaskDetails->userAssignBy->name,
                            ];
                            $newValue = [
                                'Status' => 'Completed',
                                'Verified On' => $verifiedDate ?? null,
                                'Assigned To' => $existedTaskDetails->user->name,
                            ];
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);

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
                                $newassignlog->remark =  'Assign';
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

    public function negotiatePrice(Request $request, $id)
    {
        $paymentData = Payment::where(['task_id' => $id])->first();        
        $gst = $request->negotiatePrice * 0.18;
        $old_price = $paymentData->service_price;
        $paymentData->old_service_price = $old_price;
        $paymentData->service_price = $request->negotiatePrice;
        $govtPrice = $paymentData->govt_price;
        $total = $request->negotiatePrice + $gst + $govtPrice;
        $paymentData->gst = $gst;
        $paymentData->total = $total;
        $paymentData->pending_amount = $total;        
        if ($paymentData->save()) {
            $leadLog = new LeadLog();
            $leadLog->user_id = $paymentData->leadTask->user_id;
            $leadLog->lead_id = $paymentData->lead_id;
            $leadLog->task_id = $paymentData->task_id;
            $leadLog->assign_by = Auth::id();
            $leadLog->remark = 'Negotiate Price';
            
            // Old and new values to be logged
            $oldValue = [
                'Status' => 'Pending',
                'Service Amount' => $paymentData->service_price,
                'Assigned By' => $paymentData->leadTask->userAssignBy->name,
            ];
            $newValue = [
                'Status' => 'Completed (Negotiate Price)',
                'Negotiate Price' => $request->negotiatePrice,
                'Assigned To' => $paymentData->leadTask->user->name,
            ];
            
            $leadLog->old_value = json_encode($oldValue);
            $leadLog->new_value = json_encode($newValue);
            $leadLog->description = 'The price is negotiated';
            if($leadLog->save()){
                return redirect()->back()->with('success', "Price is successfully updated!");
            }

        } else {
            return redirect()->back()->with('error', "Some error is occur while update price");
        }
    }

    public function sendQuotation(Request $request, $id)
    {
       
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');

        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedTask = LeadTask::with(['services', 'subService', 'lead', 'serviceSatge', 'userAssignBy'])->where('id', $id)->first();
        $existedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newPayment = new Payment();
        $newTaskAssigned = new LeadTask();
        $newTaskDetails = new LeadTaskDetail();
        $serviceId = $existedTask->services->id;
        $subServiceID = $existedTask->subService->id;
        $rule = [
            'verified' => 'required',
            'attachment' => 'array',
            'attachment.*' => 'nullable',
            'subject' => 'required',
            'deadline' => 'required',
            'service_price' => 'required|numeric',
            'govt_price' => 'required|numeric',

        ];
        $validtor =  Validator::make($request->all(), $rule);
        if ($validtor->fails()) {
            return redirect()->back()->withErrors($validtor)->withInput();
        }
        $formattedCreatedDate = $existedTask->created_at->format('d M Y');
        $mail = false;
        $subject = $request->subject;
        $service = $request->service;
        $service_price = $request->service_price;
        $govt_price = $request->govt_price;
        $gst = $request->gst ?? null;
        $total_without_gst = $service_price + $govt_price;
        $total = 0;

       
        if (!empty($gst)) {
            $gstApply  = "Apply";
            $gst_amount = $service_price * 0.18;
            $total = $total_without_gst + $gst_amount;
        } else {
            $gstApply  = "Not Apply";
            $total = $total_without_gst;
            $gst_amount = 0;
        }
        $quoted_price = $service_price +  $govt_price;
        $clientName = $existedTask->lead->client_name;
        $clientEmail = $existedTask->lead->email;
        $clientMobile = $existedTask->lead->mobile_number;
        $clientCompany = $existedTask->lead->company_name;

        $serviceName = $existedTask->services->serviceName;
        $subServiceName = $existedTask->subService->subServiceName;

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
            $existedTask->task_description = $request->description;
            $existedTask->save();
            if ($newTaskAssigned->save()) {
                $newTaskDetails->task_id = $newTaskAssigned->id;
                $newTaskDetails->status = 0;
                $newTaskDetails->dead_line = $deadlineDate;
                if ($newTaskDetails->save()) {
                    $existedTaskDetails->status_date = $verifiedDate ?? null;
                    $existedTaskDetails->status = 1;
                    $existedTaskDetails->mail_subject = $request->subject;
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

                        $newPayment->lead_id = $existedTask->lead_id;
                        $newPayment->task_id = $newTaskDetails->task_id;
                        $newPayment->reference_id = 0;
                        $newPayment->service_price = $service_price;
                        $newPayment->govt_price = $govt_price;
                        $newPayment->gst = $gst_amount ?? 0;
                        $newPayment->total = $total;
                        $newPayment->pending_amount = $total;
                        if ($newPayment->save()) {
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
                                $LeadLog->remark = "Quotation sent";
                                $oldValue = [
                                    'Status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' => $existedTask->userAssignBy->name,
                                    'Payment' => 'Pending'
                                ];
                                $newValue = [
                                    'Status' => 'Completed',
                                    'Sent On' => $verifiedDate ?? null,
                                    'Assigned To' => $existedTask->user->name,
                                    'Mail subject' => $request->subject ?? null,
                                    'Service Price' => $request->service_price ?? null,
                                    'Govt Price' => $request->govt_price,
                                    'gst' => $gstApply . " " . "₹" . $gst_amount . "" . "(%18)",
                                ];

                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);

                                $LeadLog->description = "Quotation sent to the client";
                                if ($LeadLog->save()) {
                                    $newassignlog = new leadLog();
                                    $newassignlog->user_id = $request->assignUser ?? $existedTask->user_id;
                                    $newassignlog->lead_id = $existedTask->lead_id;
                                    $newassignlog->task_id = $newTaskAssigned->id;
                                    $newassignlog->assign_by = Auth::id();
                                    $newassignlog->remark = "Assign";
                                    $newassignlog->description =  "Lead assigned for next task";
                                    if ($newassignlog->save()) {
                                        if ($mail== true) {
                                            SendTaskCommanMailJob::dispatch($subject, $service, $service_price,$gst_amount,$total, $govt_price, $clientName, $clientEmail, $userName,$clientMobile,$clientCompany,$serviceName,$subServiceName);                                            
                                        }
                                    }
                                    $id = $newTaskAssigned->id;
                                    return redirect()->route('task.index')
                                        ->with('success', 'Quotation sent successfully');
                                } else {
                                    return redirect()->back()->error('message', " there is something wrong during update logs ");
                                }
                            } else {
                                return redirect()->back()->error('message', " there is something wrong during update payment ");
                            }
                        } else {
                            return redirect()->back()->error('message', " there is something wrong during update existed tasl details ");
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
        $taskDetails = LeadTask::with(['user', 'payment', 'lead', 'leadTaskDetails', 'services', 'subService', 'serviceSatge'])
            ->where('id', $id)
            ->get();
        foreach ($taskDetails as $task) {
            $lastPayment = $task->payment->last();

            if ($lastPayment) {
                $paymentId = $lastPayment->id;
                $payamentDetails = Payment::where('id', $paymentId)->first();
            }
        }
        foreach ($taskDetails as $task) {
            $taskDetailsId = $task->id;
        }
        $firstPaymentId = Payment::where('task_id', $taskDetailsId)->OrderBy('id', 'ASC')->first();

        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        foreach ($taskDetails as $value) {
            $stageId = $value->service_stage_id;
        }
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($taskDetailsId);
        return view('tasks.tradeMark.payment_status', compact('id', 'firstPaymentId', 'payamentDetails', 'paymentId', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }

    public function paymentStatus(Request $request, $id)
    {
        // dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        if ($request->paymentDeadline) {

            $paymentDeadlineDate = Carbon::createFromFormat('d M Y', $request->input('paymentDeadline'))->format('Y-m-d');
        }
        if ($request->deadline) {

            $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        }
        $existedLeaedTask = LeadTask::with(['lead', 'services', 'subService', 'serviceSatge', 'userAssignBy'])->where('id', $id)->first();
        $client_id = $existedLeaedTask->lead->client_id;
        $existed_leadId = $existedLeaedTask->lead->id;

        $leadUpdate = Lead::find($existed_leadId);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $existedPayment = Payment::where('task_id', $id)->where('lead_id', $existedLeaedTask->lead_id)->where('id', $request->paymentId)->first();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $serviceId = $existedLeaedTask->services->id;
        $subServiceId = $existedLeaedTask->subService->id;
        $stageId = (int) $request->stage_id;
        $userName =  Auth::user()->name;
        $assignedStageName = ServiceStages::where('id', $stageId)->first();
        $rule = [
            'payment' => 'required',
            'deadline' => 'nullable',
            'verified' => 'required',
            'stage_id' => 'required',
            'attachment' => 'array',
            'attachment.*' => 'nullable',
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');

        if ($id) {
            if ($request->checkStatus == 0) {
                if ($request->payment == 1 || $request->payment == 2 || $request->payment == 3) {
                    $existedLeaedTask->task_description = $request->description;
                    $existedLeaedTask->save();
                    $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                    $newLeadtask->project_manager_id = $existedLeaedTask->project_manager_id;
                    $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                    $newLeadtask->service_id = $existedLeaedTask->service_id;
                    $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                    $newLeadtask->service_stage_id = $stageId;
                    $newLeadtask->sub_stage_id = null;
                    $newLeadtask->assign_by = Auth::id();
                    if ($newLeadtask->save()) {
                        if ($request->payment == 1) {
                            $existedLeaedTaskDetails->status = 1;
                        } else if ($request->payment == 2) {
                            $existedLeaedTaskDetails->status = 3;
                        } else if ($request->payment == 3) {
                            $existedLeaedTaskDetails->status = 3;
                        }
                        $existedLeaedTaskDetails->status_date = $verifiedDate;
                        if ($request->payment == 1) {
                            $existedLeaedTaskDetails->comment = 'Paid';
                        } else if ($request->payment == 2) {
                            $existedLeaedTaskDetails->comment = 'Partial Payment';
                        } else if ($request->payment == 3) {
                            $existedLeaedTaskDetails->comment = 'On Credit';
                        }
                        if ($request->payment == 1) {
                            $existedLeaedTaskDetails->reminderDate = Null;
                        } else if ($request->payment == 2) {
                            $existedLeaedTaskDetails->reminderDate = $paymentDeadlineDate;
                        } else if ($request->payment == 3) {
                            $existedLeaedTaskDetails->reminderDate = $paymentDeadlineDate;
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
                            $existedLeaedTaskDetails->attachment = json_encode($filePaths);
                        }
                        if ($existedLeaedTaskDetails->save()) {
                            $newPayment =  new Payment();
                            $newPayment->lead_id = $existedPayment->lead_id;
                            $newPayment->task_id = $existedPayment->task_id;
                            $newPayment->reference_id = $existedPayment->id;
                            $newPayment->service_price = $existedPayment->service_price;
                            $newPayment->govt_price = $existedPayment->govt_price;
                            $newPayment->gst = $existedPayment->gst;
                            $newPayment->total = $existedPayment->total;
                            if ($request->payment == 1) {
                                $newPayment->pending_amount = 0;
                                $newPayment->submitted_amount = $existedPayment->total;
                            } else if ($request->payment == 2 && $request->partial_payment != $existedPayment->total) {
                                $pending_amount = $existedPayment->pending_amount - $request->partial_payment;
                                $newPayment->pending_amount = $pending_amount;
                                $newPayment->submitted_amount = $request->partial_payment;
                            } else if ($request->payment == 2 && $request->partial_payment == $existedPayment->total) {
                                $newPayment->pending_amount = 0;
                                $newPayment->submitted_amount = $existedPayment->total;
                            } else if ($request->payment == 3) {
                                $newPayment->pending_amount = $existedPayment->pending_amount;
                                $newPayment->submitted_amount = null;
                            }
                            if ($newPayment->save()) {
                                $newLeadTaskDeatails->task_id = $newLeadtask->id;
                                $newLeadTaskDeatails->dead_line = $deadlineDate;
                                $newLeadTaskDeatails->status = 0;
                                if ($newLeadTaskDeatails->save()) {
                                    $userAssign =  $request->assignUser ?? $existedLeaedTask->user_id;
                                    $notification = new LeadNotification();
                                    $notification->user_id =  $userAssign;
                                    $notification->lead_id = $existedLeaedTask->lead_id;
                                    $notification->task_id = $newLeadtask->id;
                                    $notification->title = "Task Assigned";
                                    $notification->description =  $userName . ' assigned you ' . $assignedStageName->title . ' task';
                                    $notification->status = 0;
                                    if ($notification->save()) {
                                        $LeadLog = new LeadLog();
                                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                                        $LeadLog->task_id =  $existedLeaedTask->id;
                                        $LeadLog->assign_by = Auth::id();
                                        $remark = "";
                                        $paidAmount = "";
                                        if ($request->payment == 1) {

                                            $remark = 'Whole Ammount Paid';
                                            $paidAmount = $newPayment->total;
                                        } else if ($request->payment == 2 && $request->partial_payment != $existedPayment->total) {
                                            $remark = 'Partial Payment';
                                            $paidAmount = $newPayment->submitted_amount;
                                        } else if ($request->payment == 2 && $request->partial_payment == $existedPayment->total) {
                                            $remark = 'Partial Payment(Paid)';
                                            $paidAmount = $newPayment->submitted_amount;
                                        } else if ($request->payment == 3) {
                                            $remark = 'On Credit';
                                            $paidAmount = 0.00;
                                        }
                                        $LeadLog->remark = $remark;
                                        $oldValue = [
                                            'Status' => 'Pending',
                                            'Assigned On' => $formattedCreatedDate,
                                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                                            'Total Amount' => $existedPayment->total,
                                            'Paid Amount' => $existedPayment->submitted_amount,
                                            'pending_amount' => $existedPayment->pending_amount,

                                        ];

                                        $newValue = [
                                            'Status' => $remark,
                                            'Paid On' => $verifiedDate ?? null,
                                            'Assigned To' => $existedLeaedTask->user->name,
                                            'Total Amount' => $existedPayment->total,
                                            'Paid Amount' => $paidAmount,
                                            'pending_amount' => $newPayment->pending_amount,
                                        ];
                                        $LeadLog->old_value = json_encode($oldValue);
                                        $LeadLog->new_value = json_encode($newValue);
                                        $LeadLog->description = "payment status updated successfully";
                                        if ($LeadLog->save()) {
                                            $newassignlog = new leadLog();
                                            $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                            $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                            $newassignlog->task_id = $newLeadtask->id;
                                            $newassignlog->assign_by = Auth::id();
                                            $newassignlog->remark = 'Assign';

                                            $newassignlog->description =  "Lead assigned for next task";
                                            if ($newassignlog->save()) {
                                                if ($client_id == 0) {
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
                                                        $leadUpdate->client_id = $newClient->id;
                                                        $leadUpdate->client_name = $newClient->name;
                                                        if ($leadUpdate->save()) {
                                                            if ($mail == true) {
                                                                SendClientWelcomeEmail::dispatch($newClient, $randomNumber, $filePath = null, $type);
                                                            }
                                                            return redirect()->route('task.index')
                                                                ->with('success', 'payment status updated successfully');
                                                        } else {
                                                            return redirect()->back()->with('error', 'there is something wrong while  updateing existed lead');
                                                        }
                                                    } else {
                                                        return redirect()->back()->with('error', 'there is something wrong while  creating new client');
                                                    }
                                                } else {
                                                    return redirect()->route('task.index')->with('success', 'payment status updated successfully');
                                                }
                                            } else {
                                                return redirect()->back()->with('error', 'there is something wrong while updating logs');
                                            }
                                        } else {
                                            return redirect()->back()->with('error', 'there is something wrong while updating logs');
                                        }
                                    } else {
                                        return redirect()->back()->with('error', 'there is something wrong while updating notification');
                                    }
                                } else {
                                    return redirect()->back()->with('error', 'there is something worng while create new lead task details');
                                }
                            } else {
                                return redirect()->back()->with('eror', 'there is something wrong while updating payment status');
                            }
                        } else {
                            return redirect()->back()->with('eror', 'there is something wrong while updating exist task details');
                        }
                    } else {
                        return redirect()->back()->with('eror', 'there is something wrong while creating new task');
                    }
                }
            } else if ($request->checkStatus == 3 && $request->payment == 1) {
                $logStatus = $existedLeaedTaskDetails->comment;
                $oldPaidExitedDate = $existedLeaedTaskDetails->status_date;

                $newPayment = new Payment();
                $newPayment->lead_id = $existedPayment->lead_id;
                $newPayment->task_id = $existedPayment->task_id;
                $newPayment->reference_id = $existedPayment->id;
                $newPayment->reference_id = $existedPayment->id;
                $newPayment->service_price = $existedPayment->service_price;
                $newPayment->govt_price = $existedPayment->govt_price;
                $newPayment->gst = $existedPayment->gst;
                $newPayment->total = $existedPayment->total;
                $newPayment->pending_amount = $existedPayment->total;
                $pending_amount = $existedPayment->pending_amount;
                $newPayment->pending_amount = 0;
                $newPayment->submitted_amount = $pending_amount;
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($newPayment->save()) {
                    $existedLeaedTaskDetails->status = 1;
                    $existedLeaedTaskDetails->comment = 'Paid';
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->reminderDate = Null;
                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $filePaths = [];
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                        $LeadLog->task_id =  $existedLeaedTask->id;
                        $LeadLog->remark = "Paid";
                        $oldValue = [
                            'Status' => $logStatus,
                            'Last Payment On' => $oldPaidExitedDate ?? null,
                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $existedPayment->submitted_amount,
                            'pending_amount' => $existedPayment->pending_amount,
                        ];

                        $newValue = [
                            'Status' => 'Completed',
                            'Paid On' => $verifiedDate ?? null,
                            'Assigned To' => $existedLeaedTask->user->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $newPayment->submitted_amount,
                            'pending_amount' => $newPayment->pending_amount,
                        ];
                        $LeadLog->old_value = json_encode($oldValue);
                        $LeadLog->new_value = json_encode($newValue);
                        $LeadLog->assign_by = Auth::id();
                        $LeadLog->description = "payment status updated successfully";
                        if ($LeadLog->save()) {
                            return redirect()->route('task.index')
                                ->with('success', 'payment status updated successfully');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong while updating exist lead task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is soemthing wrong while updating payment status');
                }
            } else if ($request->checkStatus == 3 && $request->payment == 2) {
                $logStatus = $existedLeaedTaskDetails->comment;
                $oldPaidExitedDate = $existedLeaedTaskDetails->status_date;

                $newPayment = new Payment();
                $newPayment->lead_id = $existedPayment->lead_id;
                $newPayment->task_id = $existedPayment->task_id;
                $newPayment->reference_id = $request->firstPaymentId;
                $newPayment->service_price = $existedPayment->service_price;
                $newPayment->govt_price = $existedPayment->govt_price;
                $newPayment->gst = $existedPayment->gst;
                $newPayment->total = $existedPayment->total;
                $pending_amount = $existedPayment->pending_amount - $request->partial_payment;
                $newPayment->pending_amount = $pending_amount;
                $newPayment->submitted_amount = $request->partial_payment;
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($newPayment->save()) {
                    if ($newPayment->pending_amount == 0 ||  $newPayment->pending_amount == null || $newPayment->pending_amount == 0.00) {
                        $existedLeaedTaskDetails->status = 1;
                    } else {
                        $existedLeaedTaskDetails->status = 3;
                    }
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->comment = 'Partial Payment';
                    $existedLeaedTaskDetails->reminderDate = null;
                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $filePaths = [];
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                        $LeadLog->task_id =  $existedLeaedTask->id;
                        $LeadLog->remark =  'Partial Payment';
                        $oldValue = [
                            'Status' => $logStatus,
                            'Last payment On' => $oldPaidExitedDate,
                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $existedPayment->submitted_amount,
                            'pending_amount' => $existedPayment->pending_amount,
                        ];
                        $newValueStatus = "";
                        if ($newPayment->pending_amount == 0 ||  $newPayment->pending_amount == null || $newPayment->pending_amount == 0.00) {
                            $newValueStatus = 'Completed';
                        } else {
                            $newValueStatus = 'Partial Payment';
                        }

                        $newValue = [
                            'Status' => $newValueStatus,
                            'Paid On' => $verifiedDate ?? null,
                            'Assigned To' => $existedLeaedTask->user->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $newPayment->submitted_amount,
                            'pending_amount' => $newPayment->pending_amount,
                        ];
                        $LeadLog->old_value = json_encode($oldValue);
                        $LeadLog->new_value = json_encode($newValue);
                        $LeadLog->assign_by = Auth::id();
                        $LeadLog->description = "payment status updated successfully";
                        if ($LeadLog->save()) {
                            return redirect()->route('task.index')
                                ->with('success', 'payment status updated successfully');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong whil updating exist lead task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'while updating payment status');
                }
            } else if ($request->checkStatus == 3 && $request->payment == 3) {
                $logStatus = $existedLeaedTaskDetails->comment;
                $oldPaidExitedDate = $existedLeaedTaskDetails->status_date;
                $newPayment = new Payment();
                $newPayment->lead_id = $existedPayment->lead_id;
                $newPayment->task_id = $existedPayment->task_id;
                $newPayment->reference_id = $request->firstPaymentId;
                $newPayment->service_price = $existedPayment->service_price;
                $newPayment->govt_price = $existedPayment->govt_price;
                $newPayment->gst = $existedPayment->gst;
                $newPayment->total = $existedPayment->total;
                $pending_amount = $existedPayment->pending_amount;
                $newPayment->pending_amount = $pending_amount;
                $newPayment->submitted_amount = null;
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($newPayment->save()) {
                    if ($newPayment->pending_amount == 0 || $newPayment->pending_amount == Null) {
                        $existedLeaedTaskDetails->status = 1;
                    } else {
                        $existedLeaedTaskDetails->status = 3;
                    }
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->comment = "On Credit";

                    $existedLeaedTaskDetails->reminderDate = $paymentDeadlineDate;
                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $filePaths = [];
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                        $LeadLog->task_id =  $existedLeaedTask->id;
                        $LeadLog->assign_by = Auth::id();
                        $LeadLog->remark = "On Credit";
                        $oldValue = [
                            'Status' => $logStatus,
                            'Last payment On' => $oldPaidExitedDate ?? null,
                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $existedPayment->submitted_amount,
                            'Pending Amount' => $existedPayment->pending_amount,
                        ];
                        $newValue = [
                            'Status' => 'On Credit',
                            'Verified On' => $verifiedDate ?? null,
                            'Assigned To' => $existedLeaedTask->user->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => 0.00,
                            'Pending Amount' => $existedPayment->pending_amount,
                        ];
                        $LeadLog->old_value = json_encode($oldValue);
                        $LeadLog->new_value = json_encode($newValue);
                        $LeadLog->description = "payment status updated successfully";
                        if ($LeadLog->save()) {
                            return redirect()->route('task.index')
                                ->with('success', 'payment status updated successfully');
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong while updating log');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something error while exist lead task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is soemthing wrong while updating payment status');
                }
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
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        if ($request->reminder_date) {

            $reminder_date = Carbon::createFromFormat('d M Y', $request->input('reminder_date'))->format('Y-m-d');
        }
        if ($request->deadline) {

            $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        }

        $existedLeaedTask = LeadTask::with(['lead', 'services', 'subService', 'serviceSatge', 'userAssignBy'])->where('id', $id)->first();
       
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;

        $formattedCreatedDate = (!empty($existedLeaedTaskDetails->status_date))
            ? $existedLeaedTaskDetails->status_date
            : $existedLeaedTask->created_at->format('d M Y');

        $rule = [
            'document' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'nullable'
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
                $logStatus = !empty($existedLeaedTaskDetails->comment) ? $existedLeaedTaskDetails->comment : 'Pending';

                $existedLeaedTask->task_description = $request->description;
                $existedLeaedTask->save();
                $existedLeaedTaskDetails->status = $request->document;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $existedLeaedTaskDetails->reminderDate = $reminder_date;
                $existedLeaedTaskDetails->comment = 'On Hold';
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
                    $newNotification->description = 'document verification is on hold for incomplete document';
                    if ($newNotification->save()) {
                        $newLog =  new LeadLog();
                        $newLog->user_id = $existedLeaedTask->user_id;
                        $newLog->lead_id = $existedLeaedTask->lead_id;
                        $newLog->task_id = $existedLeaedTask->id;
                        $newLog->assign_by = Auth::id();
                        $newLog->remark = 'On Hold';
                        $oldValue = [
                            'status' => $logStatus,
                            'Assigned On' => $formattedCreatedDate,
                            'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                        ];
                        $newValue = [
                            'status' => 'On Hold',
                            'Hold On' => $request->verified,
                            'Assigned To' =>  $existedLeaedTask->user->name,
                        ];
                        $newLog->old_value = json_encode($oldValue);
                        $newLog->new_value = json_encode($newValue);

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
                $logStatus = !empty($existedLeaedTaskDetails->comment) ? $existedLeaedTaskDetails->comment : 'Pending';

                $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $existedLeaedTask->service_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->service_stage_id = $request->stage_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->assign_by = Auth::id();
                $newLeadtask->task_title = $newTaskTitle->title;
                $existedLeaedTask->task_description = $request->description;
                $existedLeaedTask->save();
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
                                $LeadLog->remark = "Document Verified";
                                $oldValue = [
                                    'status' => $logStatus,
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'status' => 'Completed',
                                    'Verified On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                ];
                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);
                                $LeadLog->description = " Formality check  marked as pass ";
                                if ($LeadLog->save()) {
                                    $newassignlog = new leadLog();
                                    $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                    $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                    $newassignlog->task_id = $newLeadtask->id;
                                    $newassignlog->assign_by = Auth::id();
                                    $newassignlog->remark = "Assign";
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

    public function DocumentDraft($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }
        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->where('archive', 1)->get();
        $applicationNumber = ServiceDetail::where('lead_id', $taskDetails->lead_id)
        ->first();
        
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.sent_draft', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'applicationNumber'));
    }

    public function DocumentDraftStatus(Request $request, $id)
    {   
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::with('services', 'lead', 'userAssignBy')->where('id', $id)->first();
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newServiceDetails = ServiceDetail::where('lead_id' ,$existedLeaedTask->lead_id)->first();
       
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $service = $existedLeaedTask->services->serviceName;
        $clientName = $existedLeaedTask->lead->client_name;
        $clientEmail = $existedLeaedTask->lead->email;
        $clientMobile = $existedLeaedTask->lead->mobile_number;
        $companyName = $existedLeaedTask->lead->company_name;
        $remark = $request->description ?? null;
        $subject = "Document Sent to the client for approval";
        $leadID = $existedLeaedTask->lead->id;
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');
        $applicationNumber = LeadTask::where('lead_id', $existedLeaedTask->lead_id)
        ->orderBy('id', 'asc')
        ->first();
        $rule = [
            
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            $newServiceDetails->application_number = $request->application_number ?? null;
            $newServiceDetails->save();
            $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
            $newLeadtask->lead_id = $existedLeaedTask->lead_id;
            $newLeadtask->service_id = $existedLeaedTask->service_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->service_stage_id = $request->stage_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->assign_by = Auth::id();
            $newLeadtask->task_title = $newTaskTitle->title;
            if ($newLeadtask->save()) {
                $existedLeaedTaskDetails->status = 1;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $existedLeaedTaskDetails->mail_subject = $request->mail_subject;
                $filePaths = [];
                if ($request->hasFile('attachment')) {
                    $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0755, true);
                    }
                    foreach ($request->file('attachment') as $file) {
                        if ($file->isValid()) {
                            $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                            $file->move($folderPath, $fileName);
                            $filePaths[] = $fileName;
                        }
                    }
                    $existedLeaedTaskDetails->attachment = json_encode($filePaths);
                }
                $assignApplicationNumber = $applicationNumber->application_number;
                $trademarkName = $applicationNumber->applied_for;
                CommanDraftSend::dispatch( 
                    $subject,
                    $service,
                    $leadID,
                    $companyName,
                    !empty($filePaths) ? $filePaths : null,
                    $clientName,
                    $clientEmail,
                    $clientMobile,
                    $trademarkName,
                    $assignApplicationNumber
                );
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
                            $LeadLog->remark = 'Document Draft';
                            $oldValue = [
                                'status' => 'Pending',
                                'Assigned On' => $formattedCreatedDate,
                                'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                            ];
                            $newValue = [
                                'status' => 'Completed',
                                'Draft Sent On' => $request->verified,
                                'Assigned To' =>  $existedLeaedTask->user->name,
                                'Application Number' => $request->application_number
                            ];
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);
                            $LeadLog->description = "Document Draft successfully";
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = 'Assign';

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
            } else {
                return redirect()->back()->with('error', 'there is something wrong during update  new task');
            }
        } else {
            return redirect()->back()->with('error', "no task found");
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
        $existedLeaedTask = LeadTask::with('services', 'lead', 'userAssignBy')->where('id', $id)->first();
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $userName = Auth::user()->name;
        $newTaskTitle = ServiceStages::find($request->stage_id);
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');


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
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
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
                            $LeadLog->remark = 'Client Approval';
                            $oldValue = [
                                'status' => 'Pending',
                                'Assigned On' => $formattedCreatedDate,
                                'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                            ];
                            $newValue = [
                                'status' => 'Completed',
                                'Approved On' => $request->verified,
                                'Assigned To' =>  $existedLeaedTask->user->name,
                            ];
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);

                            $LeadLog->description = " Client document verification marked as correct";
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = "Assign";
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
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');

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
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
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
                            $LeadLog->remark = 'Submit Application';
                            $oldValue = [
                                'status' => 'Pending',
                                'Assigned On' => $formattedCreatedDate,
                                'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                            ];
                            $newValue = [
                                'status' => 'Completed',
                                'Submit On' => $request->verified,
                                'Assigned To' =>  $existedLeaedTask->user->name,
                            ];
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);
                            $LeadLog->description = "Submit the application on portal successfully";
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = 'Assign';

                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'document verification completed');
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong while updatng log');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong while updatng log');
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
        $applicationNumber = ServiceDetail::where('lead_id', $taskDetails->lead_id)
        ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.formality_check', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'applicationNumber'));
    }

    public function formalityCheckStatus(Request $request, $id)
    {
        if ($request->verified) {

            $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        }
        if ($request->reminder_date) {

            $reminder_date = Carbon::createFromFormat('d M Y', $request->input('reminder_date'))->format('Y-m-d');
        }
        if ($request->deadline) {

            $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        }
        $existedLeaedTask = LeadTask::with(['lead', 'services', 'subService', 'serviceSatge', 'userAssignBy'])->where('id', $id)->first();
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $addApplicationNumber = LeadTask::where('lead_id', $existedLeaedTask->lead_id)
        ->orderBy('id', 'asc')
        ->first();
        
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $formattedCreatedDate = (!empty($existedLeaedTaskDetails->status_date))
            ? $existedLeaedTaskDetails->status_date
            : $existedLeaedTask->created_at->format('d M Y');

        $rule = [
            'formality_check' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'nullable'
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
                    'reminder_date' => 'nullable',

                ];
                $validator = Validator::make($request->all(), $rule);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $logStatus = !empty($existedLeaedTaskDetails->comment) ? $existedLeaedTaskDetails->comment : 'Pending';
                $existedLeaedTaskDetails->status = $request->formality_check;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $existedLeaedTaskDetails->comment = "On Hold";
                $existedLeaedTaskDetails->reminderDate = $reminder_date;
                $existedLeaedTask->task_description = $request->description;
                $existedLeaedTask->save();
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
                        $newLog->remark = 'On Hold';
                        $oldValue = [
                            'status' => $logStatus,
                            'Assigned On' => $formattedCreatedDate,
                            'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                        ];
                        $newValue = [
                            'status' => 'On Hold',
                            'Hold On' => $request->verified,
                            'Assigned To' =>  $existedLeaedTask->user->name,
                        ];
                        $newLog->old_value = json_encode($oldValue);
                        $newLog->new_value = json_encode($newValue);
                        $newLog->description = "Formality check marked as on hold ";

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
                // $addApplicationNumber->application_number = $request->application_number;
                // $addApplicationNumber->save();
                $logStatus = !empty($existedLeaedTaskDetails->comment) ? $existedLeaedTaskDetails->comment : 'Pending';
                $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $existedLeaedTask->service_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->service_stage_id = $request->stage_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->assign_by = Auth::id();
                $newLeadtask->task_title = $newTaskTitle->title;
                $existedLeaedTask->task_description = $request->description;
                $existedLeaedTask->save();
                if ($newLeadtask->save()) {
                    $existedLeaedTaskDetails->status = $request->formality_check;
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->mail_subject = $request->mail_subject;
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
                                $LeadLog->remark = 'Formality check pass';
                                $oldValue = [
                                    'status' => $logStatus,
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'status' => 'Completed',
                                    'pass On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                ];
                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);
                                $LeadLog->description = " formality check  marked as complete ";
                                if ($LeadLog->save()) {
                                    $newassignlog = new leadLog();
                                    $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                    $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                    $newassignlog->task_id = $newLeadtask->id;
                                    $newassignlog->assign_by = Auth::id();
                                    $newassignlog->remark = "Assign";

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

    public function initialExamination($id)
    {

        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->where('archive', 1)->get();
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $onHideSatge = ServiceStages::where('service_id', 1)->where('id', '=', 16)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.intitial_examination', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'onHideSatge'));
    }

    public function initialExaminationStatus(Request $request, $id)
    {
        //  dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');
        $rule = [
            'initial_examination' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
            $newLeadtask->lead_id = $existedLeaedTask->lead_id;
            $newLeadtask->service_id = $existedLeaedTask->service_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->service_stage_id = $request->stage_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->assign_by = Auth::id();
            $newLeadtask->task_title = $newTaskTitle->title;
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
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
                            $logStatus = "";
                            if ($request->initial_examination == 0) {
                                $logStatus = "Accepted";
                            } else if ($request->initial_examination == 1) {
                                $logStatus = "Objected";
                            }
                            $LeadLog->remark = $logStatus;
                            $oldValue = [
                                'status' => 'Pending',
                                'Assigned On' => $formattedCreatedDate,
                                'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                            ];
                            $newValue = [
                                'status' => 'Completed as' . " " . $logStatus,
                                'Submit On' => $request->verified,
                                'Assigned To' =>  $existedLeaedTask->user->name,
                            ];
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);
                            $LeadLog->description = " Examination status marked as" . " " . $logStatus;
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = "Assign";

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
            } else {
                return redirect()->back()->with('error', 'there is something wrong during update  new task');
            }
        } else {
            return redirect()->back()->with('erroe', "no task found");
        }
    }

    public function replyAdded($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->where('archive', 1)->get();

        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $onHideSatge = ServiceStages::where('service_id', 1)->where('id', '=', 14)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.reply_govt_portal', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'onHideSatge'));
    }

    public function replyAddedStatus(Request $request, $id)
    {

        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');

        $rule = [

            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {

            $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
            $newLeadtask->lead_id = $existedLeaedTask->lead_id;
            $newLeadtask->service_id = $existedLeaedTask->service_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->service_stage_id = $request->stage_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
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

                            $LeadLog->remark = "Add reply";
                            $oldValue = [
                                'status' => 'Pending',
                                'Assigned On' => $formattedCreatedDate,
                                'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                            ];
                            $newValue = [
                                'status' => "Completed",
                                'Reply Add On' => $request->verified,
                                'Assigned To' =>  $existedLeaedTask->user->name,
                            ];
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);
                            $LeadLog->description = " Reply added on the objection. ";
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = "Assign";

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
            } else {
                return redirect()->back()->with('error', 'there is something wrong during update  new task');
            }
        } else {
            return redirect()->back()->with('erroe', "no task found");
        }
    }

    public function govtPortalReply($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->where('archive', 1)->get();

        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $onHideSatge = ServiceStages::where('service_id', 1)->where('id', '=', 16)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.objection_reply_status', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'onHideSatge'));
    }

    public function govtPortalReplyStatus(Request $request, $id)
    {
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');

        $rule = [
            'reply_status' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
            $newLeadtask->lead_id = $existedLeaedTask->lead_id;
            $newLeadtask->service_id = $existedLeaedTask->service_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->service_stage_id = $request->stage_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
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
                            $logStatus = "";
                            if ($request->reply_status == 0) {
                                $logStatus = "Accepted";
                            } else if ($request->reply_status == 1) {
                                $logStatus = "Show cause hearing";
                            }
                            $LeadLog->remark = $logStatus;
                            $oldValue = [
                                'status' => 'Pending',
                                'Assigned On' => $formattedCreatedDate,
                                'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                            ];
                            $newValue = [
                                'status' => 'Completed as' . " " . $logStatus,
                                'Verified On' => $request->verified,
                                'Assigned To' =>  $existedLeaedTask->user->name,
                            ];
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);
                            $LeadLog->description = " Examination status marked as objected ";
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = "Assign";
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
            } else {
                return redirect()->back()->with('error', 'there is something wrong during update  new task');
            }
        } else {
            return redirect()->back()->with('erroe', "no task found");
        }
    }

    public function  examinationInform($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }
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
            $header_title_name = $value->serviceSatge->title;
        }
        $getStage = ServiceStages::where('service_id', $serviceID)->where('id', '>', $stage_id)->first();
        $leadTaskdetials = LeadTaskDetail::find($taskDetailsId);
        return view('tasks.tradeMark.after_examination_inform_client', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'serviceName', 'clientName'));
    }

    public function examinationInformStatus(Request $request, $id)
    {
        // dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedTask = LeadTask::with(['services', 'subService', 'lead', 'serviceSatge', 'userAssignBy'])->where('id', $id)->first();
        $existedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newPayment = new Payment();
        $newTaskAssigned = new LeadTask();
        $newTaskDetails = new LeadTaskDetail();
        $serviceId = $existedTask->services->id;
        $subServiceID = $existedTask->subService->id;
        $formattedCreatedDate = $existedTask->created_at->format('d M Y');
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
        $gst = $request->gst ?? null;
        $total_without_gst = $service_price + $govt_price;
        if (!empty($gst)) {
            $gstApply  = "Apply";
            $gst_amount = $service_price * 0.18;
            $total = $total_without_gst + $gst_amount;
        } else {
            $gstApply  = "Not Apply";
            $total = $total_without_gst;
            $gst_amount = 0;
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
            $existedTask->task_description = $request->description;
            $existedTask->save();
            if ($newTaskAssigned->save()) {
                $newTaskDetails->task_id = $newTaskAssigned->id;
                $newTaskDetails->status = 0;
                $newTaskDetails->dead_line = $deadlineDate;
                if ($newTaskDetails->save()) {
                    $existedTaskDetails->status_date = $verifiedDate;
                    $existedTaskDetails->status = 1;
                    $existedTaskDetails->mail_subject = $request->subject;
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

                        $newPayment->lead_id = $existedTask->lead_id;
                        $newPayment->task_id = $newTaskDetails->task_id;
                        $newPayment->reference_id = 0;
                        $newPayment->service_price = $service_price;
                        $newPayment->govt_price = $govt_price;
                        $newPayment->gst = $gst_amount ?? 0;
                        $newPayment->total = $total;
                        $newPayment->pending_amount = $total;
                        if ($newPayment->save()) {
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
                                $LeadLog->remark = "Completed";
                                $oldValue = [
                                    'Status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' => $existedTask->userAssignBy->name,
                                    'Payment' => 'Pending'
                                ];
                                $newValue = [
                                    'Status' => 'Quotation sent',
                                    'Sent On' => $verifiedDate ?? null,
                                    'Assigned To' => $existedTask->user->name,
                                    'Mail subject' => $request->subject ?? null,
                                    'Service Price' => $request->service_price ?? null,
                                    'Govt Price' => $request->govt_price,
                                    'gst' => $gstApply . " " . "₹" . $gst_amount . "" . "(%18)",
                                ];

                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);
                                $LeadLog->description = "Quotation sent to the client";
                                if ($LeadLog->save()) {
                                    $newassignlog = new leadLog();
                                    $newassignlog->user_id = $request->assignUser ?? $existedTask->user_id;
                                    $newassignlog->lead_id = $existedTask->lead_id;
                                    $newassignlog->task_id = $newTaskAssigned->id;
                                    $newassignlog->assign_by = Auth::id();
                                    $newassignlog->remark = "Assign";

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
                                return redirect()->back()->error('message', " there is something wrong during update logs ");
                            }
                        } else {
                            return redirect()->back()->error('message', " there is something wrong during update existed task details ");
                        }
                    } else {
                        return redirect()->back()->error('message', " there is something wrong during update existed task details ");
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

    public function examinationPayment($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }
        $header_title_name = "payment Status";
        $taskDetails = LeadTask::with(['user', 'payment', 'lead', 'leadTaskDetails', 'services', 'subService', 'serviceSatge'])
            ->where('id', $id)
            ->get();
        foreach ($taskDetails as $task) {
            $lastPayment = $task->payment->last();

            if ($lastPayment) {
                $paymentId = $lastPayment->id;
                $payamentDetails = Payment::where('id', $paymentId)->first();
            }
        }
        foreach ($taskDetails as $task) {
            $taskDetailsId = $task->id;
        }
        $firstPaymentId = Payment::where('task_id', $taskDetailsId)->OrderBy('id', 'ASC')->first();

        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        foreach ($taskDetails as $value) {
            $stageId = $value->service_stage_id;
        }
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($taskDetailsId);
        return view('tasks.tradeMark.examination_payment', compact('id', 'firstPaymentId', 'payamentDetails', 'paymentId', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }

    public function examinationPaymentStatus(Request $request, $id)
    {
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        if ($request->paymentDeadline) {

            $paymentDeadlineDate = Carbon::createFromFormat('d M Y', $request->input('paymentDeadline'))->format('Y-m-d');
        }
        if ($request->deadline) {

            $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        }
        $existedLeaedTask = LeadTask::with(['lead', 'services', 'subService', 'serviceSatge', 'userAssignBy'])->where('id', $id)->first();
        $client_id = $existedLeaedTask->lead->client_id;
        $existed_leadId = $existedLeaedTask->lead->id;

        $leadUpdate = Lead::find($existed_leadId);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $existedPayment = Payment::where('task_id', $id)->where('lead_id', $existedLeaedTask->lead_id)->where('id', $request->paymentId)->first();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $serviceId = $existedLeaedTask->services->id;
        $subServiceId = $existedLeaedTask->subService->id;
        $stageId = (int) $request->stage_id;
        $userName =  Auth::user()->name;
        $assignedStageName = ServiceStages::where('id', $stageId)->first();
        $rule = [
            'payment' => 'required',
            'deadline' => 'nullable',
            'verified' => 'required',
            'stage_id' => 'required',
            'attachment' => 'array',
            'attachment.*' => 'nullable',
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');

        if ($id) {
            if ($request->checkStatus == 0) {
                if ($request->payment == 1 || $request->payment == 2 || $request->payment == 3) {
                    $existedLeaedTask->task_description = $request->description;
                    $existedLeaedTask->save();
                    $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                    $newLeadtask->project_manager_id = $existedLeaedTask->project_manager_id;
                    $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                    $newLeadtask->service_id = $existedLeaedTask->service_id;
                    $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                    $newLeadtask->service_stage_id = $stageId;
                    $newLeadtask->sub_stage_id = null;
                    $newLeadtask->assign_by = Auth::id();
                    if ($newLeadtask->save()) {
                        if ($request->payment == 1) {
                            $existedLeaedTaskDetails->status = 1;
                        } else if ($request->payment == 2) {
                            $existedLeaedTaskDetails->status = 3;
                        } else if ($request->payment == 3) {
                            $existedLeaedTaskDetails->status = 3;
                        }
                        $existedLeaedTaskDetails->status_date = $verifiedDate;
                        if ($request->payment == 1) {
                            $existedLeaedTaskDetails->comment = 'Paid';
                        } else if ($request->payment == 2) {
                            $existedLeaedTaskDetails->comment = 'Partial Payment';
                        } else if ($request->payment == 3) {
                            $existedLeaedTaskDetails->comment = 'On Credit';
                        }
                        if ($request->payment == 1) {
                            $existedLeaedTaskDetails->reminderDate = Null;
                        } else if ($request->payment == 2) {
                            $existedLeaedTaskDetails->reminderDate = $paymentDeadlineDate;
                        } else if ($request->payment == 3) {
                            $existedLeaedTaskDetails->reminderDate = $paymentDeadlineDate;
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
                            $existedLeaedTaskDetails->attachment = json_encode($filePaths);
                        }
                        if ($existedLeaedTaskDetails->save()) {
                            $newPayment =  new Payment();
                            $newPayment->lead_id = $existedPayment->lead_id;
                            $newPayment->task_id = $existedPayment->task_id;
                            $newPayment->reference_id = $existedPayment->id;
                            $newPayment->service_price = $existedPayment->service_price;
                            $newPayment->govt_price = $existedPayment->govt_price;
                            $newPayment->gst = $existedPayment->gst;
                            $newPayment->total = $existedPayment->total;
                            if ($request->payment == 1) {
                                $newPayment->pending_amount = 0;
                                $newPayment->submitted_amount = $existedPayment->total;
                            } else if ($request->payment == 2 && $request->partial_payment != $existedPayment->total) {
                                $pending_amount = $existedPayment->pending_amount - $request->partial_payment;
                                $newPayment->pending_amount = $pending_amount;
                                $newPayment->submitted_amount = $request->partial_payment;
                            } else if ($request->payment == 2 && $request->partial_payment == $existedPayment->total) {
                                $newPayment->pending_amount = 0;
                                $newPayment->submitted_amount = $existedPayment->total;
                            } else if ($request->payment == 3) {
                                $newPayment->pending_amount = $existedPayment->pending_amount;
                                $newPayment->submitted_amount = null;
                            }
                            if ($newPayment->save()) {
                                $newLeadTaskDeatails->task_id = $newLeadtask->id;
                                $newLeadTaskDeatails->dead_line = $deadlineDate;
                                $newLeadTaskDeatails->status = 0;
                                if ($newLeadTaskDeatails->save()) {
                                    $userAssign =  $request->assignUser ?? $existedLeaedTask->user_id;
                                    $notification = new LeadNotification();
                                    $notification->user_id =  $userAssign;
                                    $notification->lead_id = $existedLeaedTask->lead_id;
                                    $notification->task_id = $newLeadtask->id;
                                    $notification->title = "Task Assigned";
                                    $notification->description =  $userName . ' assigned you ' . $assignedStageName->title . ' task';
                                    $notification->status = 0;
                                    if ($notification->save()) {
                                        $LeadLog = new LeadLog();
                                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                                        $LeadLog->task_id =  $existedLeaedTask->id;
                                        $LeadLog->assign_by = Auth::id();
                                        $remark = "";
                                        $paidAmount = "";
                                        if ($request->payment == 1) {

                                            $remark = 'Whole Ammount Paid';
                                            $paidAmount = $newPayment->total;
                                        } else if ($request->payment == 2 && $request->partial_payment != $existedPayment->total) {
                                            $remark = 'Partial Payment';
                                            $paidAmount = $newPayment->submitted_amount;
                                        } else if ($request->payment == 2 && $request->partial_payment == $existedPayment->total) {
                                            $remark = 'Partial Payment(Paid)';
                                            $paidAmount = $newPayment->submitted_amount;
                                        } else if ($request->payment == 3) {
                                            $remark = 'On Credit';
                                            $paidAmount = 0.00;
                                        }
                                        $LeadLog->remark = $remark;
                                        $oldValue = [
                                            'Status' => 'Pending',
                                            'Assigned On' => $formattedCreatedDate,
                                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                                            'Total Amount' => $existedPayment->total,
                                            'Paid Amount' => $existedPayment->submitted_amount,
                                            'pending_amount' => $existedPayment->pending_amount,

                                        ];

                                        $newValue = [
                                            'Status' => $remark,
                                            'Paid On' => $verifiedDate ?? null,
                                            'Assigned To' => $existedLeaedTask->user->name,
                                            'Total Amount' => $existedPayment->total,
                                            'Paid Amount' => $paidAmount,
                                            'pending_amount' => $newPayment->pending_amount,
                                        ];
                                        $LeadLog->old_value = json_encode($oldValue);
                                        $LeadLog->new_value = json_encode($newValue);
                                        $LeadLog->description = "payment status updated successfully";
                                        if ($LeadLog->save()) {
                                            $newassignlog = new leadLog();
                                            $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                            $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                            $newassignlog->task_id = $newLeadtask->id;
                                            $newassignlog->assign_by = Auth::id();
                                            $newassignlog->remark = 'Assign';

                                            $newassignlog->description =  "Lead assigned for next task";
                                            if ($newassignlog->save()) {
                                                if ($client_id == 0) {
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
                                                        $leadUpdate->client_id = $newClient->id;
                                                        $leadUpdate->client_name = $newClient->name;
                                                        if ($leadUpdate->save()) {
                                                            if ($mail == true) {
                                                                SendClientWelcomeEmail::dispatch($newClient, $randomNumber, $filePath = null, $type);
                                                            }
                                                            return redirect()->route('task.index')
                                                                ->with('success', 'payment status updated successfully');
                                                        } else {
                                                            return redirect()->back()->with('error', 'there is something wrong while  updateing existed lead');
                                                        }
                                                    } else {
                                                        return redirect()->back()->with('error', 'there is something wrong while  creating new client');
                                                    }
                                                } else {
                                                    return redirect()->route('task.index')->with('success', 'payment status updated successfully');
                                                }
                                            } else {
                                                return redirect()->back()->with('error', 'there is something wrong while updating logs');
                                            }
                                        } else {
                                            return redirect()->back()->with('error', 'there is something wrong while updating logs');
                                        }
                                    } else {
                                        return redirect()->back()->with('error', 'there is something wrong while updating notification');
                                    }
                                } else {
                                    return redirect()->back()->with('error', 'there is something worng while create new lead task details');
                                }
                            } else {
                                return redirect()->back()->with('eror', 'there is something wrong while updating payment status');
                            }
                        } else {
                            return redirect()->back()->with('eror', 'there is something wrong while updating exist task details');
                        }
                    } else {
                        return redirect()->back()->with('eror', 'there is something wrong while creating new task');
                    }
                }
            } else if ($request->checkStatus == 3 && $request->payment == 1) {
                $logStatus = $existedLeaedTaskDetails->comment;
                $oldPaidExitedDate = $existedLeaedTaskDetails->status_date;

                $newPayment = new Payment();
                $newPayment->lead_id = $existedPayment->lead_id;
                $newPayment->task_id = $existedPayment->task_id;
                $newPayment->reference_id = $existedPayment->id;
                $newPayment->reference_id = $existedPayment->id;
                $newPayment->service_price = $existedPayment->service_price;
                $newPayment->govt_price = $existedPayment->govt_price;
                $newPayment->gst = $existedPayment->gst;
                $newPayment->total = $existedPayment->total;
                $newPayment->pending_amount = $existedPayment->total;
                $pending_amount = $existedPayment->pending_amount;
                $newPayment->pending_amount = 0;
                $newPayment->submitted_amount = $pending_amount;
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($newPayment->save()) {
                    $existedLeaedTaskDetails->status = 1;
                    $existedLeaedTaskDetails->comment = 'Paid';
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->reminderDate = Null;
                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $filePaths = [];
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                        $LeadLog->task_id =  $existedLeaedTask->id;
                        $LeadLog->remark = "Paid";
                        $oldValue = [
                            'Status' => $logStatus,
                            'Last Payment On' => $oldPaidExitedDate ?? null,
                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $existedPayment->submitted_amount,
                            'pending_amount' => $existedPayment->pending_amount,
                        ];

                        $newValue = [
                            'Status' => 'Completed',
                            'Paid On' => $verifiedDate ?? null,
                            'Assigned To' => $existedLeaedTask->user->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $newPayment->submitted_amount,
                            'pending_amount' => $newPayment->pending_amount,
                        ];
                        $LeadLog->old_value = json_encode($oldValue);
                        $LeadLog->new_value = json_encode($newValue);
                        $LeadLog->assign_by = Auth::id();
                        $LeadLog->description = "payment status updated successfully";
                        if ($LeadLog->save()) {
                            return redirect()->route('task.index')
                                ->with('success', 'payment status updated successfully');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong while updating exist lead task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is soemthing wrong while updating payment status');
                }
            } else if ($request->checkStatus == 3 && $request->payment == 2) {
                $logStatus = $existedLeaedTaskDetails->comment;
                $oldPaidExitedDate = $existedLeaedTaskDetails->status_date;

                $newPayment = new Payment();
                $newPayment->lead_id = $existedPayment->lead_id;
                $newPayment->task_id = $existedPayment->task_id;
                $newPayment->reference_id = $request->firstPaymentId;
                $newPayment->service_price = $existedPayment->service_price;
                $newPayment->govt_price = $existedPayment->govt_price;
                $newPayment->gst = $existedPayment->gst;
                $newPayment->total = $existedPayment->total;
                $pending_amount = $existedPayment->pending_amount - $request->partial_payment;
                $newPayment->pending_amount = $pending_amount;
                $newPayment->submitted_amount = $request->partial_payment;
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($newPayment->save()) {
                    if ($newPayment->pending_amount == 0 ||  $newPayment->pending_amount == null || $newPayment->pending_amount == 0.00) {
                        $existedLeaedTaskDetails->status = 1;
                    } else {
                        $existedLeaedTaskDetails->status = 3;
                    }
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->comment = 'Partial Payment';
                    $existedLeaedTaskDetails->reminderDate = null;
                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $filePaths = [];
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                        $LeadLog->task_id =  $existedLeaedTask->id;
                        $LeadLog->remark =  'Partial Payment';
                        $oldValue = [
                            'Status' => $logStatus,
                            'Last payment On' => $oldPaidExitedDate,
                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $existedPayment->submitted_amount,
                            'pending_amount' => $existedPayment->pending_amount,
                        ];
                        $newValueStatus = "";
                        if ($newPayment->pending_amount == 0 ||  $newPayment->pending_amount == null || $newPayment->pending_amount == 0.00) {
                            $newValueStatus = 'Completed';
                        } else {
                            $newValueStatus = 'Partial Payment';
                        }

                        $newValue = [
                            'Status' => $newValueStatus,
                            'Paid On' => $verifiedDate ?? null,
                            'Assigned To' => $existedLeaedTask->user->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $newPayment->submitted_amount,
                            'pending_amount' => $newPayment->pending_amount,
                        ];
                        $LeadLog->old_value = json_encode($oldValue);
                        $LeadLog->new_value = json_encode($newValue);
                        $LeadLog->assign_by = Auth::id();
                        $LeadLog->description = "payment status updated successfully";
                        if ($LeadLog->save()) {
                            return redirect()->route('task.index')
                                ->with('success', 'payment status updated successfully');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong whil updating exist lead task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'while updating payment status');
                }
            } else if ($request->checkStatus == 3 && $request->payment == 3) {
                $logStatus = $existedLeaedTaskDetails->comment;
                $oldPaidExitedDate = $existedLeaedTaskDetails->status_date;
                $newPayment = new Payment();
                $newPayment->lead_id = $existedPayment->lead_id;
                $newPayment->task_id = $existedPayment->task_id;
                $newPayment->reference_id = $request->firstPaymentId;
                $newPayment->service_price = $existedPayment->service_price;
                $newPayment->govt_price = $existedPayment->govt_price;
                $newPayment->gst = $existedPayment->gst;
                $newPayment->total = $existedPayment->total;
                $pending_amount = $existedPayment->pending_amount;
                $newPayment->pending_amount = $pending_amount;
                $newPayment->submitted_amount = null;
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($newPayment->save()) {
                    if ($newPayment->pending_amount == 0 || $newPayment->pending_amount == Null) {
                        $existedLeaedTaskDetails->status = 1;
                    } else {
                        $existedLeaedTaskDetails->status = 3;
                    }
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->comment = "On Credit";

                    $existedLeaedTaskDetails->reminderDate = $paymentDeadlineDate;
                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $filePaths = [];
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                        $LeadLog->task_id =  $existedLeaedTask->id;
                        $LeadLog->assign_by = Auth::id();
                        $LeadLog->remark = "On Credit";
                        $oldValue = [
                            'Status' => $logStatus,
                            'Last payment On' => $oldPaidExitedDate ?? null,
                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $existedPayment->submitted_amount,
                            'Pending Amount' => $existedPayment->pending_amount,
                        ];
                        $newValue = [
                            'Status' => 'On Credit',
                            'Verified On' => $verifiedDate ?? null,
                            'Assigned To' => $existedLeaedTask->user->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => 0.00,
                            'Pending Amount' => $existedPayment->pending_amount,
                        ];
                        $LeadLog->old_value = json_encode($oldValue);
                        $LeadLog->new_value = json_encode($newValue);
                        $LeadLog->description = "payment status updated successfully";
                        if ($LeadLog->save()) {
                            return redirect()->route('task.index')
                                ->with('success', 'payment status updated successfully');
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong while updating log');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something error while exist lead task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is soemthing wrong while updating payment status');
                }
            }
        }
    }

    public function hearingDate($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->where('archive', 1)->get();

        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.hearing_date', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }

    public function hearingDateStatus(Request $request, $id)
    {
        $hearingDate = Carbon::createFromFormat('d M Y', $request->input('hearing_date'))->format('Y-m-d');
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::find($id);
        $newHearing = new HearingDateDetails();
        $existedHearing = HearingDateDetails::where('task_id', $existedLeaedTask->id)->latest()->first();

        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');

        $rule = [
            'hearing_date' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
            $newLeadtask->lead_id = $existedLeaedTask->lead_id;
            $newLeadtask->service_id = $existedLeaedTask->service_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->service_stage_id = $request->stage_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->assign_by = Auth::id();
            $newLeadtask->task_title = $newTaskTitle->title;
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
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
                        $newHearing->task_id  = $newLeadtask->id;
                        $newHearing->lead_id =  $existedLeaedTask->lead_id;
                        $newHearing->stage_id =  14;
                        $newHearing->reason =  Null;
                        $newHearing->hearing_date =  $hearingDate;
                        if ($existedHearing) {
                            $newHearing->count = $existedHearing->count ? $existedHearing->count + 1 : 0;
                        } else {
                            $newHearing->count = 1;
                        }
                        if ($newHearing->save()) {
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
                                $LeadLog->remark = 'Hearing(Objection)';
                                $oldValue = [
                                    'status' => 'Pending',
                                    'Assigned On' =>  $formattedCreatedDate,
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                    'Hearing Date' => "N/A"
                                ];
                                $newValue = [
                                    'status' => 'Completed',
                                    'Verified On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                    'Hearing Date' => $request->hearing_date,
                                ];
                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);
                                $LeadLog->description = "Application status moved to the hearing stage.";
                                if ($LeadLog->save()) {
                                    $newassignlog = new leadLog();
                                    $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                    $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                    $newassignlog->task_id = $newLeadtask->id;
                                    $newassignlog->assign_by = Auth::id();
                                    $newassignlog->remark = 'Assign';
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
                            return redirect()->back()->with('error', 'there is something wrong while updating new hearing date');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong during update  new task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is something wrong during update  existed task details');
                }
            } else {
                return redirect()->back()->with('error', 'there is something wrong during update  new task');
            }
        } else {
            return redirect()->back()->with('erroe', "no task found");
        }
    }

    public function showCaseHearing($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->where('archive', 1)->get();

        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        $hearingDateCount = HearingDateDetails::where('lead_id', $taskDetails->lead_id)->where('stage_id' , 14)->latest()->first();
        $count = ($hearingDateCount->count ?? 0) + 1;
        return view('tasks.tradeMark.show_case_hearing', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'count'));
    }

    public function showCaseHearingStatus(Request $request, $id)
    {   
    
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        if ($request->reminder_date) {

            $reminder_date = Carbon::createFromFormat('d M Y', $request->input('reminder_date'))->format('Y-m-d');
        }
        if ($request->deadline) {

            $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        }
        if ($request->hearing_date) {

            $hearing_date = Carbon::createFromFormat('d M Y', $request->input('hearing_date'))->format('Y-m-d');
        }

        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newHearing = new HearingDateDetails();
        $existedHearing = HearingDateDetails::where('lead_id', $existedLeaedTask->lead_id)->where('stage_id' , 14)->latest()->first();
        $newLeadtask = new LeadTask();
       
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $formattedCreatedDate = (!empty($existedLeaedTaskDetails->status_date))
            ? $existedLeaedTaskDetails->status_date
            : $existedLeaedTask->created_at->format('d M Y');
        $reminderDate =   $existedLeaedTaskDetails->reminderDate ?? "N/A";
        $comment = "";
        if ($request->show_case_hearing == 2) {
            $comment = "Adjournmet";
        } else if ($request->show_case_hearing == 4) {
            $comment = "Amendments";
        } else if ($request->show_case_hearing == 1) {
            $comment = "Accepeted";
        } else if ($request->show_case_hearing == 3) {
            $comment = "Rejected";
        }
       
        
        $rule = [
            'show_case_hearing' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'nullable'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {

            if ($request->show_case_hearing == 2 || $request->show_case_hearing == 4) {
                $rule = [
                    'show_case_hearing' => 'required',
                    'verified' => 'required',
                    'reminder_date' => 'required',
                    'hearing_date' => 'required',
                    
                ];
                $validator = Validator::make($request->all(), $rule);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                $logStatus = !empty($existedLeaedTaskDetails->comment) ? $existedLeaedTaskDetails->comment : 'Pending';
                $existedLeaedTaskDetails->status = 2;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $existedLeaedTaskDetails->comment = $comment;
                $existedLeaedTaskDetails->reminderDate = $reminder_date;


                if ($request->hasFile('attachment')) {
                    $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0755, true);
                    }
                    $filePaths = [];
                    $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                    foreach ($request->file('attachment') as $file) {
                        if ($file->isValid()) {
                            $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                            $file->move($folderPath, $fileName);
                            $filePaths[] = $fileName;
                        }
                    }
                    $updatedAttachments = array_merge($existingAttachments, $filePaths);
                    $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                }
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($existedLeaedTaskDetails->save()) {
                    $newHearing->task_id = $existedLeaedTask->id;
                    $newHearing->lead_id = $existedLeaedTask->lead_id;
                    $newHearing->stage_id = $existedHearing->stage_id;

                    $reason = '';
                    if ($request->show_case_hearing == 2) {
                        $reason = 'Adjournmet';
                    } else if ($request->show_case_hearing == 4) {
                        $reason = 'Amendments';
                    }
                    $newHearing->reason = $reason;
                    $newHearing->hearing_date = $hearing_date;
                    if ($existedHearing) {
                        $newHearing->count = $existedHearing->count ? $existedHearing->count + 1 : 0;
                    } else {
                        $newHearing->count = 1;
                    }
                    if ($newHearing->save()) {
                        $newNotification->user_id = $existedLeaedTask->user_id;
                        $newNotification->lead_id = $existedLeaedTask->lead_id;
                        $newNotification->task_id = $existedLeaedTask->id;
                        $newNotification->title = 'Task On Hold ';
                        $newNotification->description = 'Show case hearing  is on hold';
                        if ($newNotification->save()) {
                            $newLog =  new LeadLog();
                            $newLog->user_id = $existedLeaedTask->user_id;
                            $newLog->lead_id = $existedLeaedTask->lead_id;
                            $newLog->task_id = $existedLeaedTask->id;
                            $newLog->assign_by = Auth::id();
                            $newLog->remark = $comment . " " . "(On Hold)";
                            $oldValue = [
                                'status' => $logStatus,
                                'Assigned On' =>  $formattedCreatedDate ?? "N/A",
                                'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                'Verified On' => "N/A",
                                'Reminder Date' => $reminderDate ?? "N/A",
                                'Hearing Date' => $existedHearing->hearing_date ?? "N/A",
                                'Hearing Count' => $existedHearing->count ?? "N/A"
                            ];
                            $newValue = [
                                'status' => $comment,
                                'Verified On' => $request->verified,
                                'Assigned To' =>  $existedLeaedTask->user->name,
                                'Reminder Date' => $reminder_date,
                                'Hearing Date' => $request->hearing_date,
                                'Hearing Count' => $newHearing->count

                            ];
                            $newLog->old_value = json_encode($oldValue);
                            $newLog->new_value = json_encode($newValue);
                            $newLog->description = "Show case hearing marked as" . $comment;

                            if ($newLog->save()) {
                                return redirect()->route('task.index')->with('success', 'Show case hearing  status is Updated');
                            } else {
                                return redirect()->back()->error('message', " there is something wrong during log generate ");
                            }
                        }
                    } else {
                        return redirect()->back()->back()->with('error', 'there is something wrong while updating new hearing');
                    }
                } else {
                    return redirect()->back()->error('message', " there is something wrong during hold the task ");
                }
            } else if ($request->show_case_hearing == 3) {
                $rule = [
                    'show_case_hearing' => 'required',
                    'verified' => 'required',
                    'rejected_reason' => 'required'

                ];
                $validator = Validator::make($request->all(), $rule);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $logStatus = !empty($existedLeaedTaskDetails->comment) ? $existedLeaedTaskDetails->comment : 'Pending';

                $existedLeaedTaskDetails->status = 4;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $existedLeaedTaskDetails->reminderDate = null;
                $reason = "";
                if ($request->rejected_reason == 1) {
                    $reason  = 'Refused';
                } else if ($request->rejected_reason == 2) {
                    $reason  = 'Abandon';
                } else if ($request->rejected_reason == 3) {
                    $reason  = 'Withdraw';
                }
                $existedLeaedTaskDetails->comment = $comment . "( $reason)";
                if ($request->hasFile('attachment')) {
                    $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0755, true);
                    }
                    $filePaths = [];
                    $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                    foreach ($request->file('attachment') as $file) {
                        if ($file->isValid()) {
                            $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                            $file->move($folderPath, $fileName);
                            $filePaths[] = $fileName;
                        }
                    }
                    $updatedAttachments = array_merge($existingAttachments, $filePaths);
                    $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                }
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($existedLeaedTaskDetails->save()) {
                    $newNotification->user_id = $existedLeaedTask->user_id;
                    $newNotification->lead_id = $existedLeaedTask->lead_id;
                    $newNotification->task_id = $existedLeaedTask->id;
                    $newNotification->title = 'Task rejected ';
                    $newNotification->description = 'Show case hearing  is rejected';
                    if ($newNotification->save()) {
                        $newLog =  new LeadLog();
                        $newLog->user_id = $existedLeaedTask->user_id;
                        $newLog->lead_id = $existedLeaedTask->lead_id;
                        $newLog->task_id = $existedLeaedTask->id;
                        $newLog->assign_by = Auth::id();
                        $newLog->remark = "Rejected" . "($reason)";
                        $oldValue = [
                            'status' => $logStatus,
                            'Assigned On' =>  $formattedCreatedDate ?? "N/A",
                            'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                            'Verified On' => "N/A",
                            'Reminder Date' => $reminderDate ?? "N/A",
                            'Hearing Date' => $existedHearing->hearing_date ?? "N/A",
                            'Hearing Count' => $existedHearing->count ?? "N/A"
                        ];
                        $newValue = [
                            'status' => $comment . "($reason)",
                            'Verified On' => $request->verified,
                            'Assigned To' =>  $existedLeaedTask->user->name,
                            'Reminder Date' => "N/A",
                            'Hearing Date' => "N/A",
                            'Hearing Count' => "N/A"
                        ];
                        $newLog->old_value = json_encode($oldValue);
                        $newLog->new_value = json_encode($newValue);
                        $newLog->description = "Show case hearing marked as rejected " . "($reason)";
                        if ($newLog->save()) {
                            return redirect()->route('task.index')->with('success', 'Show case hearing  status is Updated');
                        } else {
                            return redirect()->back()->error('message', " there is something wrong during log generate ");
                        }
                    } else {
                        return redirect()->back()->error('message', " there is something wrong during notification generate ");
                    }
                } else {
                    return redirect()->back()->with('error', 'there is something wrong while updating exist lead task details');
                }
            } else if ($request->show_case_hearing == 1) {
                $logStatus = !empty($existedLeaedTaskDetails->comment) ? $existedLeaedTaskDetails->comment : 'Pending';

                $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $existedLeaedTask->service_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->service_stage_id = $request->stage_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
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
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                    $existedLeaedTask->save();
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
                                $LeadLog->remark = "Accepted";
                                $oldValue = [
                                    'status' => $logStatus,
                                    'Assigned On' =>  $formattedCreatedDate ?? "N/A",
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                    'Verified On' => "N/A",
                                    'Reminder Date' => $reminderDate ?? "N/A",
                                    'Hearing Date' => $existedHearing->hearing_date ?? "N/A",
                                    'Hearing Count' => $existedHearing->count ?? "N/A"
                                ];
                                $newValue = [
                                    'status' => "Completed" . "($comment)",
                                    'Verified On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                    'Reminder Date' => "N/A",
                                    'Hearing Date' => "N/A",
                                    'Hearing Count' => "N/A"
                                ];
                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);
                                $LeadLog->description = " Show case hearing  marked as complete ";
                                if ($LeadLog->save()) {
                                    $newassignlog = new leadLog();
                                    $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                    $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                    $newassignlog->task_id = $newLeadtask->id;
                                    $newassignlog->assign_by = Auth::id();
                                    $newassignlog->remark = "Assign";

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

    public function markAsPublish(Request $request, $id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->where('archive', 1)->get();
        
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.mark_as_publish', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }
    public function markAsPublishStatus(Request $request, $id)
    {
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        // $reminderDate = Carbon::createFromFormat('d M Y', $request->input('reminderDate'))->format('Y-m-d');
        
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');

        $rule = [
            'journal_number' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            $jornalNumber = ServiceDetail::where('lead_id', $existedLeaedTask->lead_id)
            ->first();
            $jornalNumber->save();
            $jornalNumber->journal_number = $request->journal_number ?? null;
            $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
            $newLeadtask->lead_id = $existedLeaedTask->lead_id;
            $newLeadtask->service_id = $existedLeaedTask->service_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->service_stage_id = $request->stage_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->assign_by = Auth::id();
            $newLeadtask->task_title = $newTaskTitle->title;
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
            if ($newLeadtask->save()) {
                $existedLeaedTaskDetails->status = 1;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                // $existedLeaedTaskDetails->reminderDate = $reminderDate;
               
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
                    $userAssign =  $request->assignUser ?? $existedLeaedTask->user_id;
                    $notification = new LeadNotification();
                    $notification->user_id =  $userAssign;
                    $notification->lead_id = $existedLeaedTask->lead_id;
                    $notification->task_id = $existedLeaedTask->id;
                    $notification->title = "Trademark Published ";
                    $notification->description = 'Trademark Published by' . ' ' . $userName . ' ' .  'successfully';
                    $notification->status = 0;
                    if ($notification->save()) {
                        $newLog =  new LeadLog();
                        $newLog->user_id = $existedLeaedTask->user_id;
                        $newLog->lead_id = $existedLeaedTask->lead_id;
                        $newLog->task_id = $existedLeaedTask->id;
                        $newLog->assign_by = Auth::id();
                        $newLog->remark = "Trademark Published";
                        $oldValue = [
                            'status' => 'Pending',
                            'Assigned On' => $formattedCreatedDate,
                            'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                            'Journal Number' => "N/A"
                        ];
                        $newValue = [
                            'status' => 'Completed (Published)',
                            'Published On' => $request->verified,
                            'Assigned To' =>  $existedLeaedTask->user->name,
                            'Journal Number' => $request->journal_number,

                        ];
                        $newLog->old_value = json_encode($oldValue);
                        $newLog->new_value = json_encode($newValue);

                        $newLog->description = "Trademark publish successfully";
                        if ($newLog->save()) {
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
                                    $newassignlog = new leadLog();
                                    $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                    $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                    $newassignlog->task_id = $newLeadtask->id;
                                    $newassignlog->assign_by = Auth::id();
                                    $newassignlog->remark = "Assign";

                                    $newassignlog->description =  "Lead assigned for next task";
                                    if ($newassignlog->save()) {
                                        $id = $newLeadtask->id;
                                        return redirect()->route('task.index')->with('success', 'Trademark Published successfully');
                                    } else {
                                        return redirect()->back()->with('error', 'there is something wrong while updating log');
                                    }
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong while updating assign notification');
                                }
                            } else {
                                return redirect()->back()->with('error', ' there is something wrong while updating new lead task details');
                            }
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong while updating trademark publish log');
                        }
                    } else {
                        return redirect()->back()->with('error', ' there is something wrong while updating notification ');
                    }
                } else {
                    return redirect()->back()->with('error', ' there is something wrong while updating existed lead task details');
                }
            } else {
                return redirect()->back()->with('error', ' there is something wrong while updating new  lead task');
            }
        }
    }

    public function markPublishOpposition($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->where('archive', 1)->get();
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $onHideSatge = ServiceStages::where('service_id', 1)->where('id', '=', 35)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.published_opposition', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'onHideSatge'));
    }
    public function markPublishOppositionStatus(Request $request, $id)
    {
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        if ($request->opposition_date) {

            $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        }
        if ($request->opposition_date) {

            $opposition_date = Carbon::createFromFormat('d M Y', $request->input('opposition_date'))->format('Y-m-d');
        }

        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');
        $rule = [
            'publish_opposition' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'required'
        ];
        if ($request->publish_opposition == 0) {
            $rule = [
                'publish_opposition' => 'required',
                'verified' => 'required',
                'assignUser' => 'required',
                'deadline' => 'required',
                'opposition_number' => 'required',
                'opponent_name' => 'required',
                'opponent_address' => 'required',
                'advocate_name' => 'required',
                'opposition_date' => 'required'
            ];
        }
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
            $newLeadtask->lead_id = $existedLeaedTask->lead_id;
            $newLeadtask->service_id = $existedLeaedTask->service_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->service_stage_id = $request->stage_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->assign_by = Auth::id();
            $newLeadtask->task_title = $newTaskTitle->title;
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
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
                        if ($request->publish_opposition == 0) {
                            $newOpponent = new Evidence();
                            $newOpponent->task_id = $existedLeaedTask->id;
                            $newOpponent->lead_id = $existedLeaedTask->lead_id;
                            $newOpponent->service_id = $existedLeaedTask->service_id;
                            $newOpponent->opposition_number =  $request->opposition_number;
                            $newOpponent->opponent_name = $request->opponent_name;
                            $newOpponent->advocate_name = $request->advocate_name;
                            $newOpponent->address = $request->opponent_address;
                            $newOpponent->status = 0;
                            $newOpponent->opposition_date = $opposition_date;
                            if ($request->hasFile('attachment')) {
                                $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                                if (!file_exists($folderPath)) {
                                    mkdir($folderPath, 0755, true);
                                }
                                foreach ($request->file('attachment') as $file) {
                                    if ($file->isValid()) {
                                        $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                        $file->move($folderPath, $fileName);
                                        $filePaths[] = $fileName;
                                    }
                                }
                                $newOpponent->attachment = json_encode($filePaths) ?? null;
                            }
                            $newOpponent->save();
                        }
                        $newNotification->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                        $newNotification->lead_id = $existedLeaedTask->lead_id;
                        $newNotification->task_id = $newLeadtask->id;
                        $newNotification->title = 'Task Assigned';
                        $newNotification->description =  $userName . ' assigned you ' . $newTaskTitle->title . ' task';
                        $newNotification->status = 0;
                        if ($newNotification->save()) {
                            $LeadLog =  new LeadLog();
                            if ($request->publish_opposition == 0) {
                                $LeadLog->user_id = $existedLeaedTask->user_id;
                                $LeadLog->lead_id = $existedLeaedTask->lead_id;
                                $LeadLog->task_id = $existedLeaedTask->id;
                                $LeadLog->assign_by = Auth::id();

                                $LeadLog->remark = "Opposition Filed";
                                $oldValue = [
                                    'status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                    
                                ];
                                $newValue = [
                                    'status' => 'Completed (Published)',
                                    'Published On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                    'Opposition Number' => $request->opposition_number,
                                    'Opponent Name' => $request->opponent_name,
                                    'Opponent Address' => $request->opponent_address,
                                    'Advocate Name' => $request->advocate_name,
                                    'Opposition Date' => $opposition_date ?? "N/A"
                                ];
                                $LeadLog->description = "Opposition marked as filed";
                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);
                            } else {
                                $LeadLog->user_id = $existedLeaedTask->user_id;
                                $LeadLog->lead_id = $existedLeaedTask->lead_id;
                                $LeadLog->task_id = $existedLeaedTask->id;
                                $LeadLog->assign_by = Auth::id();

                                $LeadLog->remark = "Opposition Not Filed";
                                $oldValue = [
                                    'status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,

                                ];
                                $newValue = [
                                    'status' => 'Completed (Published)',
                                    'Published On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                ];
                                $LeadLog->description = "Opposition marked as filed";
                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);
                                $LeadLog->description = "Opposition marked as not filed";
                            }

                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = 'Assign';

                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'Opposition status Updqted');
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
            } else {
                return redirect()->back()->with('error', 'there is something wrong during update  new task');
            }
        } else {
            return redirect()->back()->with('erroe', "no task found");
        }
    }

    public function informClientAfterPublish($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }
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
            $header_title_name = $value->serviceSatge->title;
        }
        $getStage = ServiceStages::where('service_id', $serviceID)->where('id', '>', $stage_id)->first();
        $leadTaskdetials = LeadTaskDetail::find($taskDetailsId);
        return view('tasks.tradeMark.after_publish_inform_client', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'serviceName', 'clientName'));
    }

    public function informClientAfterPublishStatus(Request $request, $id)
    {
        // dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedTask = LeadTask::with(['services', 'subService', 'lead', 'serviceSatge'])->where('id', $id)->first();
        $existedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newPayment = new Payment();
        $newTaskAssigned = new LeadTask();
        $newTaskDetails = new LeadTaskDetail();
        $serviceId = $existedTask->services->id;
        $subServiceID = $existedTask->subService->id;
        $formattedCreatedDate = $existedTask->created_at->format('d M Y');


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
        $gst = $request->gst ?? null;
        $total_without_gst = $service_price + $govt_price;
        if (!empty($gst)) {
            $gstApply  = "Apply";
            $gst_amount = $service_price * 0.18;
            $total = $total_without_gst + $gst_amount;
        } else {
            $gstApply  = "Not Apply";
            $total = $total_without_gst;
            $gst_amount = 0;
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
            $existedTask->task_description = $request->description;
            $existedTask->save();
            if ($newTaskAssigned->save()) {
                $newTaskDetails->task_id = $newTaskAssigned->id;
                $newTaskDetails->status = 0;
                $newTaskDetails->dead_line = $deadlineDate;
                if ($newTaskDetails->save()) {
                    $existedTaskDetails->status_date = $verifiedDate;
                    $existedTaskDetails->status = 1;
                    $existedTaskDetails->mail_subject = $request->subject;
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

                        $newPayment->lead_id = $existedTask->lead_id;
                        $newPayment->task_id = $newTaskDetails->task_id;
                        $newPayment->reference_id = 0;
                        $newPayment->service_price = $service_price;
                        $newPayment->govt_price = $govt_price;
                        $newPayment->gst = $gst_amount ?? 0;
                        $newPayment->total = $total;
                        $newPayment->pending_amount = $total;
                        if ($newPayment->save()) {
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
                                $LeadLog->remark = "Quotation sent";
                                $oldValue = [
                                    'Status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' => $existedTask->userAssignBy->name,
                                    'Payment' => 'Pending'
                                ];
                                $newValue = [
                                    'Status' => 'Completed',
                                    'Sent On' => $verifiedDate ?? null,
                                    'Assigned To' => $existedTask->user->name,
                                   
                                    'Service Price' => $request->service_price ?? null,
                                    'Govt Price' => $request->govt_price,
                                    'gst' => $gstApply . " " . "₹" . $gst_amount . "" . "(%18)",
                                ];

                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);
                                $LeadLog->description = "Quotation sent to the client";
                                if ($LeadLog->save()) {
                                    $newassignlog = new leadLog();
                                    $newassignlog->user_id = $request->assignUser ?? $existedTask->user_id;
                                    $newassignlog->lead_id = $existedTask->lead_id;
                                    $newassignlog->task_id = $newTaskAssigned->id;
                                    $newassignlog->assign_by = Auth::id();
                                    $newassignlog->remark = 'Assign';

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
                                return redirect()->back()->error('message', " there is something wrong during update payment ");
                            }
                        } else {
                            return redirect()->back()->error('message', " there is something wrong during update existed tasl details ");
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
    public function oppositionPayment($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }
        $header_title_name = "payment Status";
        $taskDetails = LeadTask::with(['user', 'payment', 'lead', 'leadTaskDetails', 'services', 'subService', 'serviceSatge'])
            ->where('id', $id)
            ->get();
        foreach ($taskDetails as $task) {
            $lastPayment = $task->payment->last();

            if ($lastPayment) {
                $paymentId = $lastPayment->id;
                $payamentDetails = Payment::where('id', $paymentId)->first();
            }
        }
        foreach ($taskDetails as $task) {
            $taskDetailsId = $task->id;
        }
        $firstPaymentId = Payment::where('task_id', $taskDetailsId)->OrderBy('id', 'ASC')->first();

        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        foreach ($taskDetails as $value) {
            $stageId = $value->service_stage_id;
        }
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($taskDetailsId);
        return view('tasks.tradeMark.opposition_payment', compact('id','taskDetailsId', 'firstPaymentId', 'payamentDetails', 'paymentId', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }

    public function oppositionPaymentStatus(Request $request, $id)
    {
        // dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        if ($request->paymentDeadline) {

            $paymentDeadlineDate = Carbon::createFromFormat('d M Y', $request->input('paymentDeadline'))->format('Y-m-d');
        }
        if ($request->deadline) {

            $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        }
        $existedLeaedTask = LeadTask::with(['lead', 'services', 'subService', 'serviceSatge', 'userAssignBy'])->where('id', $id)->first();
        $client_id = $existedLeaedTask->lead->client_id;
        $existed_leadId = $existedLeaedTask->lead->id;

        $leadUpdate = Lead::find($existed_leadId);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $existedPayment = Payment::where('task_id', $id)->where('lead_id', $existedLeaedTask->lead_id)->where('id', $request->paymentId)->first();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $serviceId = $existedLeaedTask->services->id;
        $subServiceId = $existedLeaedTask->subService->id;
        $stageId = (int) $request->stage_id;
        $userName =  Auth::user()->name;
        $assignedStageName = ServiceStages::where('id', $stageId)->first();
        $rule = [
            'payment' => 'required',
            'deadline' => 'nullable',
            'verified' => 'required',
            'stage_id' => 'required',
            'attachment' => 'array',
            'attachment.*' => 'nullable',
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');

        if ($id) {
            if ($request->checkStatus == 0) {
                if ($request->payment == 1 || $request->payment == 2 || $request->payment == 3) {
                    $existedLeaedTask->task_description = $request->description;
                    $existedLeaedTask->save();
                    $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                    $newLeadtask->project_manager_id = $existedLeaedTask->project_manager_id;
                    $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                    $newLeadtask->service_id = $existedLeaedTask->service_id;
                    $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                    $newLeadtask->service_stage_id = $stageId;
                    $newLeadtask->sub_stage_id = null;
                    $newLeadtask->assign_by = Auth::id();
                    if ($newLeadtask->save()) {
                        if ($request->payment == 1) {
                            $existedLeaedTaskDetails->status = 1;
                        } else if ($request->payment == 2) {
                            $existedLeaedTaskDetails->status = 3;
                        } else if ($request->payment == 3) {
                            $existedLeaedTaskDetails->status = 3;
                        }
                        $existedLeaedTaskDetails->status_date = $verifiedDate;
                        if ($request->payment == 1) {
                            $existedLeaedTaskDetails->comment = 'Paid';
                        } else if ($request->payment == 2) {
                            $existedLeaedTaskDetails->comment = 'Partial Payment';
                        } else if ($request->payment == 3) {
                            $existedLeaedTaskDetails->comment = 'On Credit';
                        }
                        if ($request->payment == 1) {
                            $existedLeaedTaskDetails->reminderDate = Null;
                        } else if ($request->payment == 2) {
                            $existedLeaedTaskDetails->reminderDate = $paymentDeadlineDate;
                        } else if ($request->payment == 3) {
                            $existedLeaedTaskDetails->reminderDate = $paymentDeadlineDate;
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
                            $existedLeaedTaskDetails->attachment = json_encode($filePaths);
                        }
                        if ($existedLeaedTaskDetails->save()) {
                            $newPayment =  new Payment();
                            $newPayment->lead_id = $existedPayment->lead_id;
                            $newPayment->task_id = $existedPayment->task_id;
                            $newPayment->reference_id = $existedPayment->id;
                            $newPayment->service_price = $existedPayment->service_price;
                            $newPayment->govt_price = $existedPayment->govt_price;
                            $newPayment->gst = $existedPayment->gst;
                            $newPayment->total = $existedPayment->total;
                            if ($request->payment == 1) {
                                $newPayment->pending_amount = 0;
                                $newPayment->submitted_amount = $existedPayment->total;
                            } else if ($request->payment == 2 && $request->partial_payment != $existedPayment->total) {
                                $pending_amount = $existedPayment->pending_amount - $request->partial_payment;
                                $newPayment->pending_amount = $pending_amount;
                                $newPayment->submitted_amount = $request->partial_payment;
                            } else if ($request->payment == 2 && $request->partial_payment == $existedPayment->total) {
                                $newPayment->pending_amount = 0;
                                $newPayment->submitted_amount = $existedPayment->total;
                            } else if ($request->payment == 3) {
                                $newPayment->pending_amount = $existedPayment->pending_amount;
                                $newPayment->submitted_amount = null;
                            }
                            if ($newPayment->save()) {
                                $newLeadTaskDeatails->task_id = $newLeadtask->id;
                                $newLeadTaskDeatails->dead_line = $deadlineDate;
                                $newLeadTaskDeatails->status = 0;
                                if ($newLeadTaskDeatails->save()) {
                                    $userAssign =  $request->assignUser ?? $existedLeaedTask->user_id;
                                    $notification = new LeadNotification();
                                    $notification->user_id =  $userAssign;
                                    $notification->lead_id = $existedLeaedTask->lead_id;
                                    $notification->task_id = $newLeadtask->id;
                                    $notification->title = "Task Assigned";
                                    $notification->description =  $userName . ' assigned you ' . $assignedStageName->title . ' task';
                                    $notification->status = 0;
                                    if ($notification->save()) {
                                        $LeadLog = new LeadLog();
                                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                                        $LeadLog->task_id =  $existedLeaedTask->id;
                                        $LeadLog->assign_by = Auth::id();
                                        $remark = "";
                                        $paidAmount = "";
                                        if ($request->payment == 1) {

                                            $remark = 'Paid';
                                            $paidAmount = $newPayment->total;
                                        } else if ($request->payment == 2 && $request->partial_payment != $existedPayment->total) {
                                            $remark = 'Partial Payment';
                                            $paidAmount = $newPayment->submitted_amount;
                                        } else if ($request->payment == 2 && $request->partial_payment == $existedPayment->total) {
                                            $remark = 'Partial Payment(Paid)';
                                            $paidAmount = $newPayment->submitted_amount;
                                        } else if ($request->payment == 3) {
                                            $remark = 'On Credit';
                                            $paidAmount = 0.00;
                                        }
                                        $LeadLog->remark = $remark;
                                        $oldValue = [
                                            'Status' => 'Pending',
                                            'Assigned On' => $formattedCreatedDate,
                                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                                            'Total Amount' => $existedPayment->total,
                                            'Paid Amount' => $existedPayment->submitted_amount,
                                            'pending_amount' => $existedPayment->pending_amount,

                                        ];

                                        $newValue = [
                                            'Status' => $remark,
                                            'Paid On' => $verifiedDate ?? null,
                                            'Assigned To' => $existedLeaedTask->user->name,
                                            'Total Amount' => $existedPayment->total,
                                            'Paid Amount' => $paidAmount,
                                            'pending_amount' => $newPayment->pending_amount,
                                        ];
                                        $LeadLog->old_value = json_encode($oldValue);
                                        $LeadLog->new_value = json_encode($newValue);
                                        $LeadLog->description = "payment status updated successfully";
                                        if ($LeadLog->save()) {
                                            $newassignlog = new leadLog();
                                            $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                            $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                            $newassignlog->task_id = $newLeadtask->id;
                                            $newassignlog->assign_by = Auth::id();
                                            $newassignlog->remark = 'Assign';

                                            $newassignlog->description =  "Lead assigned for next task";
                                            if ($newassignlog->save()) {
                                                if ($client_id == 0) {
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
                                                        $leadUpdate->client_id = $newClient->id;
                                                        $leadUpdate->client_name = $newClient->name;
                                                        if ($leadUpdate->save()) {
                                                            if ($mail == true) {
                                                                SendClientWelcomeEmail::dispatch($newClient, $randomNumber, $filePath = null, $type);
                                                            }
                                                            return redirect()->route('task.index')
                                                                ->with('success', 'payment status updated successfully');
                                                        } else {
                                                            return redirect()->back()->with('error', 'there is something wrong while  updateing existed lead');
                                                        }
                                                    } else {
                                                        return redirect()->back()->with('error', 'there is something wrong while  creating new client');
                                                    }
                                                } else {
                                                    return redirect()->route('task.index')->with('success', 'payment status updated successfully');
                                                }
                                            } else {
                                                return redirect()->back()->with('error', 'there is something wrong while updating logs');
                                            }
                                        } else {
                                            return redirect()->back()->with('error', 'there is something wrong while updating logs');
                                        }
                                    } else {
                                        return redirect()->back()->with('error', 'there is something wrong while updating notification');
                                    }
                                } else {
                                    return redirect()->back()->with('error', 'there is something worng while create new lead task details');
                                }
                            } else {
                                return redirect()->back()->with('eror', 'there is something wrong while updating payment status');
                            }
                        } else {
                            return redirect()->back()->with('eror', 'there is something wrong while updating exist task details');
                        }
                    } else {
                        return redirect()->back()->with('eror', 'there is something wrong while creating new task');
                    }
                }
            } else if ($request->checkStatus == 3 && $request->payment == 1) {
                $logStatus = $existedLeaedTaskDetails->comment;
                $oldPaidExitedDate = $existedLeaedTaskDetails->status_date;

                $newPayment = new Payment();
                $newPayment->lead_id = $existedPayment->lead_id;
                $newPayment->task_id = $existedPayment->task_id;
                $newPayment->reference_id = $existedPayment->id;
                $newPayment->reference_id = $existedPayment->id;
                $newPayment->service_price = $existedPayment->service_price;
                $newPayment->govt_price = $existedPayment->govt_price;
                $newPayment->gst = $existedPayment->gst;
                $newPayment->total = $existedPayment->total;
                $newPayment->pending_amount = $existedPayment->total;
                $pending_amount = $existedPayment->pending_amount;
                $newPayment->pending_amount = 0;
                $newPayment->submitted_amount = $pending_amount;
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($newPayment->save()) {
                    $existedLeaedTaskDetails->status = 1;
                    $existedLeaedTaskDetails->comment = 'Paid';
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->reminderDate = Null;
                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $filePaths = [];
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                        $LeadLog->task_id =  $existedLeaedTask->id;
                        $LeadLog->remark = "Paid";
                        $oldValue = [
                            'Status' => $logStatus,
                            'Last Payment On' => $oldPaidExitedDate ?? null,
                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $existedPayment->submitted_amount,
                            'pending_amount' => $existedPayment->pending_amount,
                        ];

                        $newValue = [
                            'Status' => 'Completed',
                            'Paid On' => $verifiedDate ?? null,
                            'Assigned To' => $existedLeaedTask->user->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $newPayment->submitted_amount,
                            'pending_amount' => $newPayment->pending_amount,
                        ];
                        $LeadLog->old_value = json_encode($oldValue);
                        $LeadLog->new_value = json_encode($newValue);
                        $LeadLog->assign_by = Auth::id();
                        $LeadLog->description = "payment status updated successfully";
                        if ($LeadLog->save()) {
                            return redirect()->route('task.index')
                                ->with('success', 'payment status updated successfully');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong while updating exist lead task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is soemthing wrong while updating payment status');
                }
            } else if ($request->checkStatus == 3 && $request->payment == 2) {
                $logStatus = $existedLeaedTaskDetails->comment;
                $oldPaidExitedDate = $existedLeaedTaskDetails->status_date;

                $newPayment = new Payment();
                $newPayment->lead_id = $existedPayment->lead_id;
                $newPayment->task_id = $existedPayment->task_id;
                $newPayment->reference_id = $request->firstPaymentId;
                $newPayment->service_price = $existedPayment->service_price;
                $newPayment->govt_price = $existedPayment->govt_price;
                $newPayment->gst = $existedPayment->gst;
                $newPayment->total = $existedPayment->total;
                $pending_amount = $existedPayment->pending_amount - $request->partial_payment;
                $newPayment->pending_amount = $pending_amount;
                $newPayment->submitted_amount = $request->partial_payment;
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($newPayment->save()) {
                    if ($newPayment->pending_amount == 0 ||  $newPayment->pending_amount == null || $newPayment->pending_amount == 0.00) {
                        $existedLeaedTaskDetails->status = 1;
                    } else {
                        $existedLeaedTaskDetails->status = 3;
                    }
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->comment = 'Partial Payment';
                    $existedLeaedTaskDetails->reminderDate = null;
                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $filePaths = [];
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                        $LeadLog->task_id =  $existedLeaedTask->id;
                        $LeadLog->remark =  'Partial Payment';
                        $oldValue = [
                            'Status' => $logStatus,
                            'Last payment On' => $oldPaidExitedDate,
                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $existedPayment->submitted_amount,
                            'pending_amount' => $existedPayment->pending_amount,
                        ];
                        $newValueStatus = "";
                        if ($newPayment->pending_amount == 0 ||  $newPayment->pending_amount == null || $newPayment->pending_amount == 0.00) {
                            $newValueStatus = 'Completed';
                        } else {
                            $newValueStatus = 'Partial Payment';
                        }

                        $newValue = [
                            'Status' => $newValueStatus,
                            'Paid On' => $verifiedDate ?? null,
                            'Assigned To' => $existedLeaedTask->user->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $newPayment->submitted_amount,
                            'pending_amount' => $newPayment->pending_amount,
                        ];
                        $LeadLog->old_value = json_encode($oldValue);
                        $LeadLog->new_value = json_encode($newValue);
                        $LeadLog->assign_by = Auth::id();
                        $LeadLog->description = "payment status updated successfully";
                        if ($LeadLog->save()) {
                            return redirect()->route('task.index')
                                ->with('success', 'payment status updated successfully');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong whil updating exist lead task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'while updating payment status');
                }
            } else if ($request->checkStatus == 3 && $request->payment == 3) {
                $logStatus = $existedLeaedTaskDetails->comment;
                $oldPaidExitedDate = $existedLeaedTaskDetails->status_date;
                $newPayment = new Payment();
                $newPayment->lead_id = $existedPayment->lead_id;
                $newPayment->task_id = $existedPayment->task_id;
                $newPayment->reference_id = $request->firstPaymentId;
                $newPayment->service_price = $existedPayment->service_price;
                $newPayment->govt_price = $existedPayment->govt_price;
                $newPayment->gst = $existedPayment->gst;
                $newPayment->total = $existedPayment->total;
                $pending_amount = $existedPayment->pending_amount;
                $newPayment->pending_amount = $pending_amount;
                $newPayment->submitted_amount = null;
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($newPayment->save()) {
                    if ($newPayment->pending_amount == 0 || $newPayment->pending_amount == Null) {
                        $existedLeaedTaskDetails->status = 1;
                    } else {
                        $existedLeaedTaskDetails->status = 3;
                    }
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->comment = "On Credit";

                    $existedLeaedTaskDetails->reminderDate = $paymentDeadlineDate;
                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $filePaths = [];
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                        $LeadLog->task_id =  $existedLeaedTask->id;
                        $LeadLog->assign_by = Auth::id();
                        $LeadLog->remark = "On Credit";
                        $oldValue = [
                            'Status' => $logStatus,
                            'Last payment On' => $oldPaidExitedDate ?? null,
                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $existedPayment->submitted_amount,
                            'Pending Amount' => $existedPayment->pending_amount,
                        ];
                        $newValue = [
                            'Status' => 'On Credit',
                            'Verified On' => $verifiedDate ?? null,
                            'Assigned To' => $existedLeaedTask->user->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => 0.00,
                            'Pending Amount' => $existedPayment->pending_amount,
                        ];
                        $LeadLog->old_value = json_encode($oldValue);
                        $LeadLog->new_value = json_encode($newValue);
                        $LeadLog->description = "payment status updated successfully";
                        if ($LeadLog->save()) {
                            return redirect()->route('task.index')
                                ->with('success', 'payment status updated successfully');
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong while updating log');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something error while exist lead task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is soemthing wrong while updating payment status');
                }
            }
        }
    }

    public function oppositionNoticeDate($id){
        if ($id) {
            LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with([
            'user',
            'lead',
            'services',
            'subService',
            'leadTaskDetails',
            'serviceSatge'
        ])->where('id', $id)->first();

        $users = User::where('role', '>', 4)
            ->where('archive', 1)
            ->where('status', 1)
            ->get();

        $stageId = $taskDetails->service_stage_id ?? null;
        $getStage = ServiceStages::where('service_id', 1)
            ->where('id', '>', $stageId)
            ->first();

        $leadTaskdetials = LeadTaskDetail::find($id);

        if ($leadTaskdetials && $leadTaskdetials->status == 3) {
            $getStage = ServiceStages::where('service_id', 1)
                ->where('id', 25)
                ->first();
        }

        $onHideSatge = ServiceStages::where('service_id', 1)
            ->where('id', 26)
            ->first();

        $header_title_name = $taskDetails->serviceSatge->title ?? 'Default Title';

        
        return view('tasks.tradeMark.opposition_notice_date', compact('id',
            'header_title_name',
            'taskDetails',
            'leadTaskdetials',
            'users',
            'getStage',
            'onHideSatge'));
    }

    public function oppositionNoticeDateStatus(Request $request , $id){
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $userName = Auth::user()->name;
        $newTaskTitle = ServiceStages::find($request->stage_id);
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');
        $rule = [
            'notice_received' => 'required',
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
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
            if ($newLeadtask->save()) {
                $existedLeaedTaskDetails->status = 1;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                if($request->notice_received == 1){
                    $existedLeaedTaskDetails->comment = "Notice recevied"; 
                }else if($request->notice_received == 2){
                    $existedLeaedTaskDetails->comment = "Non-Compliance Intimation Registration";
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
                            if($request->notice_received == 1){

                                $LeadLog->remark = 'Notice Received ';
                                $oldValue = [
                                    'status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'status' => 'Completed',
                                    'Received On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                ];
                            }else if($request->notice_received == 2){

                                $LeadLog->remark = 'Notice Not Received ';
                                $oldValue = [
                                    'status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'status' => 'Completed',
                                    'Verified On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                ];
                            }
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);
                            if($request->notice_received == 1){

                                $LeadLog->description = "Opposition Notice Recived";
                            }else if($request->notice_received == 2){
                                $LeadLog->description = "Opposition Notice Not Recived";
                            }
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = 'Assign';
                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'Opposition Notice Status Update successfully');
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong while updatng log');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong while updatng log');
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

    public function oppositionCounterStatement($id)
    {
        if ($id) {
            LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with([
            'user',
            'lead',
            'services',
            'subService',
            'leadTaskDetails',
            'serviceSatge'
        ])->where('id', $id)->first();

        $users = User::where('role', '>', 4)
            ->where('archive', 1)
            ->where('status', 1)
            ->get();

        $stageId = $taskDetails->service_stage_id ?? null;
        $getStage = ServiceStages::where('service_id', 1)
            ->where('id', '>', $stageId)
            ->first();

        $leadTaskdetials = LeadTaskDetail::find($id);

        if ($leadTaskdetials && $leadTaskdetials->status == 3) {
            $getStage = ServiceStages::where('service_id', 1)
                ->where('id', 25)
                ->first();
        }

        $onHideSatge = ServiceStages::where('service_id', 1)
            ->where('id', 26)
            ->first();

        $header_title_name = $taskDetails->serviceSatge->title ?? 'Default Title';

        
        return view('tasks.tradeMark.counter_statement', compact('id',
            'header_title_name',
            'taskDetails',
            'leadTaskdetials',
            'users',
            'getStage',
            'onHideSatge'));
    }

    public function oppositionCounterStatementStatus(Request $request, $id)
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
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');

        $rule = [
            'Counter_statement' => 'required',
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
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
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
                            if($request->Counter_statement == 1){
                                $LeadLog->remark = 'Counter Statement Filied';
                                $oldValue = [
                                    'status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate ?? "N/A",
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'status' => 'Completed',
                                    'Counter Statement Date' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                ];
                            }else if($request->Counter_statement == 1){
                                $LeadLog->remark = 'Counter Statement Not Filied';
                                $oldValue = [
                                    'status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate ?? "N/A",
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'status' => 'Completed',
                                    'Counter Statement Date' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                ];
                            }
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);
                            if($request->Counter_statement == 1){
                                $LeadLog->description = "Counter Statement Filied ";
                            }else if($request->Counter_statement == 2){
                                $LeadLog->description = "Counter Statement Not Filied ";
                            }
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = 'Assign';
                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'Counter Statement Status Update Successfully');
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

    public function noticeSent($id){
        if ($id) {
            LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with([
            'user',
            'lead',
            'services',
            'subService',
            'leadTaskDetails',
            'serviceSatge'
        ])->where('id', $id)->first();

        $users = User::where('role', '>', 4)
            ->where('archive', 1)
            ->where('status', 1)
            ->get();

        $stageId = $taskDetails->service_stage_id ?? null;
        $getStage = ServiceStages::where('service_id', 1)
            ->where('id', '>', $stageId)
            ->first();

        $leadTaskdetials = LeadTaskDetail::find($id);

        if ($leadTaskdetials && $leadTaskdetials->status == 3) {
            $getStage = ServiceStages::where('service_id', 1)
                ->where('id', 23)
                ->first();
        }

        $onHideSatge = ServiceStages::where('service_id', 1)
            ->where('id', 26)
            ->first();

        $header_title_name = $taskDetails->serviceSatge->title ?? 'Default Title';

        
        return view('tasks.tradeMark.registry_sent_notice', compact('id',
            'header_title_name',
            'taskDetails',
            'leadTaskdetials',
            'users',
            'getStage',
            'onHideSatge'));
    }

    public function noticeSentStatus(Request $request , $id){
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $userName = Auth::user()->name;
        $newTaskTitle = ServiceStages::find($request->stage_id);
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');
        
        $rule = [
            'notice_sent' => 'required',
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
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
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
                            if($request->notice_sent == 1){
                                $LeadLog->remark = 'Notice Sent';
                                $oldValue = [
                                    'status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'status' => 'Completed',
                                    'Sent On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                ];
                            }else if($request->notice_sent == 2){
                                $LeadLog->remark = 'Notice Not Sent ';
                                $oldValue = [
                                    'status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'status' => 'Completed',
                                    'Verified On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                ];
                            }
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);
                             
                            $LeadLog->description = "Notice Sent To Opponent";
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = 'Assign';
                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'Notice sent to opponent staus updated successfully');
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong while updatng log');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong while updatng log');
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

    public function opponentEvidenceSubmission($id)
    {
        if ($id) {
            LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with([
            'user',
            'lead',
            'services',
            'subService',
            'leadTaskDetails',
            'serviceSatge'
        ])->where('id', $id)->first();

        $users = User::where('role', '>', 4)
            ->where('archive', 1)
            ->where('status', 1)
            ->get();

        $stageId = $taskDetails->service_stage_id ?? null;
        $getStage = ServiceStages::where('service_id', 1)
            ->where('id', '>', $stageId)
            ->first();

        $leadTaskdetials = LeadTaskDetail::find($id);

        if ($leadTaskdetials && $leadTaskdetials->status == 3) {
            $getStage = ServiceStages::where('service_id', 1)
                ->where('id', 24)
                ->first();
        }

        $onHideSatge = ServiceStages::where('service_id', 1)
            ->where('id', 26)
            ->first();

        $header_title_name = $taskDetails->serviceSatge->title ?? 'Default Title';

        return view('tasks.tradeMark.opponent_evidence', compact(
            'id',
            'header_title_name',
            'taskDetails',
            'leadTaskdetials',
            'users',
            'getStage',
            'onHideSatge'
        ));
    }

    public function opponentEvidenceSubmissionStatus(Request $request, $id)
    {  
        
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        if($request->deadline){

            $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        }
        if($request->evidence_submit){
            $evidence_submit = Carbon::createFromFormat('d M Y', $request->input('evidence_submit'))->format('Y-m-d');
        }
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $existedOpponentDetails = Evidence::where('lead_id', $existedLeaedTask->lead_id)
            ->orderBy('id', 'ASC')
            ->first();
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');
        $userName = Auth::user()->name;
        $rule = [
            'opponent_evidence' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            if ($request->opponent_evidence == 1) {
                $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $existedLeaedTask->service_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->service_stage_id = $request->stage_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->assign_by = Auth::id();
                $newLeadtask->task_title = $newTaskTitle->title;
                $existedLeaedTask->task_description = $request->description;
                $existedLeaedTask->save();
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
                        $existedLeaedTaskDetails->attachment = json_encode($filePaths) ?? null;
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $newLeadTaskDeatails->task_id = $newLeadtask->id;
                        $newLeadTaskDeatails->dead_line = $deadlineDate;
                        $newLeadTaskDeatails->status = 0;
                        if ($newLeadTaskDeatails->save()) {
                            $newEvidencedetails = new Evidence();
                            $newEvidencedetails->task_id = $existedLeaedTask->id;
                            $newEvidencedetails->lead_id = $existedLeaedTask->lead_id;
                            $newEvidencedetails->reference_id = $existedOpponentDetails->id ?? 0;
                            $newEvidencedetails->opposition_number = $existedOpponentDetails->opposition_number ?? "N/A";
                            $newEvidencedetails->opponent_name = $existedOpponentDetails->opponent_name ?? "N/A";
                            $newEvidencedetails->advocate_name = $existedOpponentDetails->advocate_name ?? "N/A";
                            $newEvidencedetails->address = $existedOpponentDetails->address ?? "N/A";
                            $newEvidencedetails->status = 0;
                            $newEvidencedetails->remark = $request->opponent_status ?? null;
                            $newEvidencedetails->reason = $request->reason;
                            $newEvidencedetails->opposition_date = $existedOpponentDetails->opposition_date;
                            $newEvidencedetails->evidence_received = $evidence_submit;
                            if ($request->hasFile('attachment')) {
                                $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                                if (!file_exists($folderPath)) {
                                    mkdir($folderPath, 0755, true);
                                }
                                foreach ($request->file('attachment') as $file) {
                                    if ($file->isValid()) {
                                        $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                        $file->move($folderPath, $fileName);
                                        $filePaths[] = $fileName;
                                    }
                                }
                                $newEvidencedetails->attachment = json_encode($filePaths);
                            }
                            if ($newEvidencedetails->save()) {
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
                                    $LeadLog->remark = "Opponent Evidence Submission";
                                    $oldValue = [
                                        'Status' => 'Pending',
                                        'Assigned On' => $formattedCreatedDate ?? Null,
                                        'Assigned By' => $existedLeaedTask->userAssignBy->name,
                                    ];
                                    $newValue = [
                                        'Status' =>  'Completed',
                                        'Evidence Received On' => $evidence_submit,
                                        'Assigned To' => $existedLeaedTask->user->name,
                                    ];
                                    $LeadLog->old_value = Json_encode($oldValue);
                                    $LeadLog->new_value = Json_encode($newValue);
                                    $LeadLog->description = "Opponent Evidence status marked as submitted";
                                    if ($LeadLog->save()) {
                                        $newassignlog = new leadLog();
                                        $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                        $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                        $newassignlog->task_id = $newLeadtask->id;
                                        $newassignlog->assign_by = Auth::id();
                                        $newassignlog->remark = 'Assign';

                                        $newassignlog->description =  "Lead assigned for next task";
                                        if ($newassignlog->save()) {
                                            $id = $newLeadtask->id;
                                            return redirect()->route('task.index')->with('success', 'Oppnent evidence verification completed');
                                        }
                                    } else {
                                        return redirect()->back()->with('error', 'there is something wrong during upadate logs');
                                    }
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong during notification logs');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong during update  new opponent evidence details');
                            }
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong during update  new task details');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong during update  existed task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is something wrong during update  new task');
                }
            } else if ( $request->opponent_evidence == 3) {
                $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $existedLeaedTask->service_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->service_stage_id = $request->stage_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->assign_by = Auth::id();
                $newLeadtask->task_title = $newTaskTitle->title;

                if ($newLeadtask->save()) {
                    $newLeadTaskDeatails->task_id = $newLeadtask->id;
                    $newLeadTaskDeatails->dead_line = $deadlineDate;
                    $newLeadTaskDeatails->status = 0;
                    if ($newLeadTaskDeatails->save()) {

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
                            $existedLeaedTaskDetails->attachment = json_encode($filePaths) ?? null;
                        }
                        if ($existedLeaedTaskDetails->save()) {

                            $newEvidencedetails = new Evidence();
                            $newEvidencedetails->task_id = $existedLeaedTask->id;
                            $newEvidencedetails->lead_id = $existedLeaedTask->lead_id;
                            $newEvidencedetails->reference_id = $existedOpponentDetails->id ?? 0;
                            $newEvidencedetails->opposition_number = $existedOpponentDetails->opposition_number ?? "N/A";
                            $newEvidencedetails->opponent_name = $existedOpponentDetails->opponent_name ?? "N/A";
                            $newEvidencedetails->advocate_name = $existedOpponentDetails->advocate_name ?? "N/A";
                            $newEvidencedetails->address = $existedOpponentDetails->address ?? "N/A";
                            $newEvidencedetails->status = 0;
                            $newEvidencedetails->remark = $request->opponent_status ?? null;
                            $newEvidencedetails->reason = $request->reason;
                            $newEvidencedetails->opposition_date = $existedOpponentDetails->opposition_date;
                            $newEvidencedetails->evidence_received = null;
                            if ($request->hasFile('attachment')) {
                                $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                                if (!file_exists($folderPath)) {
                                    mkdir($folderPath, 0755, true);
                                }
                                foreach ($request->file('attachment') as $file) {
                                    if ($file->isValid()) {
                                        $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                        $file->move($folderPath, $fileName);
                                        $filePaths[] = $fileName;
                                    }
                                }
                                $newEvidencedetails->attachment = json_encode($filePaths);
                            }
                            if ($newEvidencedetails->save()) {
                                $LeadLog =  new LeadLog();
                                $LeadLog->user_id = $existedLeaedTask->user_id;
                                $LeadLog->lead_id = $existedLeaedTask->lead_id;
                                $LeadLog->task_id = $existedLeaedTask->id;
                                $LeadLog->assign_by = Auth::id();
                                $LeadLog->remark = "Opponent Evidence Submission";
                                $oldValue = [
                                    'Status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate ?? Null,
                                    'Assigned By' => $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'Status' =>  'Completed as (Non-Compliance Intimation Registration)',
                                    'Verified On' => $verifiedDate,
                                    'Assigned To' => $existedLeaedTask->user->name,
                                ];
                                $LeadLog->old_value = Json_encode($oldValue);
                                $LeadLog->new_value = Json_encode($newValue);
                                $LeadLog->description = "Opponent Evidence status marked as not submitted";
                                
                                if ($LeadLog->save()) {
                                        $newassignlog = new leadLog();
                                        $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                        $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                        $newassignlog->task_id = $newLeadtask->id;
                                        $newassignlog->assign_by = Auth::id();
                                        $newassignlog->remark = 'Assign';

                                        $newassignlog->description =  "Lead assigned for next task";
                                        if($newassignlog->save()){

                                            return redirect()->route('task.index')->with('success', 'Oppnent evidence verification  completed');
                                        }
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong while updating log');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong while updating evidence status');
                            }
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong while updating existed lead task details');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong while updating new task');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is something wrong while updating new lead task details');
                }
            } 
        } else {
            return redirect()->back()->with('erroe', "no task found");
        }
    }
    public function applicantEvidenceSubmission($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->where('archive', 1)->get();
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $onHideSatge = ServiceStages::where('service_id', 1)->where('id', '=', 26)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.applicant_evidence', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'onHideSatge'));
    }

    public function applicantEvidenceSubmissionStatus(Request $request, $id)
    {
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        if($request->deadline){

            $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        }
        if($request->evidence_submit){
            $evidence_submit = Carbon::createFromFormat('d M Y', $request->input('evidence_submit'))->format('Y-m-d');
        }
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $existedOpponentDetails = Evidence::where('lead_id', $existedLeaedTask->lead_id)
            ->orderBy('id', 'ASC')
            ->first();
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');
        $userName = Auth::user()->name;
        $rule = [
            'applicant_evidence' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            if ($request->applicant_evidence == 1) {
                $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $existedLeaedTask->service_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->service_stage_id = $request->stage_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->assign_by = Auth::id();
                $newLeadtask->task_title = $newTaskTitle->title;
                $existedLeaedTask->task_description = $request->description;
                $existedLeaedTask->save();
                if ($newLeadtask->save()) {
                    $existedLeaedTaskDetails->status = 1;
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->comment = "File Completed";
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
                        $existedLeaedTaskDetails->attachment = json_encode($filePaths) ?? null;
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $newLeadTaskDeatails->task_id = $newLeadtask->id;
                        $newLeadTaskDeatails->dead_line = $deadlineDate;
                        $newLeadTaskDeatails->status = 0;
                        if ($newLeadTaskDeatails->save()) {
                            $newEvidencedetails = new Evidence();
                            $newEvidencedetails->task_id = $existedLeaedTask->id;
                            $newEvidencedetails->lead_id = $existedLeaedTask->lead_id;
                            $newEvidencedetails->reference_id = $existedOpponentDetails->id ?? 0;
                            $newEvidencedetails->opposition_number = $existedOpponentDetails->opposition_number ?? "N/A";
                            $newEvidencedetails->opponent_name = $existedOpponentDetails->opponent_name ?? "N/A";
                            $newEvidencedetails->advocate_name = $existedOpponentDetails->advocate_name ?? "N/A";
                            $newEvidencedetails->address = $existedOpponentDetails->address ?? "N/A";
                            $newEvidencedetails->status = 1;
                            $newEvidencedetails->remark = $request->applicant_status ?? null;
                            $newEvidencedetails->reason = $request->reason;
                            $newEvidencedetails->opposition_date = $existedOpponentDetails->opposition_date;
                            $newEvidencedetails->evidence_submit = $evidence_submit;
                            if ($request->hasFile('attachment')) {
                                $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                                if (!file_exists($folderPath)) {
                                    mkdir($folderPath, 0755, true);
                                }
                                foreach ($request->file('attachment') as $file) {
                                    if ($file->isValid()) {
                                        $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                        $file->move($folderPath, $fileName);
                                        $filePaths[] = $fileName;
                                    }
                                }
                                $newEvidencedetails->attachment = json_encode($filePaths);
                            }
                            if ($newEvidencedetails->save()) {
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
                                    $LeadLog->remark = "Applicant Evidence Submission";
                                    $oldValue = [
                                        'Status' => 'Pending',
                                        'Assigned On' => $formattedCreatedDate ?? Null,
                                        'Assigned By' => $existedLeaedTask->userAssignBy->name,
                                    ];
                                    $newValue = [
                                        'Status' =>  'Completed',
                                        'Evidence Submit On' => $evidence_submit,
                                        'Assigned To' => $existedLeaedTask->user->name,
                                    ];
                                    $LeadLog->old_value = Json_encode($oldValue);
                                    $LeadLog->new_value = Json_encode($newValue);
                                    $LeadLog->description = "Opponent Evidence status marked as submitted";
                                    if ($LeadLog->save()) {
                                        $newassignlog = new leadLog();
                                        $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                        $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                        $newassignlog->task_id = $newLeadtask->id;
                                        $newassignlog->assign_by = Auth::id();
                                        $newassignlog->remark = 'Assign';

                                        $newassignlog->description =  "Lead assigned for next task";
                                        if ($newassignlog->save()) {
                                            $id = $newLeadtask->id;
                                            return redirect()->route('task.index')->with('success', 'Oppnent evidence verification completed');
                                        }
                                    } else {
                                        return redirect()->back()->with('error', 'there is something wrong during upadate logs');
                                    }
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong during notification logs');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong during update  new opponent evidence details');
                            }
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong during update  new task details');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong during update  existed task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is something wrong during update  new task');
                }
            } else if ( $request->applicant_evidence == 3) {
                $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $existedLeaedTask->service_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->service_stage_id = $request->stage_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->assign_by = Auth::id();
                $newLeadtask->task_title = $newTaskTitle->title;

                if ($newLeadtask->save()) {
                    $newLeadTaskDeatails->task_id = $newLeadtask->id;
                    $newLeadTaskDeatails->dead_line = $deadlineDate;
                    $newLeadTaskDeatails->status = 0;
                    if ($newLeadTaskDeatails->save()) {

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
                            $existedLeaedTaskDetails->attachment = json_encode($filePaths) ?? null;
                        }
                        if ($existedLeaedTaskDetails->save()) {
                            $newEvidencedetails = new Evidence();
                            $newEvidencedetails->task_id = $existedLeaedTask->id;
                            $newEvidencedetails->lead_id = $existedLeaedTask->lead_id;
                            $newEvidencedetails->reference_id = $existedOpponentDetails->id ?? 0;
                            $newEvidencedetails->opposition_number = $existedOpponentDetails->opposition_number ?? "N/A";
                            $newEvidencedetails->opponent_name = $existedOpponentDetails->opponent_name ?? "N/A";
                            $newEvidencedetails->advocate_name = $existedOpponentDetails->advocate_name ?? "N/A";
                            $newEvidencedetails->address = $existedOpponentDetails->address ?? "N/A";
                            $newEvidencedetails->status = 1;
                            $newEvidencedetails->remark = $request->applicant_status ?? null;
                            $newEvidencedetails->reason = $request->reason;
                            $newEvidencedetails->opposition_date = $existedOpponentDetails->opposition_date;
                            $newEvidencedetails->evidence_submit = null;
                            if ($request->hasFile('attachment')) {
                                $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                                if (!file_exists($folderPath)) {
                                    mkdir($folderPath, 0755, true);
                                }
                                foreach ($request->file('attachment') as $file) {
                                    if ($file->isValid()) {
                                        $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                        $file->move($folderPath, $fileName);
                                        $filePaths[] = $fileName;
                                    }
                                }
                                $newEvidencedetails->attachment = json_encode($filePaths);
                            }
                            if ($newEvidencedetails->save()) {
                                $LeadLog =  new LeadLog();
                                $LeadLog->user_id = $existedLeaedTask->user_id;
                                $LeadLog->lead_id = $existedLeaedTask->lead_id;
                                $LeadLog->task_id = $existedLeaedTask->id;
                                $LeadLog->assign_by = Auth::id();
                                $LeadLog->remark = "Opponent Evidence Submission";
                                $oldValue = [
                                    'Status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate ?? Null,
                                    'Assigned By' => $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'Status' =>  'Completed as (Non-Compliance Intimation Registration)',
                                    'Verified On' => $verifiedDate,
                                    'Assigned To' => $existedLeaedTask->user->name,
                                ];
                                $LeadLog->old_value = Json_encode($oldValue);
                                $LeadLog->new_value = Json_encode($newValue);
                                $LeadLog->description = "Applicant Evidence status marked as not submitted";
                                
                                if ($LeadLog->save()) {
                                        $newassignlog = new leadLog();
                                        $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                        $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                        $newassignlog->task_id = $newLeadtask->id;
                                        $newassignlog->assign_by = Auth::id();
                                        $newassignlog->remark = 'Assign';

                                        $newassignlog->description =  "Lead assigned for next task";
                                        if($newassignlog->save()){

                                            return redirect()->route('task.index')->with('success', 'Oppnent evidence verification  completed');
                                        }
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong while updating log');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong while updating evidence status');
                            }
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong while updating existed lead task details');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong while updating new task');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is something wrong while updating new lead task details');
                }
            } 
        } else {
            return redirect()->back()->with('erroe', "no task found");
        }
    }

    public function oppositionResubmissionEvedince($id){
        if ($id) {
            LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with([
            'user',
            'lead',
            'services',
            'subService',
            'leadTaskDetails',
            'serviceSatge'
        ])->where('id', $id)->first();

        $users = User::where('role', '>', 4)
            ->where('archive', 1)
            ->where('status', 1)
            ->get();

        $stageId = $taskDetails->service_stage_id ?? null;
        $getStage = ServiceStages::where('service_id', 1)
            ->where('id', '=', 27)
            ->first();

        $leadTaskdetials = LeadTaskDetail::find($id);

        if ($leadTaskdetials && $leadTaskdetials->status == 3) {
            $getStage = ServiceStages::where('service_id', 1)
                ->where('id', 27)
                ->first();
        }

        $onHideSatge = ServiceStages::where('service_id', 1)
            ->where('id', 26)
            ->first();

        $header_title_name = $taskDetails->serviceSatge->title ?? 'Default Title';

        return view('tasks.tradeMark.opponent_evidence_resubmisson', compact(
            'id',
            'header_title_name',
            'taskDetails',
            'leadTaskdetials',
            'users',
            'getStage',
            'onHideSatge'
        ));
    }

    public function oppositionResubmissionEvidenceStatus(Request $request , $id){
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        if($request->deadline){

            $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        }
        if($request->evidence_submit){
            $evidence_submit = Carbon::createFromFormat('d M Y', $request->input('evidence_submit'))->format('Y-m-d');
        }
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $existedOpponentDetails = Evidence::where('lead_id', $existedLeaedTask->lead_id)
            ->orderBy('id', 'ASC')
            ->first();
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');
        $userName = Auth::user()->name;
        $rule = [
            'opponent_evidence' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            if ($request->opponent_evidence == 1) {
                $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $existedLeaedTask->service_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->service_stage_id = $request->stage_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->assign_by = Auth::id();
                $newLeadtask->task_title = $newTaskTitle->title;
                $existedLeaedTask->task_description = $request->description;
                $existedLeaedTask->save();
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
                        $existedLeaedTaskDetails->attachment = json_encode($filePaths) ?? null;
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $newLeadTaskDeatails->task_id = $newLeadtask->id;
                        $newLeadTaskDeatails->dead_line = $deadlineDate;
                        $newLeadTaskDeatails->status = 0;
                        if ($newLeadTaskDeatails->save()) {
                            $newEvidencedetails = new Evidence();
                            $newEvidencedetails->task_id = $existedLeaedTask->id;
                            $newEvidencedetails->lead_id = $existedLeaedTask->lead_id;
                            $newEvidencedetails->reference_id = $existedOpponentDetails->id ?? 0;
                            $newEvidencedetails->opposition_number = $existedOpponentDetails->opposition_number ?? "N/A";
                            $newEvidencedetails->opponent_name = $existedOpponentDetails->opponent_name ?? "N/A";
                            $newEvidencedetails->advocate_name = $existedOpponentDetails->advocate_name ?? "N/A";
                            $newEvidencedetails->address = $existedOpponentDetails->address ?? "N/A";
                            $newEvidencedetails->status = 0;
                            $newEvidencedetails->remark = $request->opponent_status ?? null;
                            $newEvidencedetails->reason = $request->reason;
                            $newEvidencedetails->opposition_date = $existedOpponentDetails->opposition_date;
                            $newEvidencedetails->evidence_received = $evidence_submit;
                            if ($request->hasFile('attachment')) {
                                $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                                if (!file_exists($folderPath)) {
                                    mkdir($folderPath, 0755, true);
                                }
                                foreach ($request->file('attachment') as $file) {
                                    if ($file->isValid()) {
                                        $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                        $file->move($folderPath, $fileName);
                                        $filePaths[] = $fileName;
                                    }
                                }
                                $newEvidencedetails->attachment = json_encode($filePaths);
                            }
                            if ($newEvidencedetails->save()) {
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
                                    $LeadLog->remark = "Opponent Evidence Submission";
                                    $oldValue = [
                                        'Status' => 'Pending',
                                        'Assigned On' => $formattedCreatedDate ?? Null,
                                        'Assigned By' => $existedLeaedTask->userAssignBy->name,
                                    ];
                                    $newValue = [
                                        'Status' =>  'Completed',
                                        'Evidence Received On' => $evidence_submit,
                                        'Assigned To' => $existedLeaedTask->user->name,
                                    ];
                                    $LeadLog->old_value = Json_encode($oldValue);
                                    $LeadLog->new_value = Json_encode($newValue);
                                    $LeadLog->description = "Opponent Evidence status marked as submitted";
                                    if ($LeadLog->save()) {
                                        $newassignlog = new leadLog();
                                        $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                        $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                        $newassignlog->task_id = $newLeadtask->id;
                                        $newassignlog->assign_by = Auth::id();
                                        $newassignlog->remark = 'Assign';

                                        $newassignlog->description =  "Lead assigned for next task";
                                        if ($newassignlog->save()) {
                                            $id = $newLeadtask->id;
                                            return redirect()->route('task.index')->with('success', 'Oppnent evidence verification completed');
                                        }
                                    } else {
                                        return redirect()->back()->with('error', 'there is something wrong during upadate logs');
                                    }
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong during notification logs');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong during update  new opponent evidence details');
                            }
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong during update  new task details');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong during update  existed task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is something wrong during update  new task');
                }
            } else if ( $request->opponent_evidence == 3) {
                $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $existedLeaedTask->service_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->service_stage_id = $request->stage_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->assign_by = Auth::id();
                $newLeadtask->task_title = $newTaskTitle->title;

                if ($newLeadtask->save()) {
                    $newLeadTaskDeatails->task_id = $newLeadtask->id;
                    $newLeadTaskDeatails->dead_line = $deadlineDate;
                    $newLeadTaskDeatails->status = 0;
                    if ($newLeadTaskDeatails->save()) {

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
                            $existedLeaedTaskDetails->attachment = json_encode($filePaths) ?? null;
                        }
                        if ($existedLeaedTaskDetails->save()) {

                            $newEvidencedetails = new Evidence();
                            $newEvidencedetails->task_id = $existedLeaedTask->id;
                            $newEvidencedetails->lead_id = $existedLeaedTask->lead_id;
                            $newEvidencedetails->reference_id = $existedOpponentDetails->id ?? 0;
                            $newEvidencedetails->opposition_number = $existedOpponentDetails->opposition_number ?? "N/A";
                            $newEvidencedetails->opponent_name = $existedOpponentDetails->opponent_name ?? "N/A";
                            $newEvidencedetails->advocate_name = $existedOpponentDetails->advocate_name ?? "N/A";
                            $newEvidencedetails->address = $existedOpponentDetails->address ?? "N/A";
                            $newEvidencedetails->status = 0;
                            $newEvidencedetails->remark = $request->opponent_status ?? null;
                            $newEvidencedetails->reason = $request->reason;
                            $newEvidencedetails->opposition_date = $existedOpponentDetails->opposition_date;
                            $newEvidencedetails->evidence_received = null;
                            if ($request->hasFile('attachment')) {
                                $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                                if (!file_exists($folderPath)) {
                                    mkdir($folderPath, 0755, true);
                                }
                                foreach ($request->file('attachment') as $file) {
                                    if ($file->isValid()) {
                                        $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                        $file->move($folderPath, $fileName);
                                        $filePaths[] = $fileName;
                                    }
                                }
                                $newEvidencedetails->attachment = json_encode($filePaths);
                            }
                            if ($newEvidencedetails->save()) {
                                $LeadLog =  new LeadLog();
                                $LeadLog->user_id = $existedLeaedTask->user_id;
                                $LeadLog->lead_id = $existedLeaedTask->lead_id;
                                $LeadLog->task_id = $existedLeaedTask->id;
                                $LeadLog->assign_by = Auth::id();
                                $LeadLog->remark = "Opponent Evidence Submission";
                                $oldValue = [
                                    'Status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate ?? Null,
                                    'Assigned By' => $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'Status' =>  'Completed as Non-Compliance Intimation Registration',
                                    'Verified On' => $verifiedDate,
                                    'Assigned To' => $existedLeaedTask->user->name,
                                ];
                                $LeadLog->old_value = Json_encode($oldValue);
                                $LeadLog->new_value = Json_encode($newValue);
                                $LeadLog->description = "Opponent Evidence status marked as not submitted";
                                
                                if ($LeadLog->save()) {
                                        $newassignlog = new leadLog();
                                        $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                        $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                        $newassignlog->task_id = $newLeadtask->id;
                                        $newassignlog->assign_by = Auth::id();
                                        $newassignlog->remark = 'Assign';

                                        $newassignlog->description =  "Lead assigned for next task";
                                        if($newassignlog->save()){

                                            return redirect()->route('task.index')->with('success', 'Oppnent evidence verification  completed');
                                        }
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong while updating log');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong while updating evidence status');
                            }
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong while updating existed lead task details');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong while updating new task');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is something wrong while updating new lead task details');
                }
            } 
        } else {
            return redirect()->back()->with('erroe', "no task found");
        }
    }

    public function nonCompliance($id){
        
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
        return view('tasks.tradeMark.non_complaince_register', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }

    public function nonComplianceStatus(Request $request , $id){
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
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');

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
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
            if ($newLeadtask->save()) {
                $existedLeaedTaskDetails->status = 1;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $existedLeaedTaskDetails->comment = "Non-Compliance Intimation Registration";

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
                            $LeadLog->remark = 'Non-Compliance Intimation Registration';
                            $oldValue = [
                                'status' => 'Pending',
                                'Assigned On' => $formattedCreatedDate,
                                'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                            ];
                            $newValue = [
                                'status' => 'Completed',
                                'Non-Compliance On' => $request->verified,
                                'Assigned To' =>  $existedLeaedTask->user->name,
                            ];
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);
                            $LeadLog->description = "Non-Compliance Intimation Registration successfully";
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = 'Assign';

                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'Non Compliance Status updated');
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong while updatng log');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong while updatng log');
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
    public function oppositionHearingDate($id){
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->where('archive', 1)->get();
        $applicationNumber = ServiceDetail::where('lead_id', $taskDetails->lead_id)
        ->orderBy('id', 'asc')
        ->first();
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.opposition_hearing', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'applicationNumber'));
    }

    public function oppositionHearingDateStatus(Request $request , $id){

       
        $hearingDate = Carbon::createFromFormat('d M Y', $request->input('hearing_date'))->format('Y-m-d');
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::find($id);
      
        $newHearing = new HearingDateDetails();
        $existedHearing = HearingDateDetails::where('task_id', $existedLeaedTask->id)->latest()->first();

        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');

        $rule = [
            'hearing_date' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
            $newLeadtask->lead_id = $existedLeaedTask->lead_id;
            $newLeadtask->service_id = $existedLeaedTask->service_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->service_stage_id = $request->stage_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->assign_by = Auth::id();
            $newLeadtask->task_title = $newTaskTitle->title;
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
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
                        $newHearing->task_id  = $newLeadtask->id;
                        $newHearing->lead_id =  $existedLeaedTask->lead_id;
                        $newHearing->stage_id = $existedLeaedTask->service_stage_id;

                        $newHearing->reason =  Null;
                        $newHearing->hearing_date =  $hearingDate;
                        if ($existedHearing) {
                            $newHearing->count = $existedHearing->count ? $existedHearing->count + 1 : 0;
                        } else {
                            $newHearing->count = 1;
                        }
                        if ($newHearing->save()) {
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
                                $LeadLog->remark = 'Hearing(Objection)';
                                $oldValue = [
                                    'status' => 'Pending',
                                    'Assigned On' =>  $formattedCreatedDate,
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                    'Hearing Date' => "N/A"
                                ];
                                $newValue = [
                                    'status' => 'Completed',
                                    'Verified On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                    'Hearing Date' => $request->hearing_date,
                                ];
                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);
                                $LeadLog->description = "Application status moved to the hearing stage.";
                                if ($LeadLog->save()) {
                                    $newassignlog = new leadLog();
                                    $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                    $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                    $newassignlog->task_id = $newLeadtask->id;
                                    $newassignlog->assign_by = Auth::id();
                                    $newassignlog->remark = 'Assign';
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
                            return redirect()->back()->with('error', 'there is something wrong while updating new hearing date');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong during update  new task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is something wrong during update  existed task details');
                }
            } else {
                return redirect()->back()->with('error', 'there is something wrong during update  new task');
            }
        } else {
            return redirect()->back()->with('erroe', "no task found");
        }
    }

    public function clientApprovalOnHearing($id){
        if ($id) {
            LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with([
            'user',
            'lead',
            'services',
            'subService',
            'leadTaskDetails',
            'serviceSatge'
        ])->where('id', $id)->first();

        $users = User::where('role', '>', 4)
            ->where('archive', 1)
            ->where('status', 1)
            ->get();

        $stageId = $taskDetails->service_stage_id ?? null;
        $getStage = ServiceStages::where('service_id', 1)
            ->where('id', '>', $stageId)
            ->first();

        $leadTaskdetials = LeadTaskDetail::find($id);

        if ($leadTaskdetials && $leadTaskdetials->status == 3) {
            $getStage = ServiceStages::where('service_id', 1)
                ->where('id', 23)
                ->first();
        }

        $onHideSatge = ServiceStages::where('service_id', 1)
            ->where('id', 33)
            ->first();

        $header_title_name = $taskDetails->serviceSatge->title ?? 'Default Title';

        
        return view('tasks.tradeMark.client_approval_hearing', compact('id',
            'header_title_name',
            'taskDetails',
            'leadTaskdetials',
            'users',
            'getStage',
            'onHideSatge'));
    }

    public function clientApprovalOnHearingStatus(Request $request , $id){
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $userName = Auth::user()->name;
        $newTaskTitle = ServiceStages::find($request->stage_id);
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');
       
        $rule = [
            'client_status' => 'required',
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
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
            if ($newLeadtask->save()) {
                $existedLeaedTaskDetails->status = 1;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                if($request->client_status == 1){

                    $existedLeaedTaskDetails->comment = 'Client decided to proceed';
                }else if($request->client_status == 2){
                    $existedLeaedTaskDetails->comment = 'Client decided not to proceed';

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
                            if($request->client_status == 1){
                                $LeadLog->remark = 'Client decided to proceed';
                                $oldValue = [
                                    'status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'status' => 'Completed',
                                    'Client Verified On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                ];
                            }else if($request->client_status == 2){
                                $LeadLog->remark = 'Client decided not to proceed';
                                $oldValue = [
                                    'status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'status' => 'Completed',
                                    'Client Verified On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                ];
                            }
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);
                            if($request->client_status == 1){
                                $LeadLog->description = 'Client decided to proceed';
                            }else if($request->client_status == 2){
                                $LeadLog->description = 'Client decided not to proceed';
            
                            }
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = 'Assign';
                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'Clent approval staus on hearing updated successfully');
                                } else {
                                    return redirect()->back()->with('error', 'there is something wrong while updatng log');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong while updatng log');
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

    public function clientInformForHearingCharge($id){
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }
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
            $header_title_name = $value->serviceSatge->title;
        }
        $getStage = ServiceStages::where('service_id', $serviceID)->where('id', '>', $stage_id)->first();
        $leadTaskdetials = LeadTaskDetail::find($taskDetailsId);
        return view('tasks.tradeMark.proforma_invoice_on_hearing', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'serviceName', 'clientName'));
    }

    public function clientInformForHearingChargeStatus(Request $request , $id){
        // dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedTask = LeadTask::with(['services', 'subService', 'lead', 'serviceSatge'])->where('id', $id)->first();
        $existedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newPayment = new Payment();
        $newTaskAssigned = new LeadTask();
        $newTaskDetails = new LeadTaskDetail();
        $serviceId = $existedTask->services->id;
        $subServiceID = $existedTask->subService->id;
        $formattedCreatedDate = $existedTask->created_at->format('d M Y');


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
        $gst = $request->gst ?? null;
        $total_without_gst = $service_price + $govt_price;
        if (!empty($gst)) {
            $gstApply  = "Apply";
            $gst_amount = $service_price * 0.18;
            $total = $total_without_gst + $gst_amount;
        } else {
            $gstApply  = "Not Apply";
            $total = $total_without_gst;
            $gst_amount = 0;
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
            $existedTask->task_description = $request->description;
            $existedTask->save();
            if ($newTaskAssigned->save()) {
                $newTaskDetails->task_id = $newTaskAssigned->id;
                $newTaskDetails->status = 0;
                $newTaskDetails->dead_line = $deadlineDate;
                if ($newTaskDetails->save()) {
                    $existedTaskDetails->status_date = $verifiedDate;
                    $existedTaskDetails->status = 1;
                    $existedTaskDetails->mail_subject = $request->subject;
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

                        $newPayment->lead_id = $existedTask->lead_id;
                        $newPayment->task_id = $newTaskDetails->task_id;
                        $newPayment->reference_id = 0;
                        $newPayment->service_price = $service_price;
                        $newPayment->govt_price = $govt_price;
                        $newPayment->gst = $gst_amount ?? 0;
                        $newPayment->total = $total;
                        $newPayment->pending_amount = $total;
                        if ($newPayment->save()) {
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
                                $LeadLog->remark = "proforma invoice";
                                $oldValue = [
                                    'Status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' => $existedTask->userAssignBy->name,
                                    'Payment' => 'Pending'
                                ];
                                $newValue = [
                                    'Status' => 'Completed',
                                    'Sent On' => $verifiedDate ?? null,
                                    'Assigned To' => $existedTask->user->name,
                                   
                                    'Service Price' => $request->service_price ?? null,
                                    'Govt Price' => $request->govt_price,
                                    'gst' => $gstApply . " " . "₹" . $gst_amount . "" . "(%18)",
                                ];
                                
                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);
                                $LeadLog->description = "proforma invoice sent to the client";
                                if ($LeadLog->save()) {
                                    $newassignlog = new leadLog();
                                    $newassignlog->user_id = $request->assignUser ?? $existedTask->user_id;
                                    $newassignlog->lead_id = $existedTask->lead_id;
                                    $newassignlog->task_id = $newTaskAssigned->id;
                                    $newassignlog->assign_by = Auth::id();
                                    $newassignlog->remark = 'Assign';

                                    $newassignlog->description =  "Lead assigned for next task";
                                    if ($newassignlog->save()) {
                                        // if ($mail== true) {
                                        //     SendTaskCommanMailJob::dispatch($subject, $service, $service_price, $govt_price, $clientName, $clientEmail, $userName);
                                        // }
                                    }
                                    $id = $newTaskAssigned->id;
                                    return redirect()->route('task.index')
                                        ->with('success', 'proforma invoice sent successfully');
                                } else {
                                    return redirect()->back()->error('message', " there is something wrong during update logs ");
                                }
                            } else {
                                return redirect()->back()->error('message', " there is something wrong during update payment ");
                            }
                        } else {
                            return redirect()->back()->error('message', " there is something wrong during update existed tasl details ");
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

    public function hearingChargeUpdate ($id){
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }
        $header_title_name = "payment Status";
        $taskDetails = LeadTask::with(['user', 'payment', 'lead', 'leadTaskDetails', 'services', 'subService', 'serviceSatge'])
            ->where('id', $id)
            ->get();
        foreach ($taskDetails as $task) {
            $lastPayment = $task->payment->last();

            if ($lastPayment) {
                $paymentId = $lastPayment->id;
                $payamentDetails = Payment::where('id', $paymentId)->first();
            }
        }
        foreach ($taskDetails as $task) {
            $taskDetailsId = $task->id;
        }
        $firstPaymentId = Payment::where('task_id', $taskDetailsId)->OrderBy('id', 'ASC')->first();

        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        foreach ($taskDetails as $value) {
            $stageId = $value->service_stage_id;
        }
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($taskDetailsId);
        return view('tasks.tradeMark.hearing_charge', compact('id','taskDetailsId', 'firstPaymentId', 'payamentDetails', 'paymentId', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }
    public function hearingChargeStatus(Request $request , $id)
    {
        // dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        if ($request->paymentDeadline) {

            $paymentDeadlineDate = Carbon::createFromFormat('d M Y', $request->input('paymentDeadline'))->format('Y-m-d');
        }
        if ($request->deadline) {

            $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        }
        $existedLeaedTask = LeadTask::with(['lead', 'services', 'subService', 'serviceSatge', 'userAssignBy'])->where('id', $id)->first();
        $client_id = $existedLeaedTask->lead->client_id;
        $existed_leadId = $existedLeaedTask->lead->id;

        $leadUpdate = Lead::find($existed_leadId);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $existedPayment = Payment::where('task_id', $id)->where('lead_id', $existedLeaedTask->lead_id)->where('id', $request->paymentId)->first();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $serviceId = $existedLeaedTask->services->id;
        $subServiceId = $existedLeaedTask->subService->id;
        $stageId = (int) $request->stage_id;
        $userName =  Auth::user()->name;
        $assignedStageName = ServiceStages::where('id', $stageId)->first();
        $rule = [
            'payment' => 'required',
            'deadline' => 'nullable',
            'verified' => 'required',
            'stage_id' => 'required',
            'attachment' => 'array',
            'attachment.*' => 'nullable',
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');

        if ($id) {
            if ($request->checkStatus == 0) {
                if ($request->payment == 1 || $request->payment == 2 || $request->payment == 3) {
                    $existedLeaedTask->task_description = $request->description;
                    $existedLeaedTask->save();
                    $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                    $newLeadtask->project_manager_id = $existedLeaedTask->project_manager_id;
                    $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                    $newLeadtask->service_id = $existedLeaedTask->service_id;
                    $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                    $newLeadtask->service_stage_id = $stageId;
                    $newLeadtask->sub_stage_id = null;
                    $newLeadtask->assign_by = Auth::id();
                    if ($newLeadtask->save()) {
                        if ($request->payment == 1) {
                            $existedLeaedTaskDetails->status = 1;
                        } else if ($request->payment == 2) {
                            $existedLeaedTaskDetails->status = 3;
                        } else if ($request->payment == 3) {
                            $existedLeaedTaskDetails->status = 3;
                        }
                        $existedLeaedTaskDetails->status_date = $verifiedDate;
                        if ($request->payment == 1) {
                            $existedLeaedTaskDetails->comment = 'Paid';
                        } else if ($request->payment == 2) {
                            $existedLeaedTaskDetails->comment = 'Partial Payment';
                        } else if ($request->payment == 3) {
                            $existedLeaedTaskDetails->comment = 'On Credit';
                        }
                        if ($request->payment == 1) {
                            $existedLeaedTaskDetails->reminderDate = Null;
                        } else if ($request->payment == 2) {
                            $existedLeaedTaskDetails->reminderDate = $paymentDeadlineDate;
                        } else if ($request->payment == 3) {
                            $existedLeaedTaskDetails->reminderDate = $paymentDeadlineDate;
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
                            $existedLeaedTaskDetails->attachment = json_encode($filePaths);
                        }
                        if ($existedLeaedTaskDetails->save()) {
                            $newPayment =  new Payment();
                            $newPayment->lead_id = $existedPayment->lead_id;
                            $newPayment->task_id = $existedPayment->task_id;
                            $newPayment->reference_id = $existedPayment->id;
                            $newPayment->service_price = $existedPayment->service_price;
                            $newPayment->govt_price = $existedPayment->govt_price;
                            $newPayment->gst = $existedPayment->gst;
                            $newPayment->total = $existedPayment->total;
                            if ($request->payment == 1) {
                                $newPayment->pending_amount = 0;
                                $newPayment->submitted_amount = $existedPayment->total;
                            } else if ($request->payment == 2 && $request->partial_payment != $existedPayment->total) {
                                $pending_amount = $existedPayment->pending_amount - $request->partial_payment;
                                $newPayment->pending_amount = $pending_amount;
                                $newPayment->submitted_amount = $request->partial_payment;
                            } else if ($request->payment == 2 && $request->partial_payment == $existedPayment->total) {
                                $newPayment->pending_amount = 0;
                                $newPayment->submitted_amount = $existedPayment->total;
                            } else if ($request->payment == 3) {
                                $newPayment->pending_amount = $existedPayment->pending_amount;
                                $newPayment->submitted_amount = null;
                            }
                            if ($newPayment->save()) {
                                $newLeadTaskDeatails->task_id = $newLeadtask->id;
                                $newLeadTaskDeatails->dead_line = $deadlineDate;
                                $newLeadTaskDeatails->status = 0;
                                if ($newLeadTaskDeatails->save()) {
                                    $userAssign =  $request->assignUser ?? $existedLeaedTask->user_id;
                                    $notification = new LeadNotification();
                                    $notification->user_id =  $userAssign;
                                    $notification->lead_id = $existedLeaedTask->lead_id;
                                    $notification->task_id = $newLeadtask->id;
                                    $notification->title = "Task Assigned";
                                    $notification->description =  $userName . ' assigned you ' . $assignedStageName->title . ' task';
                                    $notification->status = 0;
                                    if ($notification->save()) {
                                        $LeadLog = new LeadLog();
                                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                                        $LeadLog->task_id =  $existedLeaedTask->id;
                                        $LeadLog->assign_by = Auth::id();
                                        $remark = "";
                                        $paidAmount = "";
                                        if ($request->payment == 1) {

                                            $remark = 'Paid';
                                            $paidAmount = $newPayment->total;
                                        } else if ($request->payment == 2 && $request->partial_payment != $existedPayment->total) {
                                            $remark = 'Partial Payment';
                                            $paidAmount = $newPayment->submitted_amount;
                                        } else if ($request->payment == 2 && $request->partial_payment == $existedPayment->total) {
                                            $remark = 'Partial Payment(Paid)';
                                            $paidAmount = $newPayment->submitted_amount;
                                        } else if ($request->payment == 3) {
                                            $remark = 'On Credit';
                                            $paidAmount = 0.00;
                                        }
                                        $LeadLog->remark = $remark;
                                        $oldValue = [
                                            'Status' => 'Pending',
                                            'Assigned On' => $formattedCreatedDate,
                                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                                            'Total Amount' => $existedPayment->total,
                                            'Paid Amount' => $existedPayment->submitted_amount,
                                            'pending_amount' => $existedPayment->pending_amount,

                                        ];

                                        $newValue = [
                                            'Status' => $remark,
                                            'Paid On' => $verifiedDate ?? null,
                                            'Assigned To' => $existedLeaedTask->user->name,
                                            'Total Amount' => $existedPayment->total,
                                            'Paid Amount' => $paidAmount,
                                            'pending_amount' => $newPayment->pending_amount,
                                        ];
                                        $LeadLog->old_value = json_encode($oldValue);
                                        $LeadLog->new_value = json_encode($newValue);
                                        $LeadLog->description = "payment status updated successfully";
                                        if ($LeadLog->save()) {
                                            $newassignlog = new leadLog();
                                            $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                            $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                            $newassignlog->task_id = $newLeadtask->id;
                                            $newassignlog->assign_by = Auth::id();
                                            $newassignlog->remark = 'Assign';

                                            $newassignlog->description =  "Lead assigned for next task";
                                            if ($newassignlog->save()) {
                                                if ($client_id == 0) {
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
                                                        $leadUpdate->client_id = $newClient->id;
                                                        $leadUpdate->client_name = $newClient->name;
                                                        if ($leadUpdate->save()) {
                                                            if ($mail == true) {
                                                                SendClientWelcomeEmail::dispatch($newClient, $randomNumber, $filePath = null, $type);
                                                            }
                                                            return redirect()->route('task.index')
                                                                ->with('success', 'payment status updated successfully');
                                                        } else {
                                                            return redirect()->back()->with('error', 'there is something wrong while  updateing existed lead');
                                                        }
                                                    } else {
                                                        return redirect()->back()->with('error', 'there is something wrong while  creating new client');
                                                    }
                                                } else {
                                                    return redirect()->route('task.index')->with('success', 'payment status updated successfully');
                                                }
                                            } else {
                                                return redirect()->back()->with('error', 'there is something wrong while updating logs');
                                            }
                                        } else {
                                            return redirect()->back()->with('error', 'there is something wrong while updating logs');
                                        }
                                    } else {
                                        return redirect()->back()->with('error', 'there is something wrong while updating notification');
                                    }
                                } else {
                                    return redirect()->back()->with('error', 'there is something worng while create new lead task details');
                                }
                            } else {
                                return redirect()->back()->with('eror', 'there is something wrong while updating payment status');
                            }
                        } else {
                            return redirect()->back()->with('eror', 'there is something wrong while updating exist task details');
                        }
                    } else {
                        return redirect()->back()->with('eror', 'there is something wrong while creating new task');
                    }
                }
            } else if ($request->checkStatus == 3 && $request->payment == 1) {
                $logStatus = $existedLeaedTaskDetails->comment;
                $oldPaidExitedDate = $existedLeaedTaskDetails->status_date;

                $newPayment = new Payment();
                $newPayment->lead_id = $existedPayment->lead_id;
                $newPayment->task_id = $existedPayment->task_id;
                $newPayment->reference_id = $existedPayment->id;
                $newPayment->reference_id = $existedPayment->id;
                $newPayment->service_price = $existedPayment->service_price;
                $newPayment->govt_price = $existedPayment->govt_price;
                $newPayment->gst = $existedPayment->gst;
                $newPayment->total = $existedPayment->total;
                $newPayment->pending_amount = $existedPayment->total;
                $pending_amount = $existedPayment->pending_amount;
                $newPayment->pending_amount = 0;
                $newPayment->submitted_amount = $pending_amount;
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($newPayment->save()) {
                    $existedLeaedTaskDetails->status = 1;
                    $existedLeaedTaskDetails->comment = 'Paid';
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->reminderDate = Null;
                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $filePaths = [];
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                        $LeadLog->task_id =  $existedLeaedTask->id;
                        $LeadLog->remark = "Paid";
                        $oldValue = [
                            'Status' => $logStatus,
                            'Last Payment On' => $oldPaidExitedDate ?? null,
                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $existedPayment->submitted_amount,
                            'pending_amount' => $existedPayment->pending_amount,
                        ];

                        $newValue = [
                            'Status' => 'Completed',
                            'Paid On' => $verifiedDate ?? null,
                            'Assigned To' => $existedLeaedTask->user->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $newPayment->submitted_amount,
                            'pending_amount' => $newPayment->pending_amount,
                        ];
                        $LeadLog->old_value = json_encode($oldValue);
                        $LeadLog->new_value = json_encode($newValue);
                        $LeadLog->assign_by = Auth::id();
                        $LeadLog->description = "payment status updated successfully";
                        if ($LeadLog->save()) {
                            return redirect()->route('task.index')
                                ->with('success', 'payment status updated successfully');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong while updating exist lead task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is soemthing wrong while updating payment status');
                }
            } else if ($request->checkStatus == 3 && $request->payment == 2) {
                $logStatus = $existedLeaedTaskDetails->comment;
                $oldPaidExitedDate = $existedLeaedTaskDetails->status_date;

                $newPayment = new Payment();
                $newPayment->lead_id = $existedPayment->lead_id;
                $newPayment->task_id = $existedPayment->task_id;
                $newPayment->reference_id = $request->firstPaymentId;
                $newPayment->service_price = $existedPayment->service_price;
                $newPayment->govt_price = $existedPayment->govt_price;
                $newPayment->gst = $existedPayment->gst;
                $newPayment->total = $existedPayment->total;
                $pending_amount = $existedPayment->pending_amount - $request->partial_payment;
                $newPayment->pending_amount = $pending_amount;
                $newPayment->submitted_amount = $request->partial_payment;
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($newPayment->save()) {
                    if ($newPayment->pending_amount == 0 ||  $newPayment->pending_amount == null || $newPayment->pending_amount == 0.00) {
                        $existedLeaedTaskDetails->status = 1;
                    } else {
                        $existedLeaedTaskDetails->status = 3;
                    }
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->comment = 'Partial Payment';
                    $existedLeaedTaskDetails->reminderDate = null;
                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $filePaths = [];
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                        $LeadLog->task_id =  $existedLeaedTask->id;
                        $LeadLog->remark =  'Partial Payment';
                        $oldValue = [
                            'Status' => $logStatus,
                            'Last payment On' => $oldPaidExitedDate,
                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $existedPayment->submitted_amount,
                            'pending_amount' => $existedPayment->pending_amount,
                        ];
                        $newValueStatus = "";
                        if ($newPayment->pending_amount == 0 ||  $newPayment->pending_amount == null || $newPayment->pending_amount == 0.00) {
                            $newValueStatus = 'Completed';
                        } else {
                            $newValueStatus = 'Partial Payment';
                        }

                        $newValue = [
                            'Status' => $newValueStatus,
                            'Paid On' => $verifiedDate ?? null,
                            'Assigned To' => $existedLeaedTask->user->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $newPayment->submitted_amount,
                            'pending_amount' => $newPayment->pending_amount,
                        ];
                        $LeadLog->old_value = json_encode($oldValue);
                        $LeadLog->new_value = json_encode($newValue);
                        $LeadLog->assign_by = Auth::id();
                        $LeadLog->description = "payment status updated successfully";
                        if ($LeadLog->save()) {
                            return redirect()->route('task.index')
                                ->with('success', 'payment status updated successfully');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something wrong whil updating exist lead task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'while updating payment status');
                }
            } else if ($request->checkStatus == 3 && $request->payment == 3) {
                $logStatus = $existedLeaedTaskDetails->comment;
                $oldPaidExitedDate = $existedLeaedTaskDetails->status_date;
                $newPayment = new Payment();
                $newPayment->lead_id = $existedPayment->lead_id;
                $newPayment->task_id = $existedPayment->task_id;
                $newPayment->reference_id = $request->firstPaymentId;
                $newPayment->service_price = $existedPayment->service_price;
                $newPayment->govt_price = $existedPayment->govt_price;
                $newPayment->gst = $existedPayment->gst;
                $newPayment->total = $existedPayment->total;
                $pending_amount = $existedPayment->pending_amount;
                $newPayment->pending_amount = $pending_amount;
                $newPayment->submitted_amount = null;
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($newPayment->save()) {
                    if ($newPayment->pending_amount == 0 || $newPayment->pending_amount == Null) {
                        $existedLeaedTaskDetails->status = 1;
                    } else {
                        $existedLeaedTaskDetails->status = 3;
                    }
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->comment = "On Credit";

                    $existedLeaedTaskDetails->reminderDate = $paymentDeadlineDate;
                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $filePaths = [];
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    if ($existedLeaedTaskDetails->save()) {
                        $LeadLog = new LeadLog();
                        $LeadLog->user_id =  $existedLeaedTask->user_id;
                        $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                        $LeadLog->task_id =  $existedLeaedTask->id;
                        $LeadLog->assign_by = Auth::id();
                        $LeadLog->remark = "On Credit";
                        $oldValue = [
                            'Status' => $logStatus,
                            'Last payment On' => $oldPaidExitedDate ?? null,
                            'Assigned By' => $existedLeaedTask->userAssignBy->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => $existedPayment->submitted_amount,
                            'Pending Amount' => $existedPayment->pending_amount,
                        ];
                        $newValue = [
                            'Status' => 'On Credit',
                            'Verified On' => $verifiedDate ?? null,
                            'Assigned To' => $existedLeaedTask->user->name,
                            'Total Amount' => $existedPayment->total,
                            'Paid Amount' => 0.00,
                            'Pending Amount' => $existedPayment->pending_amount,
                        ];
                        $LeadLog->old_value = json_encode($oldValue);
                        $LeadLog->new_value = json_encode($newValue);
                        $LeadLog->description = "payment status updated successfully";
                        if ($LeadLog->save()) {
                            return redirect()->route('task.index')
                                ->with('success', 'payment status updated successfully');
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong while updating log');
                        }
                    } else {
                        return redirect()->back()->with('error', 'there is something error while exist lead task details');
                    }
                } else {
                    return redirect()->back()->with('error', 'there is soemthing wrong while updating payment status');
                }
            }
        }
    }

    public function hearing(Request $request , $id){
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->where('archive', 1)->get();

        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        $hearingDateCount = HearingDateDetails::where('lead_id',$taskDetails->lead_id )->where('stage_id' , 27)->latest()->first();
        $count = ($hearingDateCount->count ?? 0) + 1;
        return view('tasks.tradeMark.hearing', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'count'));

    }

    public function hearingStatus(Request $request , $id) {   
    
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        if ($request->reminder_date) {

            $reminder_date = Carbon::createFromFormat('d M Y', $request->input('reminder_date'))->format('Y-m-d');
        }
        if ($request->deadline) {

            $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        }
        if ($request->hearing_date) {

            $hearing_date = Carbon::createFromFormat('d M Y', $request->input('hearing_date'))->format('Y-m-d');
        }

        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newHearing = new HearingDateDetails();
        $existedHearing = HearingDateDetails::where('lead_id', $existedLeaedTask->lead_id)->where('stage_id',27)->latest()->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $formattedCreatedDate = (!empty($existedLeaedTaskDetails->status_date))
            ? $existedLeaedTaskDetails->status_date
            : $existedLeaedTask->created_at->format('d M Y');
        $reminderDate =   $existedLeaedTaskDetails->reminderDate ?? "N/A";
        $comment = "";
        if ($request->show_case_hearing == 2) {
            $comment = "Appeal To IPAB";
        } else if ($request->show_case_hearing == 4) {
            $comment = "Settelment B/W Applicant and Opponent";
        } else if ($request->show_case_hearing == 1) {
            $comment = "Accepeted";
        } else if ($request->show_case_hearing == 3) {
            $comment = "Rejected";
        }
       
        
        $rule = [
            'show_case_hearing' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'nullable'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {

            if ($request->show_case_hearing == 2) {
                $rule = [
                    'show_case_hearing' => 'required',
                    'verified' => 'required',
                    'reminder_date' => 'required',
                    'hearing_date' => 'required',
                    
                ];
                $validator = Validator::make($request->all(), $rule);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                $logStatus = !empty($existedLeaedTaskDetails->comment) ? $existedLeaedTaskDetails->comment : 'Pending';
                $existedLeaedTaskDetails->status = 2;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $existedLeaedTaskDetails->comment = $comment;
                $existedLeaedTaskDetails->reminderDate = $reminder_date;


                if ($request->hasFile('attachment')) {
                    $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0755, true);
                    }
                    $filePaths = [];
                    $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                    foreach ($request->file('attachment') as $file) {
                        if ($file->isValid()) {
                            $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                            $file->move($folderPath, $fileName);
                            $filePaths[] = $fileName;
                        }
                    }
                    $updatedAttachments = array_merge($existingAttachments, $filePaths);
                    $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                }
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($existedLeaedTaskDetails->save()) {
                    $newHearing->task_id = $existedLeaedTask->id;
                    $newHearing->lead_id = $existedLeaedTask->lead_id;
                    $newHearing->stage_id = $existedHearing->stage_id;

                    $reason = 'Settelment B/W Applicant and Opponent';
                    
                    $newHearing->reason = $reason;
                    $newHearing->hearing_date = $hearing_date;
                    if ($existedHearing) {
                        $newHearing->count = $existedHearing->count ? $existedHearing->count + 1 : 0;
                    } else {
                        $newHearing->count = 1;
                    }
                    if ($newHearing->save()) {
                        $newNotification->user_id = $existedLeaedTask->user_id;
                        $newNotification->lead_id = $existedLeaedTask->lead_id;
                        $newNotification->task_id = $existedLeaedTask->id;
                        $newNotification->title = 'Task On Hold ';
                        $newNotification->description = 'Opposition hearing is on hold';
                        if ($newNotification->save()) {
                            $newLog =  new LeadLog();
                            $newLog->user_id = $existedLeaedTask->user_id;
                            $newLog->lead_id = $existedLeaedTask->lead_id;
                            $newLog->task_id = $existedLeaedTask->id;
                            $newLog->assign_by = Auth::id();
                            $newLog->remark = $comment . " " . "(On Hold)";
                            $oldValue = [
                                'status' => $logStatus,
                                'Assigned On' =>  $formattedCreatedDate ?? "N/A",
                                'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                
                            ];
                            $newValue = [
                                'status' => $comment,
                                'Verified On' => $request->verified,
                                'Assigned To' =>  $existedLeaedTask->user->name,
                                'Reminder Date' => $reminder_date,
                                'Hearing Date' => $request->hearing_date,
                                'Hearing Count' => $newHearing->count

                            ];
                            $newLog->old_value = json_encode($oldValue);
                            $newLog->new_value = json_encode($newValue);
                            $newLog->description = "Opposition hearing marked as" . $comment;

                            if ($newLog->save()) {
                                return redirect()->route('task.index')->with('success', 'Opposition hearing status is Updated');
                            } else {
                                return redirect()->back()->error('message', " there is something wrong during log generate ");
                            }
                        }
                    } else {
                        return redirect()->back()->back()->with('error', 'there is something wrong while updating new hearing');
                    }
                } else {
                    return redirect()->back()->error('message', " there is something wrong during hold the task ");
                }
            } else if ($request->show_case_hearing == 3) {
                $rule = [
                    'show_case_hearing' => 'required',
                    'verified' => 'required',
                    'rejected_reason' => 'required'
                ];
                $validator = Validator::make($request->all(), $rule);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $logStatus = !empty($existedLeaedTaskDetails->comment) ? $existedLeaedTaskDetails->comment : 'Pending';
                $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $existedLeaedTask->service_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->service_stage_id = $request->stage_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->assign_by = Auth::id();
                $newLeadtask->task_title = $newTaskTitle->title;
                if($newLeadtask->save()){
                $existedLeaedTaskDetails->status = 4;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $existedLeaedTaskDetails->reminderDate = null;
                $reason = "";
                if ($request->rejected_reason == 1) {
                    $reason  = 'Refused';
                } else if ($request->rejected_reason == 2) {
                    $reason  = 'Abandon';
                } else if ($request->rejected_reason == 3) {
                    $reason  = 'Withdraw';
                }
                $existedLeaedTaskDetails->comment = $comment . "( $reason)";
                if ($request->hasFile('attachment')) {
                    $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0755, true);
                    }
                    $filePaths = [];
                    $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                    foreach ($request->file('attachment') as $file) {
                        if ($file->isValid()) {
                            $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                            $file->move($folderPath, $fileName);
                            $filePaths[] = $fileName;
                        }
                    }
                    $updatedAttachments = array_merge($existingAttachments, $filePaths);
                    $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                }
                $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                $existedLeaedTask->save();
                if ($existedLeaedTaskDetails->save()) {
                    $newLeadTaskDeatails->task_id = $newLeadtask->id;
                    $newLeadTaskDeatails->dead_line = $deadlineDate;
                    $newLeadTaskDeatails->status = 0;
                    if($newLeadTaskDeatails->save()){
                        $newNotification->user_id = $existedLeaedTask->user_id;
                        $newNotification->lead_id = $existedLeaedTask->lead_id;
                        $newNotification->task_id = $existedLeaedTask->id;
                        $newNotification->title = 'Task rejected ';
                        $newNotification->description = 'Opposition hearing  is rejected';
                        if ($newNotification->save()) {
                            $newLog =  new LeadLog();
                            $newLog->user_id = $existedLeaedTask->user_id;
                            $newLog->lead_id = $existedLeaedTask->lead_id;
                            $newLog->task_id = $existedLeaedTask->id;
                            $newLog->assign_by = Auth::id();
                            $newLog->remark = "Rejected" . "($reason)";
                            $oldValue = [
                                'status' => $logStatus,
                                'Assigned On' =>  $formattedCreatedDate ?? "N/A",
                                'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                'Reminder Date' => $reminderDate ?? "N/A",
                                'Hearing Date' => $existedHearing->hearing_date ?? "N/A",
                                'Hearing Count' => $existedHearing->count ?? "N/A"
                            ];
                            $newValue = [
                                'status' => $comment . "($reason)",
                                'Verified On' => $request->verified,
                                'Assigned To' =>  $existedLeaedTask->user->name,
                                'Reminder Date' => "N/A",
                                'Hearing Date' => "N/A",
                                'Hearing Count' => "N/A"
                            ];
                            $newLog->old_value = json_encode($oldValue);
                            $newLog->new_value = json_encode($newValue);
                            $newLog->description = "Opposition hearing marked as rejected " . "($reason)";
                            if ($newLog->save()) {
                                return redirect()->route('task.index')->with('success', 'Opposition hearing status is Updated');
                            } else {
                                return redirect()->back()->error('message', " there is something wrong during log generate ");
                            }
                        } else {
                            return redirect()->back()->error('message', " there is something wrong during notification generate ");
                        }
                    }else{
                        return redirect()->back()->error('message' , "there is something wrong while updating new lead task details");
                    }
                } else {
                    return redirect()->back()->with('error', 'there is something wrong while updating exist lead task details');
                }
            }else{
                return redirect()->back()->with('error', 'there is something wrong while updating exist lead task');

            }
            } else if ($request->show_case_hearing == 1) {
                $logStatus = !empty($existedLeaedTaskDetails->comment) ? $existedLeaedTaskDetails->comment : 'Pending';
                
                $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $existedLeaedTask->service_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->service_stage_id = $request->stage_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->assign_by = Auth::id();
                $newLeadtask->task_title = $newTaskTitle->title;
                if ($newLeadtask->save()) {
                    $existedLeaedTaskDetails->status = 1;
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->comment = $comment;

                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $filePaths = [];
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                    $existedLeaedTask->save();
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
                                $LeadLog->remark = "Accepted";
                                $oldValue = [
                                    'status' => $logStatus,
                                    'Assigned On' =>  $formattedCreatedDate ?? "N/A",
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                    'Reminder Date' => $reminderDate ?? "N/A",
                                    'Hearing Date' => $existedHearing->hearing_date ?? "N/A",
                                    'Hearing Count' => $existedHearing->count ?? "N/A"
                                ];
                                $newValue = [
                                    'status' => "Completed" . "($comment)",
                                    'Verified On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                    'Reminder Date' => "N/A",
                                    'Hearing Date' => "N/A",
                                    'Hearing Count' => "N/A"
                                ];
                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);
                                $LeadLog->description = " Show case hearing  marked as complete ";
                                if ($LeadLog->save()) {
                                    $newassignlog = new leadLog();
                                    $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                    $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                    $newassignlog->task_id = $newLeadtask->id;
                                    $newassignlog->assign_by = Auth::id();
                                    $newassignlog->remark = "Assign";

                                    $newassignlog->description =  "Lead assigned for next task";
                                    if ($newassignlog->save()) {
                                        $id = $newLeadtask->id;
                                        return redirect()->route('task.index')->with('success', 'Opposition hearing status is Updated');
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
                }else{

                    return redirect()->back()->with('error', 'there is something wrong during update  new task');
                }
            } else if($request->show_case_hearing == 4) {
                $logStatus = !empty($existedLeaedTaskDetails->comment) ? $existedLeaedTaskDetails->comment : 'Pending';
                
                $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $existedLeaedTask->service_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->service_stage_id = $request->stage_id;
                $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
                $newLeadtask->assign_by = Auth::id();
                $newLeadtask->task_title = $newTaskTitle->title;
                if ($newLeadtask->save()) {
                    $existedLeaedTaskDetails->status = 1;
                    $existedLeaedTaskDetails->status_date = $verifiedDate;
                    $existedLeaedTaskDetails->comment = $comment;

                    if ($request->hasFile('attachment')) {
                        $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $filePaths = [];
                        $existingAttachments = json_decode($existedLeaedTaskDetails->attachment, true) ?? [];
                        foreach ($request->file('attachment') as $file) {
                            if ($file->isValid()) {
                                $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                                $file->move($folderPath, $fileName);
                                $filePaths[] = $fileName;
                            }
                        }
                        $updatedAttachments = array_merge($existingAttachments, $filePaths);
                        $existedLeaedTaskDetails->attachment = json_encode($updatedAttachments);
                    }
                    $existedLeaedTask->task_description = $existedLeaedTask->task_description . ' ' . $request->description;
                    $existedLeaedTask->save();
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
                                $LeadLog->remark = "Settelment B/W Applicant and Opponent";
                                $oldValue = [
                                    'status' => $logStatus,
                                    'Assigned On' =>  $formattedCreatedDate ?? "N/A",
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                    'Reminder Date' => $reminderDate ?? "N/A",
                                    'Hearing Date' => $existedHearing->hearing_date ?? "N/A",
                                    'Hearing Count' => $existedHearing->count ?? "N/A"
                                ];
                                $newValue = [
                                    'status' => "Completed" . "($comment)",
                                    'Verified On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                    'Reminder Date' => "N/A",
                                    'Hearing Date' => "N/A",
                                    'Hearing Count' => "N/A"
                                ];
                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);
                                $LeadLog->description = " Hearing  marked as complete ";
                                if ($LeadLog->save()) {
                                    $newassignlog = new leadLog();
                                    $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                    $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                    $newassignlog->task_id = $newLeadtask->id;
                                    $newassignlog->assign_by = Auth::id();
                                    $newassignlog->remark = "Assign";

                                    $newassignlog->description =  "Lead assigned for next task";
                                    if ($newassignlog->save()) {
                                        $id = $newLeadtask->id;
                                        return redirect()->route('task.index')->with('success', 'Opposition hearing status is Updated');
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
                }else{

                    return redirect()->back()->with('error', 'there is something wrong during update  new task');
                }
            }
        } else {
            return redirect()->back()->with('error', 'no task found');
        }
    }
    public function agreementSubmission($id){
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }
        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->where('archive', 1)->get();
        $applicationNumber = LeadTask::where('lead_id', $taskDetails->lead_id)
        ->orderBy('id', 'asc')
        ->first();
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.signed_agreement', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'applicationNumber'));
    }

    public function agreementSubmissionStatus(Request $request , $id){
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::with('services', 'lead', 'userAssignBy')->where('id', $id)->first();
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $userName = Auth::user()->name;
        $newTaskTitle = ServiceStages::find($request->stage_id);
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');


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
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
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
                            $LeadLog->remark = 'Written Submission';
                            $oldValue = [
                                'status' => 'Pending',
                                'Assigned On' => $formattedCreatedDate,
                                'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                            ];
                            $newValue = [
                                'status' => 'Completed',
                                'Written Submission On' => $request->verified,
                                'Assigned To' =>  $existedLeaedTask->user->name,
                            ];
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);

                            $LeadLog->description = " Written submission  marked as complete";
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = "Assign";
                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'Written submission completed');
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

    public function tradeMark($id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
        ->where('id', $id)
        ->first();
    $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->where('archive', 1)->get();
    $stageId = $taskDetails->service_stage_id;
    $getStage = ServiceStages::where('service_id', 1)->where('id', '>', $stageId)->first();
    $onHideSatge = ServiceStages::where('service_id', 1)->where('id', '=', 35)->first();
    $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.tradeMark.tardemark_status', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'onHideSatge'));
    }

    public function trademarkStatus(Request $request , $id){
        //  dd($request->all());
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::find($id);
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');
        $rule = [
            'trademark_status' => 'required',
            'verified' => 'required',
            'assignUser' => 'required',
            'deadline' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
            $newLeadtask->lead_id = $existedLeaedTask->lead_id;
            $newLeadtask->service_id = $existedLeaedTask->service_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->service_stage_id = $request->stage_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->assign_by = Auth::id();
            $newLeadtask->task_title = $newTaskTitle->title;
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
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
                            
                            
                            if($request->trademark_status == 0 ){
                                $LeadLog->remark = 'Register';
                                $oldValue = [
                                    'status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'status' => 'Completed as register',
                                    'Verified On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                ];
                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);
                                $LeadLog->description = " Trademark status marked as register";
                            }else if($request->trademark_status == 1 ){
                                $LeadLog->remark = 'refused';
                                $oldValue = [
                                    'status' => 'Pending',
                                    'Assigned On' => $formattedCreatedDate,
                                    'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                                ];
                                $newValue = [
                                    'status' => 'Completed as refused',
                                    'Verified On' => $request->verified,
                                    'Assigned To' =>  $existedLeaedTask->user->name,
                                ];
                                $LeadLog->old_value = json_encode($oldValue);
                                $LeadLog->new_value = json_encode($newValue);
                                $LeadLog->description = " Trademark status marked as refused";
                            }
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = "Assign";

                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'Trademark status completed');
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
            } else {
                return redirect()->back()->with('error', 'there is something wrong during update  new task');
            }
        } else {
            return redirect()->back()->with('erroe', "no task found");
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
            return redirect()->route('task.DocumentDraft', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 6) {
            return redirect()->route('task.clientApproval', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 7) {
            return redirect()->route('task.draftApplication', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 8) {
            return redirect()->route('task.formalityCheck', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 9) {
            return redirect()->route('task.initialExamination', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 10) {
            return redirect()->route('task.replyAdded', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 11) {
            return redirect()->route('task.govtPortalReply', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 12) {
            return redirect()->route('task.examinationInform', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 13) {
            return redirect()->route('task.examinationPayment', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 14) {
            return redirect()->route('task.hearingDate', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 15) {
            return redirect()->route('task.showCaseHearing', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 16) {
            return redirect()->route('task.markAsPublish', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 17) {
            return redirect()->route('task.markPublishOpposition', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 18) {
            return redirect()->route('task.informClientAfterPublish', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 19) {
            return redirect()->route('task.oppositionPayment', ['id' => $id]);
        }else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 20) {
            return redirect()->route('task.oppositionNoticeDate', ['id' => $id]);
        }else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 21) {
            return redirect()->route('task.oppositionCounterStatement', ['id' => $id]);
        }else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 22) {
            return redirect()->route('task.noticeSent', ['id' => $id]);
        }else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 23) {
            return redirect()->route('task.opponentEvidenceSubmission', ['id' => $id]);
        } else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 24) {
            return redirect()->route('task.applicantEvidenceSubmission', ['id' => $id]);
        }else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 25) {
            return redirect()->route('task.oppositionResubmissionEvedince', ['id' => $id]);
        }else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 26) {
            return redirect()->route('task.nonCompliance', ['id' => $id]);
        }else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 27) {
            return redirect()->route('task.oppositionHearingDate', ['id' => $id]);
        }else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 28) {
            return redirect()->route('task.clientApprovalOnHearing', ['id' => $id]);
        }else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 29) {
            return redirect()->route('task.clientInformForHearingCharge', ['id' => $id]);
        }else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 30) {
            return redirect()->route('task.hearingChargeUpdate', ['id' => $id]);
        }else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 31) {
            return redirect()->route('task.hearing', ['id' => $id]);
        }else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 32) {
            return redirect()->route('task.agreementSubmission', ['id' => $id]);
        }else if ($id == $taskDetails->id && $serviceId == 1 && $stageId == 33) {
            return redirect()->route('task.tradeMark', ['id' => $id]);
        }
        
        
        // For Patent...............
        else if ($taskDetails && $serviceId == 2 && $stageId == 35) {
            return redirect()->route('task.patentSendQuotation', ['id' => $id]);
        } else if ($taskDetails && $serviceId == 2 && $stageId == 36) {
            return redirect()->route('task.patentPaymentVerification', ['id' => $id]);
        } else if ($taskDetails && $serviceId == 2 && $stageId == 36) {
            return redirect()->route('task.patentPriorArt', ['id' => $id]);
        } else if ($taskDetails && $serviceId == 2 && $stageId == 37) {
            return redirect()->route('task.patentDocumentation', ['id' => $id]);
        } else if ($taskDetails && $serviceId == 2 && $stageId == 38) {
            return redirect()->route('task.patentDraft', ['id' => $id]);
        } else if ($taskDetails && $serviceId == 2 && $stageId == 39) {
            return redirect()->route('task.patentClientApproval', ['id' => $id]);
        } else if ($taskDetails && $serviceId == 2 && $stageId == 40) {
            return redirect()->route('task.clentApprovalOnPatent', ['id' => $id]);
        }else if ($taskDetails && $serviceId == 2 && $stageId == 41) {
            return redirect()->route('task.patentfilingProccess', ['id' => $id]);
        }
    }

    public function patentSendQuotation(Request $request, $id)
    {
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
        $taskList = LeadTask::with(['user', 'leadTaskDetails', 'payment' => function ($q) {
            $q->limit(1)->latest();
        }])->where('id', $taskId)->first();
        $firstPaymentId = Payment::where('task_id', $taskId)->OrderBy('id', 'ASC')->first();
        
        $serviceStage = ServiceStages::where('id', '>', $taskList->service_stage_id)->where('service_id', 2)->first();
        $userList = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        $getStage = ServiceStages::where('service_id', 2)->where('id', '>', $taskList->service_stage_id)->first();
        $currentUser = User::find($taskList->user_id);
        $header_title_name = "Payment Verification";
        return view('tasks/patent/payment-verification', compact('header_title_name', 'firstPaymentId', 'taskId', 'getStage', 'taskList', 'serviceStage', 'userList', 'currentUser'));
    }

    public function patentPriorArt(Request $request, $id)
    { 
      
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
        return view('tasks/patent/prior-art', compact('header_title_name', 'taskId', 'users', 'taskDetails', 'getStage'));
    }

    public function patentSubmitPriorArt(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            // dd($request);
            $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
            $dead_line = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
            $existedLeaedTask = LeadTask::with(['lead', 'services', 'subService', 'serviceSatge'])->where('id', $id)->first();

            $newLeadtask = new LeadTask();
            $newLeadTaskDeatails  = new LeadTaskDetail();
            $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
            $stageId = (int) $request->stage_id;
            $serviceId = $existedLeaedTask->services->id;
            $subServiceId = $existedLeaedTask->subService->id;
            $existedStageName = ServiceStages::where('id', $existedLeaedTask->service_stage_id)->first();
            $assignedStageName = ServiceStages::where('id', $stageId)->first();
            $userName = Auth::user()->name;

            if ($request->relevantpriorart == 1) {
                $existedLeaedTaskDetails->update(['status' => 1, 'status_date' => $verifiedDate]);
                $newLeadtask->user_id = $request->assignUser;
                $newLeadtask->lead_id = $existedLeaedTask->lead_id;
                $newLeadtask->service_id = $serviceId;
                $newLeadtask->subservice_id = $subServiceId;
                $newLeadtask->service_stage_id = $stageId;
                $newLeadtask->assign_by = Auth::id();
                $newLeadtask->task_title = $assignedStageName->description;
                $existedLeaedTask->update(['task_title' => $existedStageName->title, 'task_description' => $request->description]);

                if ($newLeadtask->save()) {
                    $newLeadTaskDeatails->task_id = $newLeadtask->id;
                    $newLeadTaskDeatails->status = 0;
                    $newLeadTaskDeatails->dead_line = $dead_line;
                    if ($newLeadTaskDeatails->save()) {
                        $userAssign =  $request->assignUser ?? $existedLeaedTask->user_id;
                        $notification = new LeadNotification();
                        $notification->user_id =  $userAssign;
                        $notification->lead_id = $existedLeaedTask->lead_id;
                        $notification->task_id = $newLeadtask->id;
                        $notification->title = "Task Assigned";
                        $notification->description =  $userName . ' assigned you ' . $assignedStageName->title . ' task';
                        $notification->status = 0;
                        if ($notification->save()) {
                            $LeadLog = new LeadLog();
                            $LeadLog->user_id =  $existedLeaedTask->user_id;
                            $LeadLog->lead_id =  $existedLeaedTask->lead_id;
                            $LeadLog->task_id =  $existedLeaedTask->id;
                            $LeadLog->assign_by = Auth::id();
                            $LeadLog->description = "prior art marked as completed";
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    return redirect()->route('task.index')->with('success', 'Prior art marked as completed!');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong while updating log');
                            }
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong while updating notification');
                        }
                    }
                } else {
                    return redirect()->route('task.index')->with('error', 'Some error is occure while updating lead task.');
                }
            } else {
                $existedLeaedTaskDetails->update(['status' => 1, 'status_date' => $verifiedDate]);
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
                $existedLeaedTaskDetails->update(['attachment' => $imgData]);
            }
            return redirect()->route('task.index')->with('success', 'Task is completed!');
        }
    }

    public function patentDocumentation(Request $request, $id)
    {
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 2)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.patent.documentation', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }

    public function patentDraft(Request $request, $id)
    {  
       
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }

        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();

        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 2)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks/patent/draft-application', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }

    public function patentSubmitDraft(Request $request, $id)
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
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
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
                    $newLeadTaskDeatails->comment = $request->document;
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
                                    return redirect()->back()->with('error', 'there is something wrong while updating log');
                                }
                            } else {
                                return redirect()->back()->with('error', 'there is something wrong while updating log');
                            }
                        } else {
                            return redirect()->back()->with('error', 'there is something wrong while updating notification');
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

    public function patentclientapproval(Request $request, $id)
    {   
        
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }
        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();


        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 2)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks/patent/client-approval', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage'));
    }
 
    public function patentSubmitClientApproval(Request $request, $id)
    {
       
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::with('services', 'lead', 'userAssignBy')->where('id', $id)->first();
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $newTaskStageId = $existedLeaedTask->service_stage_id + 1;
        $newTaskTitle = ServiceStages::find($newTaskStageId);
        $userName = Auth::user()->name;
        $service = $existedLeaedTask->services->serviceName;
        $clientName = $existedLeaedTask->lead->client_name;
        $clientEmail = $existedLeaedTask->lead->email;
        $clientMobile = $existedLeaedTask->lead->mobile_number;
        $companyName = $existedLeaedTask->lead->company_name;
        $remark = $request->description ?? null;
        $subject = "Document Sent to the client for approval";
        $leadID = $existedLeaedTask->lead->id;
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');
        $additionalReason = "";
        if($request->statusDrafting == 1){
            $additionalReason = "Provisional Specification";
        }else if($request->statusDrafting == 2){
            $additionalReason = "Complete Specification";
        }
        
        $rule = [
            
            'verified' => 'required',
            
            'deadline' => 'required'
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($id) {
            $newLeadtask->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
            $newLeadtask->lead_id = $existedLeaedTask->lead_id;
            $newLeadtask->service_id = $existedLeaedTask->service_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->service_stage_id = $request->stage_id;
            $newLeadtask->subservice_id = $existedLeaedTask->subservice_id;
            $newLeadtask->assign_by = Auth::id();
            $newLeadtask->task_title = $newTaskTitle->title;
            if ($newLeadtask->save()) {
                $existedLeaedTaskDetails->status = 1;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $existedLeaedTaskDetails->comment = $additionalReason;

                $filePaths = [];
                if ($request->hasFile('attachment')) {
                    $folderPath = public_path('uploads/leads/' . $existedLeaedTask->lead_id);
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0755, true);
                    }
                    foreach ($request->file('attachment') as $file) {
                        if ($file->isValid()) {
                            $fileName = rand(100000, 999999) . '.' . $file->getClientOriginalExtension();
                            $file->move($folderPath, $fileName);
                            $filePaths[] = $fileName;
                        }
                    }
                    $existedLeaedTaskDetails->attachment = json_encode($filePaths);
                }
               
                // CommanDraftSend::dispatch( 
                //     $subject,
                //     $service,
                //     $leadID,
                //     $companyName,
                //     !empty($filePaths) ? $filePaths : null,
                //     $clientName,
                //     $clientEmail,
                //     $clientMobile,
                //     $trademarkName,
                //     $assignApplicationNumber
                // );
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
                            $LeadLog->remark = 'Document Draft as'.' '.($additionalReason);
                            $oldValue = [
                                'status' => 'Pending',
                                'Assigned On' => $formattedCreatedDate,
                                'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                            ];
                            $newValue = [
                                'status' => 'Completed',
                                'Draft Sent On' => $request->verified,
                                'Assigned To' =>  $existedLeaedTask->user->name,
                            ];
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);

                            $LeadLog->description = "Document Drafted successfully";
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = 'Assign';

                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {

                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'document Draft status completed');
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
            } else {
                return redirect()->back()->with('error', 'there is something wrong during update  new task');
            }
        } else {
            return redirect()->back()->with('error', "no task found");
        }
    }
    
    public function clentApprovalOnPatent($id){
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }
        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();


            $previousTask = LeadTask::where('id', '<', $taskDetails->id)
            ->orderBy('id', 'desc')  
            ->first();
            
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 2)->where('id', '>', $stageId)->first();
        $leadTaskdetials = LeadTaskDetail::find($id);
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.patent.patent_client_approval', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'previousTask'));
    }
    public function clentApprovalOnPatentStatus(Request $request , $id){
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::with('services', 'lead', 'userAssignBy')->where('id', $id)->first();
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $getPerviousAction = LeadTaskDetail::where('task_id', $request->previous_task_id)->first();
        $comment = $getPerviousAction->comment;
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $userName = Auth::user()->name;
        $newTaskTitle = ServiceStages::find($request->stage_id);
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');
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
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
            if ($newLeadtask->save()) {
                $existedLeaedTaskDetails->status =  1;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $existedLeaedTaskDetails->comment = $comment;

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
                            $LeadLog->remark = 'Client Approval';
                            $oldValue = [
                                'status' => 'Pending',
                                'Assigned On' => $formattedCreatedDate,
                                'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                            ];
                            $newValue = [
                                'status' => 'Completed',
                                'Approved On' => $request->verified,
                                'Assigned To' =>  $existedLeaedTask->user->name,
                            ];
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);

                            $LeadLog->description = " Client document verification marked as" .' '. $comment;
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = "Assign";
                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'Client approval completed');
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

    public function patentfilingProccess($id){
        if ($id) {
            $notifyData = LeadNotification::where('task_id', $id)->update(['status' => 1]);
        }
        $taskDetails = LeadTask::with(['user', 'lead', 'services', 'subService', 'leadTaskDetails', 'serviceSatge'])
            ->where('id', $id)
            ->first();
            $leadTaskdetials = LeadTaskDetail::find($id);
            $previousTask = LeadTaskDetail::where('id', '<', $taskDetails->id)
            ->orderBy('id', 'desc')  
            ->first();
       
        $users = User::where('role', '>', '4')->where('archive', 1)->where('status', 1)->get();
        $stageId = $taskDetails->service_stage_id;
        $getStage = ServiceStages::where('service_id', 2)->where('id', '>', $stageId)->first();
        if($previousTask->comment != 'Provisional Specification'){
            $getStage = ServiceStages::where('service_id', 2)->where('id', '>', $stageId)->skip(1)->first();

        }
        $header_title_name = $taskDetails->serviceSatge->title;
        return view('tasks.patent.patent_filing_process', compact('id', 'header_title_name', 'taskDetails', 'leadTaskdetials', 'users', 'getStage', 'previousTask'));
    }
    
    public function patentfilingProccessStatus(Request $request , $id ){
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        $deadlineDate = Carbon::createFromFormat('d M Y', $request->input('deadline'))->format('Y-m-d');
        $existedLeaedTask = LeadTask::with('services', 'lead', 'userAssignBy')->where('id', $id)->first();
        $existedLeaedTaskDetails = LeadTaskDetail::where('task_id', $id)->first();
        $getPerviousAction = LeadTaskDetail::where('task_id', $request->previous_task_id)->first();
        $comment = $getPerviousAction->comment;
        $newLeadtask = new LeadTask();
        $newLeadTaskDeatails  = new LeadTaskDetail();
        $newNotification = new LeadNotification();
        $userName = Auth::user()->name;
        $newTaskTitle = ServiceStages::find($request->stage_id);
        $formattedCreatedDate = $existedLeaedTask->created_at->format('d M Y');
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
            $existedLeaedTask->task_description = $request->description;
            $existedLeaedTask->save();
            if ($newLeadtask->save()) {
                $existedLeaedTaskDetails->status =  1;
                $existedLeaedTaskDetails->status_date = $verifiedDate;
                $existedLeaedTaskDetails->comment = 'Filling proccess completed';
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
                        //  $serviceDetails = new ServiceDetail();
                        //  $serviceDetails->lead_id = $newLeadtask->lead_id;
                        //  $serviceDetails->task_id = $newLeadtask->task_id;
                        //  $serviceDetails->service_id = $newLeadtask->service_id;
                        //  $serviceDetails->application_number = $request->application_number;
                        //  $serviceDetails->filing_date = $request->$verifiedDate;
                        //  $serviceDetails->applicant_name = $request->applicant_name;
                        //  $serviceDetails->inventor_name = $request->inventor_name;
                        //  $serviceDetails->title_of_invention = $request->title_of_invention;
                        //  $serviceDetails->category_of_invention = $request->category_invention;
                        //  $serviceDetails->type_of_application = $request->type_of_application;
                        //  $serviceDetails->status = 0;
                        //  $serviceDetails->client_status = 0;
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
                            $LeadLog->remark = 'Filing Proccess'.''.($comment);
                            $oldValue = [
                                'status' => 'Pending',
                                'Assigned On' => $formattedCreatedDate,
                                'Assigned By' =>  $existedLeaedTask->userAssignBy->name,
                            ];
                            $newValue = [
                                'status' => 'Completed',
                                'Filed On' => $request->verified,
                                'Assigned To' =>  $existedLeaedTask->user->name,
                            ];
                            $LeadLog->old_value = json_encode($oldValue);
                            $LeadLog->new_value = json_encode($newValue);
                            $LeadLog->description = "Filing Proccess marked as" .' '. $comment;
                            if ($LeadLog->save()) {
                                $newassignlog = new leadLog();
                                $newassignlog->user_id = $request->assignUser ?? $existedLeaedTask->user_id;
                                $newassignlog->lead_id = $existedLeaedTask->lead_id;
                                $newassignlog->task_id = $newLeadtask->id;
                                $newassignlog->assign_by = Auth::id();
                                $newassignlog->remark = "Assign";
                                $newassignlog->description =  "Lead assigned for next task";
                                if ($newassignlog->save()) {
                                    $id = $newLeadtask->id;
                                    return redirect()->route('task.index')->with('success', 'Filing proccess completed');
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
    public function  patten(Request $request, $id)
    {
        dd($id);
    }
    public function holdtask(Request $request)
    {
        $verifiedDate = Carbon::createFromFormat('d M Y', $request->input('verified'))->format('Y-m-d');
        if($request->followUp){

            $followUpDate = Carbon::createFromFormat('d M Y', $request->input('followUp'))->format('Y-m-d');
        }
        $rule = [
            'verified' => 'required',
            'comment' => 'max:25',
        ];
        $validtor =  Validator::make($request->all(), $rule);
        if ($validtor->fails()) {
            return redirect()->back()->withErrors($validtor)->withInput();
        }
        $taskDetails = LeadTaskDetail::where('task_id', $request->task_hidden_id)->first();
        $formattedCreatedDate = $taskDetails->leadTask->created_at->format('d M Y');
        
        $user_id =  $taskDetails->leadTask->user_id;
        $taskDetails->status = 5;
        $taskDetails->status_date = $verifiedDate;
        $taskDetails->reminderDate = $followUpDate ?? null;
        $taskDetails->comment = $request->comment;
        if ($taskDetails->save()) { 

            $LeadLog = new LeadLog();
            $LeadLog->user_id = $user_id;
            $LeadLog->task_id = $taskDetails->task_id;
            $LeadLog->lead_id = $taskDetails->leadTask->lead_id;
            $LeadLog->assign_by = Auth::id();
            $LeadLog->remark = "On Hold";
            $oldValue = [
                'Status' => 'Pending',
                'Assigned On' => $formattedCreatedDate,
                'Assigned By' => $taskDetails->leadTask->userAssignBy->name,
            ];
            $newValue = [
                'Status' => 'On Hold',
                'Verified On' => $verifiedDate ?? null,
                'Assigned To' => $taskDetails->leadTask->user->name,
            ];
            $LeadLog->old_value = json_encode($oldValue);
            $LeadLog->new_value = json_encode($newValue);
            $LeadLog->description = $request->description;
            $LeadLog->save();
            if($LeadLog->save()){

                return redirect()->route('task.index')->with('success', 'Task on hold');
            }
        } else {
            return redirect()->back()->error('message', " No task found ");
        }
    }
    public function unHoldTask(Request $request){
      $task_id = $request->id;
      $unHold = LeadTaskDetail::where('task_id', $task_id)->update(['status' => 0]);
      if($unHold){
        return response()->json([
            'message' => 'Task status updated to successfully.',
            'status' => 200
        ]);
      }else{
        return response()->json([
            'message' => 'Task status updated to successfully.',
            'status' => 201
        ]);
      }
    }

    public function rejecttask(Request $request)
    {
        $leadData = LeadTaskDetail::where(['task_id' => $request->taskId])->first();
        $leadData->status = 4;
        if ($leadData->save()) {
            echo "1";
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
 