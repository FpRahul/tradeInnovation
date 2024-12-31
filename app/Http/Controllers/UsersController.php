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
        return view('users/listing');
    }

    public function addUser(Request $request, $id=null){
        $data = new User();
        if($id > 0){
            $data = User::with('userdetail')->find($id);            
        }
        if($request->isMethod('POST')){           
            $randomNumber = random_int(100000, 999999);
            $hashedPassword = Hash::make($randomNumber);
            $credentials = $request->validate([
                'name' => 'required',
                'role' => 'required',
                'email' => 'required|email|unique:users,email',
                'number' => 'required',
                'companyname' => 'required',
                'address' => 'required',
                'registered' => 'required',
            ]);
            $newUser = new User();
            $newUser->name = $request->name;
            $newUser->role = $request->role;
            $newUser->email = $request->email;
            $newUser->mobile = $request->number;
            $newUser->companyName = $request->companyname;
            $newUser->address = $request->address;
            $newUser->password = $hashedPassword;

            if($newUser->save()){
                // mail($request->email,'subject',$randomNumber);
                $newUserDetails = new UserDetail();
                $newUserDetails->userId =$newUser->id;
                $newUserDetails->incorporationType = $request->incorporationtype;
                $newUserDetails->registered = $request->registered;
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
        return view('users.add-user',compact('data'));
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
