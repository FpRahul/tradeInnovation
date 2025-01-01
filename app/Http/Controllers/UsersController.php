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

            return back()->with('error','The provided credentials do not match our records.');
        }
       
        return view('users/login');
    }

    public function forgetPassword(Request $request){
        if($request->isMethod('POST')){
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);
            $updatePass = User::where('email',$request->email);
            $updatePass->password = $hashedPassword;
            if($updatePass->save()){
                return redirect()->route('login')->withSuccess('Password is successfully updated! '); 
            }else{
                return back()->with('error','Some error is occur.'); 
            }
        }
    }
    public function index(){
        $employeeData = User::with('userdetail')->where('role',2)->where('archive',1)->get();
        return view('users/listing',compact('employeeData'));
    }

    public function addUser(Request $request, $id=null){
        if($id > 0){
            $newUser = User::find($id);  
            $newUserDetails = UserDetail::where('userId',$id)->first();
            $email = "required|email";
            $hashedPassword = $newUser->password;
        }else{
            $newUser = new User();
            $newUserDetails = new UserDetail();
            $email = "required|email|unique:users,email'";
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);

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
                'email'=>$email,
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
                    return redirect()->route('users.listing')->withSuccess('User is successfully inserted!');
                }else{
                    return back()->with('error','Some error is occur.'); 
                }
            }else{
                return back()->with('error','Some error is occur.');
            }
        }
        return view('users.add-user',compact('newUser','newUserDetails'));
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
        $clientData = User::with('userdetail')->where('role',3)->where('archive',1)->orderBy('id','desc')->get();
        return view('users/client-listing',compact('clientData'));
    }

    public function addClient(Request $request,$id=null){
        if($id > 0){
            $newClient = User::find($id);
            $newClientDetails = UserDetail::where('userId',$id)->first();
            $hashedPassword = $newClient->password;
            $email = "'email' => 'required|email";
        }else{
            $newClient = new User();
            $newClientDetails = new UserDetail();
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);
            $email = "'email' => 'required|email|unique:users,email'";

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
        return view('users.add-client',compact('newClient','newClientDetails'));
    }

    public function associates(){
        $associateData = User::with('userdetail')->where('role',4)->where('archive',1)->orderBy('id','desc')->get();
        return view('users/associate-listing',compact('associateData'));
    }

    public function addAssociate(Request $request,$id=null){
        if($id > 0){
            $newAssociate = User::find($id);
            $hashedPassword = $newAssociate->password;
            $email = "'email' => 'required|email";
        }else{
            $newAssociate = new User();
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);
            $email = "'email' => 'required|email|unique:users,email'";

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
        return view('users/add-associate',compact('newAssociate'));
    }

    public function userStatus(Request $request){
        if($request->isMethod('POST')){
            $existedUser = User::where('id',$request->userId)->first();
            $existedUser->status = $request->statusVal;
            if($existedUser->save()){
                echo "status changed";die;
            }
        }
    }

    
}
