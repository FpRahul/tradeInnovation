<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserDetail;

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

        if ($request->isMethod('POST')) {
            $key = $request->input('key');
            if ($key) {
                $employeeData->where(function ($query) use ($key) {
                    $query->where('name', 'LIKE', $key . '%')
                        ->orWhere('mobile', 'LIKE', $key . '%');
                });
            }
            $employeeData = $employeeData->get();
            dd($employeeData);
        }

     
        $employeeData = $employeeData->paginate(1);
        
        $header_title_name = 'User';
        $moduleName="Manage Employees";
        return view('users.listing', array_merge(
            compact('employeeData', 'header_title_name', 'moduleName')
        ));
    }

    public function addUser(Request $request, $id=null){
        if($id > 0){
            $newUser = User::find($id);  
            $newUserDetails = UserDetail::where('userId',$id)->first();
            $email = "required|email";
            $hashedPassword = $newUser->password;
            $successMessage = "User is successfully updated!";
            $moduleName="Update Employee";
        }else{
            $newUser = new User();
            $newUserDetails = new UserDetail();
            $email = "required|email|unique:users,email";
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);
            $successMessage = "User is successfully inserted!";
            $moduleName="Create Employee";
        }
        if($request->isMethod('POST')){  
            if($request->registered == 'on'){
                $registered = 1;
            }else{
                $registered = 0;
            }
         
            
            $credentials = $request->validate([
                'name' => 'required',
                'role' => 'required',
                'email'=> $email,
                'number' => 'required',
                'companyname' => 'required',
                'address' => 'required',
                'registered' => 'required',
                'referralpartner'=> 'required',
                'incorporationtype'=> 'required'
            ]);
            // dd($id);
            $newUser->name = $request->name;
            $newUser->role = $request->role;
            $newUser->email = $request->email;
            $newUser->mobile = $request->number;
            $newUser->companyName = $request->companyname;
            $newUser->address = $request->address;
            $newUser->password = $hashedPassword;
            if($newUser->save()){               
                $newUserDetails->userId =$newUser->id;
                $newUserDetails->incorporationType = $request->incorporationtype;
                $newUserDetails->registered = $registered;
                $newUserDetails->referralPartner = $request->referralpartner;   
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
        return view('users.add-user',compact('newUser','newUserDetails','header_title_name','moduleName'));
    }
    
    public function deleteEmployee(Request $request){
       $employeeId = $request->employeeId;
       $employeeData = User::where('id',$employeeId)->first();
         $employeeData->archive = 0;
         if($employeeData->save()){
            echo "deleted";die;
         }
    }

    public function clients(){
        $clientData = User::with('userdetail')->where('role',3)->where('archive',1)->orderBy('id','desc')->paginate(1);
        $header_title_name = 'User';
        $moduleName="Manage Clients";

        return view('users/client-listing',compact('clientData','header_title_name','moduleName'));
    }

    public function addClient(Request $request,$id=null){
        if($id > 0){
            $newClient = User::find($id);
            $newClientDetails = UserDetail::where('userId',$id)->first();
            $hashedPassword = $newClient->password;
            $email = "'email' => 'required|email";
            $moduleName="Update Client";

        }else{
            $newClient = new User();
            $newClientDetails = new UserDetail();
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);
            $email = "'email' => 'required|email|unique:users,email'";
            $moduleName="Add Client";
        }
        if($request->isMethod('POST')){
            
            $credentials = $request->validate([
                'name' => 'required',
                'role' => 'required',
                $email,
                'number' => 'required',
                'companyname' => 'required',
                'address' => 'required',
                'registered' => 'required',
            ]);
            if($request->registered == 'on'){
                $registered = 1;
            }else{
                $registered = 0;
            }
            
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
                $newClientDetails->registered = $registered;
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

    public function associates(){
        $associateData = User::with('userdetail')->where('role',4)->where('archive',1)->orderBy('id','desc')->paginate(1);
        $header_title_name = 'User';
        $moduleName="Manage Associates";
        return view('users/associate-listing',compact('associateData','header_title_name','moduleName'));
    }

    public function addAssociate(Request $request,$id=null){
        if($id > 0){
            $newAssociate = User::find($id);
            $hashedPassword = $newAssociate->password;
            $email = "'email' => 'required|email'";
            $moduleName="Update Associate";

        }else{
            $newAssociate = new User();
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);
            $email = "'email' => 'required|email|unique:users,email'";
            $moduleName="Add Associate";

        }
        if($request->isMethod('POST')){
            $credentials = $request->validate([
                'name' => 'required',
                'role' => 'required',
                $email,
                'number' => 'required',
                'firmName' => 'required',
                'address' => 'required',
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
        if($request->isMethod('POST')){
            $existedUser = User::where('id',$request->userId)->first();
            $existedUser->status = $request->val;
            if($existedUser->save()){
                echo "status changed";die;
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
