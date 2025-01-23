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

class UsersController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
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
                    'created_at' => date('Y-m-d H:i:s')
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
        if ($request->isMethod('POST')) {
            $credentials = $request->validate([
                'email' => 'required|email',
            ]);
            $updatePass = User::where('email', $request->email)->first();
            if ($updatePass) {
                $randomNumber = random_int(100000, 999999);
                $randomNumber = '123456';
                $hashedPassword = Hash::make($randomNumber);
                $updatePass->password = $hashedPassword;
                if ($updatePass->save()) {
                    $logActivity[] = [
                        'user_id' => $updatePass->id,
                        'title' => 'Forgot Password',
                        'description' => $updatePass->name . ' requested for new password',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $logActivity = new LogActivity($logActivity);
                    $logActivity->log();
                    return redirect()->route('login')->withSuccess('Password is successfully updated! ');
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
        //  $serverIP = gethostbyname(gethostname());
        // dd($clientIP);
        $userAgent = \Request::header('User-Agent');
        function getOperatingSystem($userAgent)
        {
            $osArray = [
                'Windows' => 'Windows',
                'Mac' => 'Mac',
                'Linux' => 'Linux',
                'Android' => 'Android',
                'iPhone' => 'iPhone',
                'iPad' => 'iPad',
                'Unix' => 'Unix',
            ];

            foreach ($osArray as $key => $os) {
                if (stripos($userAgent, $key) !== false) {
                    return $os;
                }
            }

            return 'Unknown';
        }

        $operatingSystem = getOperatingSystem($userAgent);

        // dd($operatingSystem);
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
        } else {
            $newUser = new User();
            $newUserDetails = new UserDetail();
            $pImage = "required|mimes:jpeg,png,jpg,pdf|max:2048";
            $aImage = "required|mimes:jpeg,png,jpg,pdf|max:2048";
            $dImage = "required|mimes:jpeg,png,jpg,pdf|max:2048";
            $newUserExperiences = [];
            $email = "required|email|unique:users,email";
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);
            $successMessage = "User is successfully inserted!";
            $moduleName = "Add";
            $logAct = 'added';
        }
        if ($request->isMethod('POST')) {
            $credentials = $request->validate([
                'email' => $email,
                'uploadPan' => $pImage,
                'uploadAadhar' => $aImage,
                'uploadDrivingLicence' => $dImage,
            ]);
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
                    $newUserExperiences->startDate = $exVal['startDate'];
                    $newUserExperiences->endDate = $exVal['endDate'];
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
        $employeeData = User::where('id', $id)->first();
        $employeeData->archive = 0;
        if ($employeeData->save()) {
            $logActivity[] = [
                'user_id' => auth()->user()->id,
                'title' => 'Archive User',
                'description' => auth()->user()->name . ' has deleted user ' . $employeeData->name . ' (' . $employeeData->id . ')',
                'created_at' => date('Y-m-d H:i:s')
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
        $incorporationDataList = CategoryOption::where('status', 1)->where('type', 2)->get();
        $referDataList = CategoryOption::where('status', 1)->where('type', 3)->get();

        if ($id > 0) {
            $newClient = User::find($id);
            $newClientDetails = UserDetail::where('userId', $id)->first();
            $hashedPassword = $newClient->password;
            $email = "required|email";
            $moduleName = "Update ";
            $logAct = 'updated';
        } else {
            $newClient = new User();
            $newClientDetails = new UserDetail();
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);
            $email = "required|email|unique:users,email";
            $moduleName = "Add ";
            $logAct = 'added';
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
                        'description' => auth()->user()->name . ' has ' . $logAct . ' user ' . $newClient->name . ' (' . $newClient->id . ')',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $logActivity = new LogActivity($logActivity);
                    $logActivity->log();
                    SendClientWelcomeEmail::dispatch($newClient);
                    // dd("Job dispatched for client: {$newClient->name}"); 
                    return redirect()->route('client.listing')->withSuccess('Client is successfully inserted!');
                } else {
                    return back()->with('error', 'Some error is occur.');
                }
            } else {
                return back()->with('error', 'Some error is occur.');
            }
        }
        $header_title_name = 'User';
        return view('users.add-client', compact('newClient', 'newClientDetails', 'header_title_name', 'moduleName', 'incorporationDataList', 'referDataList'));
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
        $professionDataList = CategoryOption::where('status', 1)->where('type', 1)->get();

        if ($id > 0) {
            $newAssociate = User::find($id);
            $hashedPassword = $newAssociate->password;
            $email = "required|email";
            $moduleName = "Update ";
            $logAct = 'updated';
        } else {
            $newAssociate = new User();
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);
            $email = "required|email|unique:users,email";
            $moduleName = "Add ";
            $logAct = 'added';
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
                    'description' => auth()->user()->name . ' has ' . $logAct . ' user ' . $newAssociate->name . ' (' . $newAssociate->id . ')',
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $logActivity = new LogActivity($logActivity);
                $logActivity->log();
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
                    'created_at' => date('Y-m-d H:i:s')
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
                return redirect()->route('dashboard')->withSuccess('Profile is successfully updated!');
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
        if ($request->profession_id > 0) {
            $newCategory = CategoryOption::where('id', $request->profession_id)->first();
        } else {
            $newCategory = new CategoryOption;
        }
        if ($request->isMethod('POST')) {
            $authUser = Auth::user();
            $newCategory->authId = $authUser->id;
            $newCategory->type = $request->type;
            $newCategory->name = $request->name;

            if ($newCategory->save()) {
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
        $categoryData = CategoryOption::find($id);
        if ($request->val) {
            $categoryData->status = 0;
        } else {
            $categoryData->status = 1;
        }

        if ($categoryData->save()) {
            return redirect()->back()->with('success', 'Status is successfully updated!');
        } else {
            return redirect()->back()->with('error', 'Some error is occur!');
        }
    }

    public function categoryDelete($id = null)
    {
        $categoryData = CategoryOption::find($id);
        if ($categoryData->delete()) {
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
        if ($request->incorporation_id > 0) {
            $newCategory = CategoryOption::where('id', $request->incorporation_id)->first();
        } else {
            $newCategory = new CategoryOption;
        }
        if ($request->isMethod('POST')) {
            $authUser = Auth::user();
            $newCategory->authId = $authUser->id;
            $newCategory->type = $request->type;
            $newCategory->name = $request->name;

            if ($newCategory->save()) {
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
        if ($request->referral_id > 0) {
            $newCategory = CategoryOption::where('id', $request->referral_id)->first();
        } else {
            $newCategory = new CategoryOption;
        }
        if ($request->isMethod('POST')) {
            $authUser = Auth::user();
            $newCategory->authId = $authUser->id;
            $newCategory->type = $request->type;
            $newCategory->name = $request->name;
            if ($newCategory->save()) {
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
        $activityTitles = Log::select('title')->distinct()->orderBy('title', 'asc')->pluck('title');
        $activityUsers = Log::select('user_id')->distinct()->orderBy('user_id', 'asc')->pluck('user_id');

        $filterOptions['completeDate'] = $request->dateRange ?? '';
        // dd($filterOptions['completeDate']);
        if (!empty($selectedDate)) {
            $selectedDate = explode('-', $filterOptions['completeDate']);
            $filterOptions = [
                'startDate' => date('Y-m-d', strtotime($selectedDate[0])),
                'endDate' => date('Y-m-d', strtotime($selectedDate[1])),
                'completeDate' => $request->dateRange
            ];
        }

        // $query = Log::with(['user' => function ($query) {
        //     $query->select('id', 'title', 'post'); // Specify the columns you want from the `user` table
        // }])->orderBy('id', 'desc');

        $filterOptions['user_id'] = $request->user_id ?? '';
        $filterOptions['activity'] = $request->activity ?? '';
        // dd($filterOptions['activity']);
        $query = Log::with(['user' => function ($query) {
                $query->select('id','name', 'ip_address', 'operating_system'); // Specify the columns you want from the `user` table
            }])->orderBy('id', 'desc');
    
        if (!empty($filterOptions['startDate']) && !empty($filterOptions['endDate'])) {
            $query->whereDate('created_at', '>=', $filterOptions['startDate'])->whereDate('created_at', '<=', $filterOptions['endDate']);
        }

        if (!empty($filterOptions['user_id'])) {
            $query->where('user_id', $filterOptions['user_id']);
        }

        if (!empty($filterOptions['activity'])) {
            $query->where('title', 'like', '%' . $filterOptions['activity'] . '%');
        }
       
     
        
        $systemLogs = $query->paginate(8)->appends($request->query());
        
        return view('users.logs', compact('header_title_name', 'systemLogs', 'activityTitles', 'activityUsers', 'filterOptions'));
    }
}
