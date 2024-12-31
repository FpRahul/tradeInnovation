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

    public function index(){
        $employeeData = User::with('userdetail')->where('role',2)->where('archive',1)->get();
        return view('users/listing',compact('employeeData'));
    }

    public function addUser(Request $request, $id=null){
        if($id > 0){
            $newUser = User::find($id);  
            $newUserDetails = UserDetail::where('userId',$id)->first();
            $email = "'email' => 'required|email";

        }else{
            $newUser = new User();
            $newUserDetails = new UserDetail();
            $email = "'email' => 'required|email|unique:users,email'";

        }
        if($request->isMethod('POST')){  
            if($request->registered == 'on'){
                $registered = 1;
            }else{
                $registered = 0;
            }
         
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);
            $credentials = $request->validate([
                'name' => 'required',
                'role' => 'required',
                $email,
                'number' => 'required',
                'companyname' => 'required',
                'address' => 'required',
                'registered' => 'required',
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
        return view('users/client-listing');
    }

    public function addClient(){
        return view('users.add-client');
    }

    public function associates(){
        return view('users/associate-listing');
    }

    public function addAssociate(){
        return view('users/add-associate');
    }


    
}
