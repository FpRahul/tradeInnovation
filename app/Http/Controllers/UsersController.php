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
use Carbon\Carbon;


class UsersController extends Controller
{
    public function login(Request $request)
    {
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
                    'description' => auth()->user()->name . ' logged into portal',
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

    public function forgetPassword(Request $request)
    {   
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->withSuccess('You have been logged out successfully!');
    }
 
    public function index(Request $request)
    {
        $employeeData = User::with('userdetail')->where('role', '>', '3')->where('archive', 1);
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
       
        if ($searchKey) {
            $employeeData->where(function ($query) use ($searchKey) {
                $query->where('name', 'LIKE', $searchKey . '%')
                    ->orWhere('mobile', 'LIKE', $searchKey . '%');
            });
        }

        $employeeData = $employeeData->paginate(env("PAGINATION_COUNT"));

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

    public function addUser(Request $request, $id = null)
    {
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        $roleData = Role::where('id', '>', 3)->get();
        if ($id > 0) {
            $newUser = User::find($id);
            $newUserDetails = UserDetail::where('userId', $id)->first();
            $newUserExperiences = UserExperience::where('userId', $id)->get();
            $pImage = !empty($newUserDetails['uploadPan']) ? '' : 'required|mimes:jpeg,png,jpg,pdf|max:2048';
            $aImage = !empty($newUserDetails['uploadAadhar']) ? '' : 'required|mimes:jpeg,png,jpg,pdf|max:2048';
            $dImage = !empty($newUserDetails['uploadDrivingLicence']) ? '' : 'required|mimes:jpeg,png,jpg,pdf|max:2048';
            $email = "required|email";
            $hashedPassword = $newUser->password;
            $successMessage = "User is successfully updated!";
            $moduleName = "Update";
            $logAct = 'updated';
            $mail = false;
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
            if ($newUser->save()) {
                $newUserDetails->userId = $newUser->id;
                $newUserDetails->fatherHusbandName = $request->fatherHusbandName;
                $newUserDetails->qualification = $request->qualification;
                $newUserDetails->skills = $request->skill;
                $newUserDetails->keyResponsibilityArea = $request->keyResponsibilityArea;
                $newUserDetails->keyPerformanceIndicator = $request->keyPerformanceIndicator;
                $newUserDetails->emergencyContactDetails = $request->emergencyContactDetails;
                $newUserDetails->currentAddress = $request->currentAddress;
                $newUserDetails->permanentAddress = $request->permanentAddress;

                if ($request->hasFile('employeePhoto')) {
                    $image_name = $request->employeePhoto;
                    $imageName = rand(100000, 999999) . '.' . $image_name->getClientOriginalExtension();
                    $image_name->move(public_path('Image'), $imageName);
                    $newUserDetails->uploadPhotograph = $imageName;
                }
                if ($request->hasFile('uploadPan')) {
                    $image_name = $request->uploadPan;
                    $imageName = rand(100000, 999999) . '.' . $image_name->getClientOriginalExtension();
                    $image_name->move(public_path('Image'), $imageName);
                    $newUserDetails->uploadPan = $imageName;
                }
                if ($request->hasFile('uploadAadhar')) {
                    $image_name = $request->uploadAadhar;
                    $imageName = rand(100000, 999999) . '.' . $image_name->getClientOriginalExtension();
                    $image_name->move(public_path('Image'), $imageName);
                    $newUserDetails->uploadAadhar = $imageName;
                }
                if ($request->hasFile('uploadDrivingLicence')) {
                    $image_name = $request->uploadDrivingLicence;
                    $imageName = rand(100000, 999999) . '.' . $image_name->getClientOriginalExtension();
                    $image_name->move(public_path('Image'), $imageName);
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
                        'title' => 'Add/Edit User',
                        'description' => auth()->user()->name . ' has ' . $logAct . ' user ' . $newUser->name . ' (' . $newUser->id . ')',
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
    public function deleteUser($id = null)
    {
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        $employeeData = User::where('id', $id)->first();
        $employeeData->archive = 0;
        if ($employeeData->save()) {
            $logActivity[] = [
                'user_id' => auth()->user()->id,
                'title' => 'Archive User',
                'description' => auth()->user()->name . ' has deleted user ' . $employeeData->name . ' (' . $employeeData->id . ')',
                'created_at' => date('Y-m-d H:i:s'),
                'ip_address' => $clientIP,
                'operating_system' => $operatingSystem
            ];
            $logActivity = new LogActivity($logActivity);
            $logActivity->log();
            return redirect()->back()->with('success', 'Your data is successfully deleted');
        }
    }
    public function deleteRepeaterUser(Request $request)
    {

        $experienceData = UserExperience::where('id', $request->id);
        if ($experienceData->delete()) {
            echo "1";
        } else {
            echo "0";
        }
    }

    public function clients(Request $request)
    {
        $clientData = User::with('userdetail')->where('role', 2)->where('archive', 1);
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if ($searchKey) {
            $clientData->where(function ($query) use ($searchKey) {
                $query->where('name', 'LIKE', $searchKey . '%')->orWhere('mobile', 'LIKE', $searchKey . '%');
            });
        }
        $clientData = $clientData->paginate(env("PAGINATION_COUNT"));

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

    public function addClient(Request $request, $id = null)
    {  
        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        $incorporationDataList = CategoryOption::where('status', 1)->where('type', 2)->get();
        $referDataList = CategoryOption::where('status', 1)->where('type', 3)->get();
        if ($id > 0) {
            $newClient = User::find($id);
            $newClientDetails = UserDetail::where('userId', $id)->first();
            $hashedPassword = $newClient->password;
            $email = "required|email";
            $moduleName = "Update";
            $logAct = 'updated';
            $mail = false;
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
            $newClient->address = $request->address;
            $newClient->communicationAdress = $request->communi_address;
            $newClient->password = $hashedPassword;
            if ($newClient->save()) {
                $newClientDetails->userId = $newClient->id;
                $newClientDetails->incorporationType = $request->incorporationtype;
                $newClientDetails->registered = $request->registered;
                $newClientDetails->referralPartner = $request->referralPartner;
                if ($newClientDetails->save()) {
                    $logActivity[] = [
                        'user_id' => auth()->user()->id,
                        'title' => 'Add/Edit Client',
                        'description' => auth()->user()->name . ' has ' . $logAct . ' client ' . $newClient->name . ' (' . $newClient->id . ')',
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
        // dd($newClientDetails);
        return view('users.add-client', compact('newClient', 'newClientDetails', 'header_title_name', 'moduleName', 'referDataList','incorporationDataList'));
    }
 
    public function associates(Request $request)
    {
        $associateData = User::with('userdetail')->where('role', 3)->where('archive', 1);
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if ($searchKey) {
            $associateData->where(function ($query) use ($searchKey) {
                $query->where('name', 'LIKE', $searchKey . '%')->orWhere('mobile', 'LIKE', $searchKey . '%');
            });
        }

        $associateData = $associateData->paginate(env("PAGINATION_COUNT"));
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

    public function addAssociate(Request $request, $id = null)
    {   

        $clientIP = \Request::ip();
        $userAgent = \Request::header('User-Agent');
        $operatingSystem = getOperatingSystem($userAgent);
        $professionDataList = CategoryOption::where('status', 1)->where('type', 1)->get();

        if ($id > 0) {
            $newAssociate = User::find($id);
            $hashedPassword = $newAssociate->password;
            $email = "required|email";
            $moduleName = "Update ";
            $logAct = 'updated';
            $mail = false;
        } else {
            $newAssociate = new User();
            $randomNumber = substr(str_shuffle('9abcdefghijklmnopq045678rstuvwxyzABCDEFG123HIJKLMNOPQRSTUVWXYZ'), 0, 8);
            $hashedPassword = Hash::make($randomNumber);
            $email = "required|email|unique:users,email";
            $moduleName = "Add ";
            $logAct = 'added';
            $mail = true;
            $type = 'Associate';
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
            $newAssociate->address = $request->address;
            $newAssociate->password = $hashedPassword;
            if ($newAssociate->save()) {
                $logActivity[] = [
                    'user_id' => auth()->user()->id,
                    'title' => 'Add/Edit Associate',
                    'description' => auth()->user()->name . ' has ' . $logAct . ' Associate ' . $newAssociate->name . ' (' . $newAssociate->id . ')',
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
        }
        $header_title_name = 'User';
        return view('users/add-associate', compact('newAssociate', 'header_title_name', 'moduleName', 'professionDataList'));
    }

    public function userStatus(Request $request)
    {  
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
                    'description' => auth()->user()->name . ' has changed status of ' . $existedUser->name . ' (' . $existedUser->id . ')',
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

    public function myprofile(Request $request, $id = null)
    {  
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
                $image_name->move(public_path('Image'), $imageName);
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
                    'description' => auth()->user()->name . ' has ' . $logAct . ' user ' . $userData->name . ' (' . $userData->id . ')',
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

    public function userProfessions()
    {
        $categoryData = CategoryOption::where('type', 1)->paginate(env("PAGINATION_COUNT"));
        $header_title_name = 'User';
        return view('users.professions', compact('header_title_name', 'categoryData'));
    }

    public function addProfessions(Request $request)
    {  

        $clientIP = \Request::ip();
        
        $userAgent = \Request::header('User-Agent');
      

        $operatingSystem = getOperatingSystem($userAgent);
        if ($request->profession_id > 0) {
            $newCategory = CategoryOption::where('id', $request->profession_id)->first();
            $logAct = 'updated';
        } else {
            $newCategory = new CategoryOption;
            $logAct = 'Add';
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
                    'description' => auth()->user()->name . ' has ' . $logAct . ' Professions ' . $newCategory->name . ' (' . $newCategory->id . ')',
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

    public function categoryStatus(Request $request, $id = null)
    {    
        $clientIP = \Request::ip();
        
        $userAgent = \Request::header('User-Agent');
     

    $operatingSystem = getOperatingSystem($userAgent);
    $logAct = 'Status Change';
        $categoryData = CategoryOption::find($id);
        if ($request->val) {
            $categoryData->status = 0;
        } else {
            $categoryData->status = 1;
        }
        
        if ($categoryData->save()) {
            $logActivity[] = [
                'user_id' => auth()->user()->id,
                'title' => 'Update Category Status',
                'description' => auth()->user()->name . ' has ' . $logAct . ' category ' . $categoryData->name . ' (' . $categoryData->id . ')',
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

    public function categoryDelete($id = null)
    {   

        $clientIP = \Request::ip();
        
        $userAgent = \Request::header('User-Agent');
      

    $operatingSystem = getOperatingSystem($userAgent);
    
        $categoryData = CategoryOption::find($id);
        if ($categoryData->delete()) {
            $logActivity[] = [
                'user_id' => auth()->user()->id,
                'title' => 'Archive User',
                'description' => auth()->user()->name . ' has deleted user ' . $categoryData->name . ' (' . $categoryData->id . ')',
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

    public function userIncorporation()
    {
        $categoryData = CategoryOption::where('type', 2)->paginate(env("PAGINATION_COUNT"));
        $header_title_name = 'User';
        return view('users.incorporation', compact('header_title_name', 'categoryData'));
    }

    public function addIncorporation(Request $request)
    {    
        $clientIP = \Request::ip();
        
        $userAgent = \Request::header('User-Agent');
        

    $operatingSystem = getOperatingSystem($userAgent);
        if ($request->incorporation_id > 0) {
            $newCategory = CategoryOption::where('id', $request->incorporation_id)->first();
            $logAct = "Update";
        } else {
            $newCategory = new CategoryOption;
            $logAct = "Add";

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
                    'description' => auth()->user()->name . $logAct . $newCategory->name . ' (' . $newCategory->id . ')',
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

    public function userReferral()
    {
        $categoryData = CategoryOption::where('type', 3)->paginate(env("PAGINATION_COUNT"));
        $header_title_name = 'User';
        return view('users.referral', compact('header_title_name', 'categoryData'));
    }

    public function addReferral(Request $request)
    {  
        $clientIP = \Request::ip();
        
        $userAgent = \Request::header('User-Agent');
        

    $operatingSystem = getOperatingSystem($userAgent);
        if ($request->referral_id > 0) {
            $logAct = "Update";
            $newCategory = CategoryOption::where('id', $request->referral_id)->first();
        } else {
            $newCategory = new CategoryOption;
            $logAct = "Add";

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
                    'description' => auth()->user()->name .' has '. $logAct .' Referral '. $newCategory->name . ' (' . $newCategory->id . ')',
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

    public function panelLogs(Request $request)
    {   
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
                $query->select('id','name', 'ip_address', 'operating_system'); // Specify the columns you want from the `user` table
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
}
