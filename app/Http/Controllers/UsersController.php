<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserExperience;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    
    public function login(Request $request){       
        
        if($request->isMethod('post')){
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if(Auth::attempt($credentials))
            {
                $request->session()->regenerate();
                $user = Auth::user();
              
                return redirect()->route('dashboard')->withSuccess('You have successfully logged in!');
            }

            return redirect()->back()->with('error','The provided credentials do not match our records.');
        }
       
        return view('users/login');
    }

    public function forgetPassword(Request $request){
        if($request->isMethod('POST')){
            $credentials = $request->validate([
                'email'=>'required|email',                
            ]);
            $updatePass = User::where('email',$request->email)->first();
            if($updatePass){
                $randomNumber = random_int(100000, 999999);
                $hashedPassword = Hash::make($randomNumber);
                $updatePass->password = $hashedPassword;
                
                if($updatePass->save()){
                    return redirect()->route('login')->withSuccess('Password is successfully updated! '); 
                }else{
                    return back()->with('error','Some error is occur.'); 
                }
            }else{
                return back()->with('error','Email does not exist!'); 
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
        $employeeData = User::with('userdetail')->where('role', 2)->where('archive', 1);
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if ($searchKey) {
            $employeeData->where(function ($query) use ($searchKey) {
                $query->where('name', 'LIKE', $searchKey . '%')
                    ->orWhere('mobile', 'LIKE', $searchKey . '%');
            });
        }
        $employeeData = $employeeData->paginate(2);

        if (empty($requestType)) {    
            $header_title_name = 'User';
            $moduleName="Manage Employees";
            return view('users.listing', compact('employeeData', 'header_title_name', 'moduleName','searchKey'));       
        }else{
            $trData = view('users/employee-page-search-data', compact('employeeData','searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
        }
            
    }

    public function addUser(Request $request, $id=null){
        if($id > 0){
            $newUser = User::find($id);  
            $newUserDetails = UserDetail::where('userId',$id)->first();
            $newUserExperiences = UserExperience::where('userId',$id)->get();
            $eImage = !empty($newUserDetails['uploadPhotograph']) ? '':'required|mimes:jpeg,png,jpg,pdf|max:2048';           
            $pImage = !empty($newUserDetails['uploadPan']) ? '':'required|mimes:jpeg,png,jpg,pdf|max:2048';
            $aImage = !empty($newUserDetails['uploadAadhar']) ? '':'required|mimes:jpeg,png,jpg,pdf|max:2048';
            $dImage = !empty($newUserDetails['uploadDrivingLicence']) ? '':'required|mimes:jpeg,png,jpg,pdf|max:2048';
            $email = "required|email";
            $hashedPassword = $newUser->password;
            $successMessage = "User is successfully updated!";
            $moduleName="Update Employee";
        }else{
            $newUser = new User();
            $newUserDetails = new UserDetail();
            $eImage = "required|mimes:jpeg,png,jpg,pdf|max:2048";            
            $pImage = "required|mimes:jpeg,png,jpg,pdf|max:2048";
            $aImage = "required|mimes:jpeg,png,jpg,pdf|max:2048";
            $dImage = "required|mimes:jpeg,png,jpg,pdf|max:2048";
            $newUserExperiences = [];
            $email = "required|email|unique:users,email";
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);
            $successMessage = "User is successfully inserted!";
            $moduleName="Create Employee";
        }
        if($request->isMethod('POST')){ 
           
            $credentials = $request->validate([               
                'email'=> $email,
                'employeePhoto' => $eImage,             
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
            if($newUser->save()){             
                $newUserDetails->userId =$newUser->id;
                $newUserDetails->fatherHusbandName =$request->fatherHusbandName;
                $newUserDetails->qualification =$request->qualification;
                $newUserDetails->skills = $request->skill;
                $newUserDetails->keyResponsibilityArea = $request->keyResponsibilityArea;
                $newUserDetails->keyPerformanceIndicator = $request->keyPerformanceIndicator;
                $newUserDetails->emergencyContactDetails = $request->emergencyContactDetails;
                $newUserDetails->currentAddress = $request->currentAddress;
                $newUserDetails->permanentAddress = $request->permanentAddress;

                if($request->hasFile('employeePhoto'))
                {
                    $image_name = $request->employeePhoto;
                    $imageName = rand(100000, 999999) . '.' . $image_name->getClientOriginalExtension();
                    $image_name->move(public_path('Image'), $imageName);
                    $newUserDetails->uploadPhotograph = $imageName;
                }
                if($request->hasFile('uploadPan')){
                    $image_name = $request->uploadPan;
                    $imageName = rand(100000, 999999) . '.' . $image_name->getClientOriginalExtension();
                    $image_name->move(public_path('Image'),$imageName);
                    $newUserDetails->uploadPan = $imageName;
                }
                if($request->hasFile('uploadAadhar')){
                    $image_name = $request->uploadAadhar;
                    $imageName = rand(100000, 999999) . '.' . $image_name->getClientOriginalExtension();
                    $image_name->move(public_path('Image'),$imageName);
                    $newUserDetails->uploadAadhar = $imageName;
                }
                if($request->hasFile('uploadDrivingLicence')){
                    $image_name = $request->uploadDrivingLicence;
                    $imageName = rand(100000, 999999) . '.' . $image_name->getClientOriginalExtension();
                    $image_name->move(public_path('Image'),$imageName);
                    $newUserDetails->uploadDrivingLicence = $imageName;

                }
                foreach($request->experince as $exKey => $exVal){
                   if($exVal['experience_id'] > 0){                   
                        $newUserExperiences = UserExperience::find($exVal['experience_id']);                       
                   }else{
                        $newUserExperiences = new UserExperience();                        
                   }
                    $newUserExperiences->userId = $newUser->id;
                    $newUserExperiences->employerName = $exVal['employerName'];
                    $newUserExperiences->startDate = $exVal['startDate'];
                    $newUserExperiences->endDate = $exVal['endDate'];
                    $newUserExperiences->save();
                } 

                if($newUserDetails->save()){
                   
                    return redirect()->route('users.listing')->withSuccess($successMessage);
                }else{
                    return back()->with('error','Some error is occur.'); 
                }
            }else{
                return back()->with('error','Some error is occur.');
            }
        }
        
       
        $header_title_name = 'User';
        return view('users.add-user',compact('newUser','newUserDetails','newUserExperiences','header_title_name','moduleName'));
    }
    
    public function deleteUser(Request $request,$id=null){        
       $employeeData = User::where('id',$id)->first();
         $employeeData->archive = 0;
         if($employeeData->save()){
           return redirect()->back()->with('success','Your data is successfully deleted');
         }
    }

    public function deleteRepeaterUser(Request $request){
       
        $experienceData = UserExperience::where('id',$request->id);
        if($experienceData->delete()){
            echo "1";
        }else{
            echo "0";
        }

    }

    public function clients(Request $request){
        $clientData = User::with('userdetail')->where('role', 3)->where('archive', 1);
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if ($searchKey) {
            $clientData->where(function ($query) use ($searchKey) {
                $query->where('name', 'LIKE', $searchKey . '%')
                    ->orWhere('mobile', 'LIKE', $searchKey . '%');
            });
        }
        $clientData = $clientData->paginate(1);

        if (empty($requestType)) {    
            $header_title_name = 'User';
            $moduleName="Manage Clients";
            return view('users/client-listing', compact('clientData', 'header_title_name', 'moduleName','searchKey'));       
        }else{
            $trData = view('users/client-page-search-data', compact('clientData','searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
        }
    }

    public function addClient(Request $request,$id=null){
        if($id > 0){
            $newClient = User::find($id);
            $newClientDetails = UserDetail::where('userId',$id)->first();
            $hashedPassword = $newClient->password;
            $email = "required|email";
            $moduleName="Update Client";

        }else{
            $newClient = new User();
            $newClientDetails = new UserDetail();
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);
            $email = "required|email|unique:users,email";
            $moduleName="Add Client";
        }
        if($request->isMethod('POST')){
            $credentials = $request->validate([
               'email' => $email,
            ]);            
            
            $newClient->name = $request->name ;
            $newClient->role = $request->role ;
            $newClient->email = $request->email ;
            $newClient->altEmail = $request->alternateEmail ;
            $newClient->mobile = $request->number ;
            $newClient->altNumber = $request->alternatePhone ;
            $newClient->companyName = $request->companyname ;
            $newClient->address = $request->address ;
            $newClient->communicationAdress = $request->communi_address ;
            $newClient->password = $hashedPassword ;
            if($newClient->save()){
                
                $newClientDetails->userId = $newClient->id;
                $newClientDetails->incorporationType = $request->incorporationtype;
                $newClientDetails->registered = $request->registered;
                $newClientDetails->referralPartner = $request->referralPartner;
                if($newClientDetails->save()){
                    return redirect()->route('client.listing')->withSuccess('Client is successfully inserted!');
                }else{
                    return back()->with('error','Some error is occur.'); 

                }
            }else{
                return back()->with('error','Some error is occur.'); 
            }
        }
        $header_title_name = 'User';
        return view('users.add-client',compact('newClient','newClientDetails','header_title_name','moduleName'));
    }

    public function associates(Request $request){
        $associateData = User::with('userdetail')->where('role', 4)->where('archive', 1);
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if ($searchKey) {
            $associateData->where(function ($query) use ($searchKey) {
                $query->where('name', 'LIKE', $searchKey . '%')
                    ->orWhere('mobile', 'LIKE', $searchKey . '%');
            });
        }

        $associateData = $associateData->paginate(1);
        if (empty($requestType)) {    
            $header_title_name = 'User';
            $moduleName="Manage Associates";
            return view('users/associate-listing', compact('associateData', 'header_title_name', 'moduleName','searchKey'));       
        }else{
            $trData = view('users/associate-page-search-data', compact('associateData','searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
        }
    }

    public function addAssociate(Request $request,$id=null){
        if($id > 0){
            $newAssociate = User::find($id);
            $hashedPassword = $newAssociate->password;
            $email = "required|email";
            $moduleName="Update Associate";

        }else{
            $newAssociate = new User();
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);
            $email = "required|email|unique:users,email";
            $moduleName="Add Associate";

        }
        if($request->isMethod('POST')){
            $credentials = $request->validate([
               'email' =>  $email,
            ]);            
            
            $newAssociate->name = $request->name ;
            $newAssociate->Profession = $request->profession ;
            $newAssociate->role = $request->role ;
            $newAssociate->email = $request->email ;
            $newAssociate->altEmail = $request->alternateEmail ;
            $newAssociate->mobile = $request->number ;
            $newAssociate->altNumber = $request->alternatePhone ;
            $newAssociate->companyName = $request->firmName ;
            $newAssociate->address = $request->address ;
            $newAssociate->password = $hashedPassword ;
            if($newAssociate->save()){               
                return redirect()->route('associate.listing')->withSuccess('Associate is successfully inserted!');
            }else{
                return back()->with('error','Some error is occur.'); 

            }
            
        }
        $header_title_name = 'User';
        return view('users/add-associate',compact('newAssociate','header_title_name','moduleName'));
    }

    public function userStatus(Request $request){
        if($request->isMethod('GET')){
            if($request->val){
                $status = 0;
            }else{
                $status = 1;
            }
            $existedUser = User::where('id',$request->id)->first();
            $existedUser->status = $status;
            if($existedUser->save()){
                return redirect()->back()->with('success', 'Your status is successfully updated!');
            }else{
                return back()->withError('Some error is occur');
            }
        }
    }

    public function myprofile(Request $request,$id = null){
        $user = Auth::user();
        $userData = User::where('id',$user->id)->first();
        if($request->isMethod('POST')){
            $credentials = $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'pasword' => 'required',                
            ]);
                if(!empty($request->password)){
                    $password =  Hash::make($request->password);
                }else{
                    $password = $request->password;
                }
                dd($userData);
               $userData->name=$request->name;
               $userData->email=$request->email; 
               $userData->password=$password;
               if($userData->save()){
                return redirect()->route('dashboard')->withSuccess('Profile is successfully updated!');
               }        
        }
        $header_title_name = 'My Profile';
        $moduleName="Update Profile";
        
        return view('users/myprofile',compact('userData','header_title_name','moduleName'));
    }

    
}
