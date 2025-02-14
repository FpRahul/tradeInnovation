<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\CategoryOption;
use App\Models\UserExperience;
use App\Models\Role;
use App\View\Components\LogActivity;
use App\Models\Log;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendClientWelcomeEmail;
use App\Jobs\SentForgetPasswordmail;
use App\Models\Partner;
use Carbon\Carbon;


class UsersController extends Controller
{
    public function login(Request $request){
        if ($request->isMethod('post')) {
            $clientIP = \Request::ip();
            $userAgent = \Request::header('User-Agent');
            $operatingSystem = getOperatingSystem($userAgent);
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $logActivity[] = [
                    'user_id' => auth()->user()->id,
                    'title' => 'Login',
                    'description' => auth()->user()->name . 'has logged into the portal',
                    'created_at' => date('Y-m-d H:i:s'),
                    'ip_address' => $clientIP,
                    'operating_system' => $operatingSystem
                ];                
                $logActivity = new LogActivity($logActivity);
                $logActivity->log();
                return redirect()->route('dashboard')->withSuccess('You have successfully logged in!');
            }
            return redirect()->back()->with('error', 'The provided credentials do not match our records.');
        }
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return view('users/login');
        }
    }

    public function forgetPassword(Request $request){   
        $clientIP = \Request::ip();        
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        if ($request->isMethod('POST')) {
            $credentials = $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            $updatePass = User::where('email', $request->email)->first();
            if ($updatePass) {
                $newPass = substr(str_shuffle('9abcdefghijklmnopq045678rstuvwxyzABCDEFG123HIJKLMNOPQRSTUVWXYZ'), 0, 8);
                $newPass = '12345678';
                $hashPasswrd = Hash::make($newPass);
                $updatePass->password = $hashPasswrd;
                $updatePass->save();
                SentForgetPasswordmail::dispatch($updatePass , $newPass);
                if ($updatePass) {
                    $logActivity[] = [
                        'user_id' => $updatePass->id,
                        'title' => 'Forgot Password',
                        'description' => $updatePass->name . ' requested for new password',
                        'created_at' => date('Y-m-d H:i:s'),
                        'ip_address' => $clientIP,
                        'operating_system' => $operatingSystem

                    ];
                    $logActivity = new LogActivity($logActivity);
                    $logActivity->log();
                    return redirect()->route('login')->withSuccess('An email has been sent to your register address ');
                } else {
                    return back()->with('error', 'Some error is occur.');
                }
            } else {
                return back()->with('error', 'Email does not exist!');
            }
        }
        return view('users/forgetPassword');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->withSuccess('You have been logged out successfully!');
    }
 
    public function index(Request $request){
        $employeeData = User::with('userdetail')->where('role', '>', '3')->where('archive', 1);
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
       
        if ($searchKey) {
            $employeeData->where(function ($query) use ($searchKey) {
                $query->where('name', 'LIKE', $searchKey . '%')
                    ->orWhere('mobile', 'LIKE', $searchKey . '%');
            });
        }

        $employeeData = $employeeData->latest()->paginate(env("PAGINATION_COUNT"));

        if (empty($requestType)) {
            $header_title_name = 'User';
            return view('users.listing', compact('employeeData', 'header_title_name', 'searchKey'));
        } else {
            $trData = view('users/employee-page-search-data', compact('employeeData', 'searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
        }
    }

    public function addUser(Request $request, $id = null){
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        $roleData = Role::where('id', '>', 3)->get();
        if ($id > 0) {
            $newUser = User::find($id);
            $newUserDetails = UserDetail::where('userId', $id)->first();
        
            if (empty($newUserDetails)) {
                $newUserDetails = new UserDetail();
                $pImage = "required|mimes:jpeg,png,jpg,pdf|max:2048";
                $aImage = "required|mimes:jpeg,png,jpg,pdf|max:2048";
                $dImage = "required|mimes:jpeg,png,jpg,pdf|max:2048";
            } else {
                $pImage = !empty($newUserDetails->uploadPan) ? 'nullable|mimes:jpeg,png,jpg,pdf|max:2048' : 'required|mimes:jpeg,png,jpg,pdf|max:2048';
                $aImage = !empty($newUserDetails->uploadAadhar) ? 'nullable|mimes:jpeg,png,jpg,pdf|max:2048' : 'required|mimes:jpeg,png,jpg,pdf|max:2048';
                $dImage = !empty($newUserDetails->uploadDrivingLicence) ? 'nullable|mimes:jpeg,png,jpg,pdf|max:2048' : 'required|mimes:jpeg,png,jpg,pdf|max:2048';
            }        
            $newUserExperiences = UserExperience::where('userId', $id)->get();
            $email = "required|email";
            $hashedPassword = $newUser->password;
            $successMessage = "User is successfully updated!";
            $moduleName = "Update";
            $logAct = 'updated';
            $mail = false;
            $type = 'User';  // Ensure type is defined
            $uniqueUserId = $newUser->uni_user_id;
        } else {
            $newUser = new User();
            $newUserDetails = new UserDetail();
            $pImage = "required|mimes:jpeg,png,jpg,pdf|max:2048";
            $aImage = "required|mimes:jpeg,png,jpg,pdf|max:2048";
            $dImage = "required|mimes:jpeg,png,jpg,pdf|max:2048";
            $newUserExperiences = [];
        
            $email = "required|email|unique:users,email";
            $randomNumber = substr(str_shuffle('9abcdefghijklmnopq045678rstuvwxyzABCDEFG123HIJKLMNOPQRSTUVWXYZ'), 0, 8);
            $hashedPassword = Hash::make($randomNumber);
            $successMessage = "User is successfully inserted!";
            $moduleName = "Add";
            $logAct = 'added';
            $mail = true;
            $type = 'User';

            //UniqueId
            $uniqueUserId = $this->generateUniqueUserCode('I','>', 4);
        }
        if($request->isMethod('POST')){    
            $customMessages = [
                'uploadPan.max' => 'File size exceeds 2MB limit.',
                'uploadAadhar.max' => 'File size exceeds 2MB limit.',
                'uploadDrivingLicence.max' => 'File size exceeds 2MB limit.',
                'mimes' => 'The :attribute must be a file of type: jpeg, png, jpg, pdf.'
            ];
            $credentials = $request->validate([                
                'email' => $email,           
                'uploadPan' => $pImage,      
                'uploadAadhar' => $aImage,   
                'uploadDrivingLicence' => $dImage, 
            ], $customMessages);
            $newUser->name = $request->name;
            $newUser->role = $request->role;
            $newUser->email = $request->email;
            $newUser->mobile = $request->mobileNumber;
            $newUser->altNumber = $request->altMobile;
            $newUser->password = $hashedPassword;
            $newUser->uni_user_id=$uniqueUserId;
            if ($newUser->save()) {                  
                $newUserDetails->userId = $newUser->id;
                $newUserDetails->fatherHusbandName = $request->fatherHusbandName;
                $newUserDetails->qualification = $request->qualification;
                $newUserDetails->skills = $request->skill;
                $newUserDetails->keyResponsibilityArea = $request->keyResponsibilityArea;
                $newUserDetails->keyPerformanceIndicator = $request->keyPerformanceIndicator;
                $newUserDetails->emergencyContactDetails = $request->emergencyContactDetails;

                $newUserDetails->currentAddress = $request->currentAddress;
                $newUserDetails->curr_city = $request->curr_city;
                $newUserDetails->curr_state = $request->curr_state;
                $newUserDetails->curr_zip = $request->curr_zip;

                $newUserDetails->permanentAddress = $request->permanentAddress;
                $newUserDetails->perma_city = $request->perma_city;
                $newUserDetails->perma_state = $request->perma_state;
                $newUserDetails->perma_zip = $request->perma_zip;

                if ($request->hasFile('employeePhoto')) {
                    $image_name = $request->employeePhoto;
                    $imageName = rand(100000, 999999) . '.' . $image_name->getClientOriginalExtension();
                    $image_name->move(public_path('uploads/users/'.$newUser->id), $imageName);
                    $newUserDetails->uploadPhotograph = $imageName;
                }
                if ($request->hasFile('uploadPan')) {
                    $image_name = $request->uploadPan;
                    $imageName = rand(100000, 999999) . '.' . $image_name->getClientOriginalExtension();
                    $image_name->move(public_path('uploads/users/'.$newUser->id), $imageName);
                    $newUserDetails->uploadPan = $imageName;
                }
                if ($request->hasFile('uploadAadhar')) {
                    $image_name = $request->uploadAadhar;
                    $imageName = rand(100000, 999999) . '.' . $image_name->getClientOriginalExtension();
                    $image_name->move(public_path('uploads/users/'.$newUser->id), $imageName);
                    $newUserDetails->uploadAadhar = $imageName;
                }
                if ($request->hasFile('uploadDrivingLicence')) {
                    $image_name = $request->uploadDrivingLicence;
                    $imageName = rand(100000, 999999) . '.' . $image_name->getClientOriginalExtension();
                    $image_name->move(public_path('uploads/users/'.$newUser->id), $imageName);
                    $newUserDetails->uploadDrivingLicence = $imageName;
                }
                foreach ($request->experince as $exKey => $exVal) {
                    if ($exVal['experience_id'] > 0) {
                        $newUserExperiences = UserExperience::find($exVal['experience_id']);
                    } else {
                        $newUserExperiences = new UserExperience();
                    }
                    $newUserExperiences->userId = $newUser->id;
                    $newUserExperiences->employerName = $exVal['employerName'];
                    $newUserExperiences->startDate = date('Y-m-d',strtotime($exVal['startDate']));
                    $newUserExperiences->endDate = date('Y-m-d',strtotime($exVal['endDate']));
                    $newUserExperiences->save();
                }

                if ($newUserDetails->save()) {
                    $logActivity[] = [
                        'user_id' => auth()->user()->id,
                        'title' => 'Add/Edit '.Role::find($request->role)->name,
                        'description' => auth()->user()->name . ' has ' . $logAct . ' user ' . $newUser->name . ' #' . $uniqueUserId . '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'ip_address' => $clientIP,
                        'operating_system' => $operatingSystem
                    ];
                    $logActivity = new LogActivity($logActivity);
                    $logActivity->log();
                    if($mail == true){
                        SendClientWelcomeEmail::dispatch($newUser,$randomNumber,$filePath = null,$type);
                    }
                    return redirect()->route('users.listing')->withSuccess($successMessage);
                } else {
                    return back()->with('error', 'Some error is occur.');
                }
            } else {
                return back()->with('error', 'Some error is occur.');
            }
        }
        $header_title_name = 'User';
        return view('users.add-user', compact('roleData', 'newUser', 'newUserDetails', 'newUserExperiences', 'header_title_name', 'moduleName'));
    }

    public function deleteUser($id = null){
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        $employeeData = User::where('id', $id)->first();
        $employeeData->archive = 0;
        if ($employeeData->save()) {
            $logActivity[] = [
                'user_id' => auth()->user()->id,
                'title' => 'Archive User',
                'description' => auth()->user()->name . ' has deleted user ' . $employeeData->name . ' #' . $employeeData->id . '',
                'created_at' => date('Y-m-d H:i:s'),
                'ip_address' => $clientIP,
                'operating_system' => $operatingSystem
            ];
            $logActivity = new LogActivity($logActivity);
            $logActivity->log();
            return redirect()->back()->with('success', 'Your data is successfully deleted');
        }
    }

    public function deleteRepeaterUser(Request $request){
        $experienceData = UserExperience::where('id', $request->id);
        if ($experienceData->delete()) {
            echo "1";
        } else {
            echo "0";
        }
    }

    public function clients(Request $request){
        $clientData = User::with('userdetail')->where('role', 2)->where('archive', 1);
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if ($searchKey) {
            $clientData->where(function ($query) use ($searchKey) {
                $query->where('name', 'LIKE', $searchKey . '%')->orWhere('mobile', 'LIKE', $searchKey . '%');
            });
        }
        $clientData = $clientData->latest()->paginate(env("PAGINATION_COUNT"));

        if (empty($requestType)) {
            $header_title_name = 'User';
            return view('users/client-listing', compact('clientData', 'header_title_name', 'searchKey'));
        } else {
            $trData = view('users/client-page-search-data', compact('clientData', 'searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
        }
    }

    public function addClient(Request $request, $id = null){  
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        $incorporationDataList = CategoryOption::where('status', 1)->where('type', 2)->get();
        $referDataList = CategoryOption::where('status', 1)->where('type', 3)->get();
        $partnerDataList = Partner::where('status',1)->get();
        if ($id > 0) {
            $newClient = User::find($id);
            $newClientDetails = UserDetail::where('userId', $id)->first();
            $hashedPassword = $newClient->password;
            $email = "required|email";
            $moduleName = "Update";
            $logAct = 'updated';
            $mail = false;
            $uniqueUserId = $newClient->uni_user_id;
        } else {
            $newClient = new User();
            $newClientDetails = new UserDetail();
            $randomNumber = substr(str_shuffle('9abcdefghijklmnopq045678rstuvwxyzABCDEFG123HIJKLMNOPQRSTUVWXYZ'), 0, 8);
            $hashedPassword = Hash::make($randomNumber);
            $email = "required|email|unique:users,email";
            $moduleName = "Add";
            $logAct = 'added';
            $mail = true;
            $type = 'Client';
            $uniqueUserId = $this->generateUniqueUserCode('C','=', 2);
        }
        if ($request->isMethod('POST')) {
            $credentials = $request->validate([
                'email' => $email,
            ]);
            $newClient->name = $request->name;
            $newClient->role = $request->role;
            $newClient->email = $request->email;
            $newClient->altEmail = $request->alternateEmail;
            $newClient->mobile = $request->number;
            $newClient->altNumber = $request->alternatePhone;
            $newClient->companyName = $request->companyname;
            $newClient->password = $hashedPassword;
            $newClient->uni_user_id=$uniqueUserId;

            if ($newClient->save()) {               
                $newClientDetails->userId = $newClient->id;
                $newClientDetails->incorporationType = $request->incorporationtype;
                $newClientDetails->registered = $request->registered;
                $newClientDetails->msmem = $request->msmem;
                $newClientDetails->referralPartner = $request->referralPartner;
                $newClientDetails->source_type_id = $request->sourcetypenamelist;
                $newClientDetails->partner_id = implode(',',$request->partnerNamelist);

                $newClientDetails->currentAddress = $request->currentAddress;
                $newClientDetails->curr_city = $request->curr_city;
                $newClientDetails->curr_state = $request->curr_state;
                $newClientDetails->curr_zip = $request->curr_zip;

                $newClientDetails->permanentAddress = $request->permanentAddress;
                $newClientDetails->perma_city = $request->perma_city;
                $newClientDetails->perma_state = $request->perma_state;
                $newClientDetails->perma_zip = $request->perma_zip;

                if ($newClientDetails->save()) {
                    $logActivity[] = [
                        'user_id' => auth()->user()->id,
                        'title' => 'Add/Edit Client',
                        'description' => auth()->user()->name . ' has ' . $logAct . ' client ' . $newClient->name . ' #' . $uniqueUserId . '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'ip_address' => $clientIP,
                        'operating_system' => $operatingSystem
                    ];
                    $logActivity = new LogActivity($logActivity);
                    $logActivity->log();
                    if($mail == true){
                        SendClientWelcomeEmail::dispatch($newClient,$randomNumber, $filePath = null,$type);
                    }
                    return redirect()->route('client.listing')->withSuccess('Client is successfully inserted!');
                } else {
                    return back()->with('error', 'Some error is occur.');
                }
            } else {
                return back()->with('error', 'Some error is occur.');
            }
        }
        $header_title_name = 'User';
        return view('users.add-client', compact('newClient', 'newClientDetails', 'header_title_name', 'moduleName', 'referDataList','incorporationDataList','partnerDataList'));
    }
 
    public function associates(Request $request){
        $associateData = User::with('userdetail')->where('role', 3)->where('archive', 1);
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if ($searchKey) {
            $associateData->where(function ($query) use ($searchKey) {
                $query->where('name', 'LIKE', $searchKey . '%')->orWhere('mobile', 'LIKE', $searchKey . '%');
            });
        }

        $associateData = $associateData->latest()->paginate(env("PAGINATION_COUNT"));
        if (empty($requestType)) {
            $header_title_name = 'User';
            return view('users/associate-listing', compact('associateData', 'header_title_name', 'searchKey'));
        } else {
            $trData = view('users/associate-page-search-data', compact('associateData', 'searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
        }
    }

    public function addAssociate(Request $request, $id = null){
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        $professionDataList = CategoryOption::where('status', 1)->where('type', 1)->get();

        if ($id > 0) {
            $newAssociate = User::find($id);
            $newAssociateDetails = UserDetail::where('userId', $id)->first();
            $hashedPassword = $newAssociate->password;
            $email = "required|email";
            $moduleName = "Update ";
            $logAct = 'updated';
            $mail = false;
            $uniqueUserId = $newAssociate->uni_user_id;
        } else {
            $newAssociate = new User();
            $newAssociateDetails = new UserDetail();
            $randomNumber = substr(str_shuffle('9abcdefghijklmnopq045678rstuvwxyzABCDEFG123HIJKLMNOPQRSTUVWXYZ'), 0, 8);
            $hashedPassword = Hash::make($randomNumber);
            $email = "required|email|unique:users,email";
            $moduleName = "Add ";
            $logAct = 'added';
            $mail = true;
            $type = 'Associate';
            $uniqueUserId = $this->generateUniqueUserCode('A','=', 3);
        }
        if ($request->isMethod('POST')) {
            $credentials = $request->validate([
                'email' =>  $email,
            ]);
            $newAssociate->name = $request->name;
            $newAssociate->Profession = $request->profession;
            $newAssociate->role = $request->role;
            $newAssociate->email = $request->email;
            $newAssociate->altEmail = $request->alternateEmail;
            $newAssociate->mobile = $request->number;
            $newAssociate->altNumber = $request->alternatePhone;
            $newAssociate->companyName = $request->firmName;
            $newAssociate->password = $hashedPassword;
            $newAssociate->uni_user_id=$uniqueUserId;

            if ($newAssociate->save()) { 
                $newAssociateDetails->userId = $newAssociate->id;
                $newAssociateDetails->currentAddress = $request->currentAddress;
                $newAssociateDetails->curr_city = $request->curr_city;
                $newAssociateDetails->curr_state = $request->curr_state;
                $newAssociateDetails->curr_zip = $request->curr_zip;

                $newAssociateDetails->permanentAddress = $request->permanentAddress;
                $newAssociateDetails->perma_city = $request->perma_city;
                $newAssociateDetails->perma_state = $request->perma_state;
                $newAssociateDetails->perma_zip = $request->perma_zip; 
                if ($newAssociateDetails->save()) {
                    $logActivity[] = [
                        'user_id' => auth()->user()->id,
                        'title' => 'Add/Edit Associate',
                        'description' => auth()->user()->name . ' has ' . $logAct . ' the Associate ' . $newAssociate->name . ' #' . $newAssociate->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'ip_address' => $clientIP,
                        'operating_system' => $operatingSystem
                    ];
                    $logActivity = new LogActivity($logActivity);
                    $logActivity->log();
                        if($mail == true){
                            SendClientWelcomeEmail::dispatch($newAssociate,$randomNumber,$filePath = null,$type);
                        }
                   
                    return redirect()->route('associate.listing')->withSuccess('Associate is successfully inserted!');
                } else {
                    return back()->with('error', 'Some error is occur.');
                }          
                
            } else {
                return back()->with('error', 'Some error is occur.');
            }
        }
        $header_title_name = 'User';
        return view('users/add-associate', compact('newAssociate','newAssociateDetails', 'header_title_name', 'moduleName', 'professionDataList'));
    }

    public function userStatus(Request $request){  
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        if ($request->isMethod('GET')) {
            if ($request->val) {
                $status = 0;
            } else {
                $status = 1;
            }
            $existedUser = User::where('id', $request->id)->first();
            $existedUser->status = $status;
            if ($existedUser->save()) {
                $logActivity[] = [
                    'user_id' => auth()->user()->id,
                    'title' => 'Update User Status',
                    'description' => auth()->user()->name . ' has changed the status of ' . $existedUser->name . ' #' . $existedUser->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'ip_address' => $clientIP,
                    'operating_system' => $operatingSystem
                ];
                $logActivity = new LogActivity($logActivity);
                $logActivity->log();
                return redirect()->back()->with('success', 'Your status is successfully updated!');
            } else {
                return back()->withError('Some error is occur');
            }
        }
    }
    public function clientStatus(Request $request){  
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        if ($request->isMethod('GET')) {
            if ($request->val) {
                $status = 0;
            } else {
                $status = 1;
            }
            $existedUser = User::where('id', $request->id)->first();
            $existedUser->status = $status;
            if ($existedUser->save()) {
                $logActivity[] = [
                    'user_id' => auth()->user()->id,
                    'title' => 'Update User Status',
                    'description' => auth()->user()->name . ' has changed the status of ' . $existedUser->name . ' #' . $existedUser->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'ip_address' => $clientIP,
                    'operating_system' => $operatingSystem
                ];
                $logActivity = new LogActivity($logActivity);
                $logActivity->log();
                return redirect()->back()->with('success', 'Your status is successfully updated!');
            } else {
                return back()->withError('Some error is occur');
            }
        }
    }
    public function associateStatus(Request $request){  
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        if ($request->isMethod('GET')) {
            if ($request->val) {
                $status = 0;
            } else {
                $status = 1;
            }
            $existedUser = User::where('id', $request->id)->first();
            $existedUser->status = $status;
            if ($existedUser->save()) {
                $logActivity[] = [
                    'user_id' => auth()->user()->id,
                    'title' => 'Update User Status',
                    'description' => auth()->user()->name . ' has changed the status of ' . $existedUser->name . ' #' . $existedUser->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'ip_address' => $clientIP,
                    'operating_system' => $operatingSystem
                ];
                $logActivity = new LogActivity($logActivity);
                $logActivity->log();
                return redirect()->back()->with('success', 'Your status is successfully updated!');
            } else {
                return back()->withError('Some error is occur');
            }
        }
    }
    public function myprofile(Request $request, $id = null){  
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $logAct = 'updated';
        $operatingSystem = getOperatingSystem($userAgent);
        $user = Auth::user();
        $userData = User::where('id', $user->id)->first();
        $newUserDetails = UserDetail::where('userId', $user->id)->first();
        if ($request->isMethod('POST')) {
            $credentials = $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'profilePic' => 'mimes:jpeg,png,jpg|max:2048'
            ]);
            if (!empty($request->password)) {
                $password =  Hash::make($request->password);
            } else {
                $password = $user->password;
            }
            if ($request->hasFile('profilePic')) {
                $image_name = $request->profilePic;
                $imageName = rand(100000, 999999) . '.' . $image_name->getClientOriginalExtension();
                $image_name->move(public_path('uploads/users/'.$user->id), $imageName);
                $newUserDetails->uploadPhotograph = $imageName;
                $newUserDetails->save();
            }
            
            $userData->name = $request->name;
            $userData->email = $request->email;
            $userData->password = $password;
            if ($userData->save()) {
                $logActivity[] = [
                    'user_id' => auth()->user()->id,
                    'title' => 'Add/Edit Profile',
                    'description' => auth()->user()->name . ' has ' . $logAct . ' the user ' . $userData->name . ' #' . $userData->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'ip_address' => $clientIP,
                    'operating_system' => $operatingSystem
                ];
                $logActivity = new LogActivity($logActivity);
                $logActivity->log();
                return redirect()->route('dashboard')->withSuccess('Profile is successfully updated!');
            }else{
                return back()->with('error', 'Some error is occur.');
            }
        }
        $header_title_name = 'My Profile';
        return view('users/myprofile', compact('newUserDetails', 'userData', 'header_title_name'));
    }

    public function userProfessions(Request $request){
        $categoryData = CategoryOption::where('type', 1);
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if($searchKey){
            $categoryData->where(function($q) use($searchKey){
                $q->where('name', 'LIKE', "%{$searchKey}%");
            });
        }
        $categoryData = $categoryData->paginate(env("PAGINATION_COUNT"));

        if(empty($requestType)){
            $header_title_name = 'User';
            return view('users.professions', compact('header_title_name', 'categoryData','searchKey'));
        }else{
            $trData = view('users/profession-page-search-data', compact('categoryData', 'searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
        }
        // profession-page-search-data.blade.php
        
        
    }

    public function addProfessions(Request $request){
        $clientIP = \Request::ip();        
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        if ($request->profession_id > 0) {
            $newCategory = CategoryOption::where('id', $request->profession_id)->first();
            $logAct = 'updated';
        } else {
            $newCategory = new CategoryOption;
            $logAct = 'Added';
        }
        if ($request->isMethod('POST')) {
            $authUser = Auth::user();
            $newCategory->authId = $authUser->id;
            $newCategory->type = $request->type;
            $newCategory->name = $request->name;

            if ($newCategory->save()) {
                $logActivity[] = [
                    'user_id' => auth()->user()->id,
                    'title' => 'Add/Edit Professions',
                    'description' => auth()->user()->name . ' has ' . $logAct . ' the Professions ' . $newCategory->name . ' #' . $newCategory->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'ip_address' => $clientIP,
                    'operating_system' => $operatingSystem
                ];
                $logActivity = new LogActivity($logActivity);
                $logActivity->log();
                if ($request->profession_id > 0) {
                    return redirect()->back()->with('success', 'Your data is successfully updated');
                } else {
                    return redirect()->back()->with('success', 'Your data is successfully inserted');
                }
            } else {
                return back()->with('error', 'some error is occurring');
            }
        }
        return view('users.professions', compact('newCategory'));
    }

    public function professionStatus(Request $request, $id = null){ 
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        $logAct = 'changed the profession status';
        $categoryData = CategoryOption::find($id);
        if (!$categoryData) {
            return redirect()->back()->with('error', 'Category not found!');
        }
        $categoryData->status = $categoryData->status == 1 ? 0 : 1;

        if ($categoryData->save()) {
            $logActivity[] = [
                'user_id' => auth()->user()->id,
                'title' => 'Update Category Status',
                'description' => auth()->user()->name . ' has ' . $logAct . ' of the category ' . $categoryData->name . ' #' . $categoryData->id,
                'created_at' => now(),
                'ip_address' => $clientIP,
                'operating_system' => $operatingSystem
            ];
            $logActivity = new LogActivity($logActivity);
            $logActivity->log();
            return redirect()->back()->with('success', 'Status successfully updated!');
        } else {
            return redirect()->back()->with('error', 'An error occurred while updating the status!');
        }
    }


    public function incorporationStatus(Request $request, $id = null){
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        $logAct = 'changed the incorporation status';
        $categoryData = CategoryOption::find($id);
        if (!$categoryData) {
            return redirect()->back()->with('error', 'Category not found!');
        }
        $categoryData->status = $categoryData->status == 1 ? 0 : 1;
        
        if ($categoryData->save()) {
            $logActivity[] = [
                'user_id' => auth()->user()->id,
                'title' => 'Update Category Status',
                'description' => auth()->user()->name . ' has ' . $logAct . ' of the category ' . $categoryData->name . ' (' . $categoryData->id . ')',
                'created_at' => date('Y-m-d H:i:s'),
                'ip_address' => $clientIP,
                'operating_system' => $operatingSystem
            ];
            $logActivity = new LogActivity($logActivity);
            $logActivity->log();
            return redirect()->back()->with('success', 'Status is successfully updated!');
        } else {
            return redirect()->back()->with('error', 'Some error is occur!');
        }
    }
   
    public function referralStatus(Request $request, $id = null){
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        $categoryData = CategoryOption::find($id);
        if (!$categoryData) {
            return redirect()->back()->with('error', 'Category not found!');
        }
        $categoryData->status = $categoryData->status == 1 ? 0 : 1;
        
        if ($categoryData->save()) {
            $logActivity[] = [
                'user_id' => auth()->user()->id,
                'title' => 'Update Referral Status',
                'description' => auth()->user()->name . ' has updated the status of ' . $categoryData->name,
                'created_at' => date('Y-m-d H:i:s'),
                'ip_address' => $clientIP,
                'operating_system' => $operatingSystem
            ];
            $logActivity = new LogActivity($logActivity);
            $logActivity->log();
            return redirect()->back()->with('success', 'Status is successfully updated!');
        } else {
            return redirect()->back()->with('error', 'Some error is occur!');
        }
    }

    public function categoryDelete($id = null){ 
        $clientIP = \Request::ip();        
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);    
        $categoryData = CategoryOption::find($id);
        if ($categoryData->delete()) {
            $logActivity[] = [
                'user_id' => auth()->user()->id,
                'title' => 'Archive User',
                'description' => auth()->user()->name . ' has deleted the user ' . $categoryData->name . ' #' . $categoryData->id,
                'created_at' => date('Y-m-d H:i:s'),
                'ip_address' => $clientIP,
                'operating_system' => $operatingSystem
            ];
            $logActivity = new LogActivity($logActivity);
            $logActivity->log();
            return redirect()->back()->with('success', 'Your data is successfully deleted!');
        } else {
            return redirect()->back()->with('error', 'Some error is occur!');
        }
    }

    public function userIncorporation(Request $request){
        // incorporation-page-search-data.blade.php
        $categoryData = CategoryOption::where('type', 2);
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if($searchKey){
            $categoryData->where(function($q) use($searchKey){
                $q->where('name', 'LIKE', "%{$searchKey}%");
            });
        }
        
        $categoryData = $categoryData->paginate(env("PAGINATION_COUNT"));
        if(empty($requestType)){
            $header_title_name = 'User';
            return view('users.incorporation', compact('header_title_name', 'categoryData','searchKey'));
        }else{
            $trData = view('users/incorporation-page-search-data', compact('categoryData', 'searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
        }
    }

    public function addIncorporation(Request $request){
        $clientIP = \Request::ip();        
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        if ($request->incorporation_id > 0) {
            $newCategory = CategoryOption::where('id', $request->incorporation_id)->first();
            $logAct = "Updated";
        } else {
            $newCategory = new CategoryOption;
            $logAct = "Added";

        }
        if ($request->isMethod('POST')) {
            $authUser = Auth::user();
            $newCategory->authId = $authUser->id;
            $newCategory->type = $request->type;
            $newCategory->name = $request->name;
            
            if ($newCategory->save()) {
                $logActivity[] = [
                    'user_id' => auth()->user()->id,
                    'title' => 'Add/Edit Incorporation',
                    'description' => auth()->user()->name . 'has' .$logAct . $newCategory->name . ' #' . $newCategory->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'ip_address' => $clientIP,
                    'operating_system' => $operatingSystem
                ];
                $logActivity = new LogActivity($logActivity);
                $logActivity->log();
                if ($request->incorporation_id > 0) {
                    return redirect()->back()->with('success', 'Your data is successfully updated');
                } else {
                    return redirect()->back()->with('success', 'Your data is successfully inserted');
                }
            } else {
                return back()->with('error', 'some error is occurring');
            }
        }
        return view('users.incorporation', compact('newCategory'));
    }

    public function userReferral(Request $request){
        $categoryData = CategoryOption::where('type', 3);
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if($searchKey){
            $categoryData->where(function($q) use($searchKey){
                $q->where('name', 'LIKE', "%{$searchKey}%");
            });
        }
        $categoryData = $categoryData->paginate(env("PAGINATION_COUNT"));
        if(empty($requestType)){
            $header_title_name = 'User';
            return view('users.referral', compact('header_title_name', 'categoryData','searchKey'));
        }else{
            $trData = view('users/referral-page-listing-data', compact('categoryData', 'searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
        }
       
    }

    public function addReferral(Request $request){
        $clientIP = \Request::ip();        
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        if ($request->referral_id > 0) {
            $logAct = "Updated";
            $newCategory = CategoryOption::where('id', $request->referral_id)->first();
        } else {
            $newCategory = new CategoryOption;
            $logAct = "Added";

        }
        if ($request->isMethod('POST')) {
            $authUser = Auth::user();
            $newCategory->authId = $authUser->id;
            $newCategory->type = $request->type;
            $newCategory->name = $request->name;
            if ($newCategory->save()) {
                $logActivity[] = [
                    'user_id' => auth()->user()->id,
                    'title' => 'Add/Edit Referral',
                    'description' => auth()->user()->name .' has '. $logAct .' the referral '. $newCategory->name . ' #' . $newCategory->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'ip_address' => $clientIP,
                    'operating_system' => $operatingSystem
                ];
                $logActivity = new LogActivity($logActivity);
                $logActivity->log();
                if ($request->referral_id > 0) {
                    return redirect()->back()->with('success', 'Your data is successfully updated');
                } else {
                    return redirect()->back()->with('success', 'Your data is successfully inserted');
                }
            } else {
                return back()->with('error', 'some error is occurring');
            }
        }
        return view('users.referral', compact('newCategory'));
    }

    public function panelLogs(Request $request){
        $header_title_name = 'System Logs';
        if(empty($request->input('auto'))){
            $activityTitles = Log::select('title')->distinct()->orderBy('title', 'asc')->pluck('title');
            $activityUsers = Log::select('user_id')->distinct()->orderBy('user_id', 'asc')->pluck('user_id');
        
            if(!empty($request->input('dateRange'))){
                $filterOptions['completeDate']= $request->dateRange ?? '';
                list($startDateStr, $endDateStr) = explode(' - ', $filterOptions['completeDate']);
                $startDate = Carbon::createFromFormat('d M Y', $startDateStr);
                $endDate = Carbon::createFromFormat('d M Y', $endDateStr);
                $startDateFormatted = $startDate->format('Y-m-d');
                $endDateFormatted = $endDate->format('Y-m-d');
            }
            $filterOptions['user_id'] = $request->user_id ?? '';
            $filterOptions['activity'] = $request->activity ?? '';
            $query = Log::with(['user' => function ($query) {
                $query->select('id','name', 'ip_address', 'operating_system');
            }])->orderBy('id', 'desc');
            if (!empty($startDateFormatted) && !empty($endDateFormatted)) {
                $query->whereDate('created_at', '>=', $startDateFormatted)->whereDate('created_at', '<=', $endDateFormatted);
            }
            if (!empty($filterOptions['user_id'])) {
                $query->where('user_id', $filterOptions['user_id']);
            }
            if (!empty($filterOptions['activity'])) {
                $query->where('title', 'like', '%' . $filterOptions['activity'] . '%');
            }
            $systemLogs = $query->paginate(env("PAGINATION_COUNT"))->appends($request->query());
            return view('users.logs', compact('header_title_name', 'systemLogs', 'activityTitles', 'activityUsers', 'filterOptions'))->with('isAutoId', false);;
        } else{
            $autoId = $request->input('auto');
            $query = Log::with(['user' => function ($query) {
                $query->select('id','name'); 
            }])->where('id' ,$autoId )->orderBy('id', 'desc')->first();
            return view('users.logs', compact('query' , 'header_title_name'))->with('isAutoId' , true);
        }
    }

    public function resetPassword(Request $request){
        $autoId = $request->input('auto');
        if($autoId){
            $getLogs = Log::with('user')->find($autoId);
            return view('users.logs', compact('getLogs'));
        }
    }

    public function generateUniqueUserCode($type,$symb,$role){
        $lastUser = User::where('role',$symb,$role)->latest()->first();
        if ($lastUser && $lastUser->uni_user_id) {
            $lastNumber = (int) substr($lastUser->uni_user_id, 1);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        return $type . $newNumber;
    }

    public function userPartner(Request $request){
        $partnerList = Partner::where('id','>',0);
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if($searchKey){
            $partnerList->where(function($q) use($searchKey){
                $q->where('name', 'LIKE', "%{$searchKey}%");
            });
        }
        $partnerList = $partnerList->paginate(env("PAGINATION_COUNT"));
        if(empty($requestType)){
            $header_title_name = 'User';
            return view('users.partner', compact('header_title_name', 'partnerList','searchKey'));
        }else{
            $trData = view('users/partner-page-search-data', compact('partnerList', 'searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
        }
    }

    public function addPartner(Request $request){
        $clientIP = \Request::ip();        
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        if($request->partner_model_id > 0){
            $partnerData = Partner::find($request->partner_model_id);
            $logAct = "Updated";
          }else{
              $partnerData = new Partner();
              $logAct = "Added";
          }
        if($request->isMethod('POST')){            
            $partnerData->name = $request->name;
            if($partnerData->save()){
                $logActivity[] = [
                    'user_id' => auth()->user()->id,
                    'title' => 'Add/Edit Partner',
                    'description' => auth()->user()->name . ' has ' . $logAct . ' the user ' . $partnerData->name . ' #' . $partnerData->id,                    
                    'created_at' => date('Y-m-d H:i:s'),
                    'ip_address' => $clientIP,
                    'operating_system' => $operatingSystem
                ];
                $logActivity = new LogActivity($logActivity);
                $logActivity->log();
                if($request->partner_model_id > 0){
                    return redirect()->back()->with('success','Successfully Updated!');
                }else{
                    return redirect()->back()->with('success','Successfully Inserted!');
                }
                
            }else{
                return redirect()->back()->with('error','Some error is occur!');
            }
        }
        return view('users.partner', compact('partnerData'));
    }

    public function partnerStatus(Request $request , $id=null){
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        $logAct = 'changed the partner';
        $partnerData = Partner::find($id);
        if (!$partnerData) {
            return redirect()->back()->with('error', 'Partner not found!');
        }
        $partnerData->status = $partnerData->status == 1 ? 0 : 1;
        
        if ($partnerData->save()) {
            $logActivity[] = [
                'user_id' => auth()->user()->id,
                'title' => 'Update Partner Status',
                'description' => auth()->user()->name . ' has ' . $logAct .' status of '. $partnerData->name . ' #' . $partnerData->id ,
                'created_at' => date('Y-m-d H:i:s'),
                'ip_address' => $clientIP,
                'operating_system' => $operatingSystem
            ];
            $logActivity = new LogActivity($logActivity);
            $logActivity->log();
            return redirect()->back()->with('success', 'Status is successfully updated!');
        } else {
            return redirect()->back()->with('error', 'Some error is occur!');
        }
    }
}
