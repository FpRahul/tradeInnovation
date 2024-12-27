<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function roles(){
        return view('users/roles');
    }

    public function addRole(){
        return view('users/add-role');
    }

    public function index(){
        return view('users/listing');
    }

    public function addUser(Request $request){
        // UserDetails
        if($request->isMethod('POST')){
            $credentials = $request->validate([
                'name' => 'required',
                'role' => 'required',
                'email' => 'required|email|unique:users,email',
                'number' => 'required|digits:10',
                'companyname' => 'required',
                'address' => 'required',
                'incorporationType' => 'required',
                'registered' => 'required',
                'referralpartner' => 'required',
            ]);
            $newUser = new User();
            $newUser->name = $credentials->name;
            $newUser->role = $credentials->role;
            $newUser->email = $credentials->email;
            $newUser->mobile = $credentials->number;
            $newUser->companyName = $credentials->companyname;
            $newUser->address = $credentials->address;
            if($newUser->save()){
                $newUserDetails = new UserDetails();
                $newUserDetails->userId =$newUser->id;
                $newUserDetails->incorporationType = $credentials->incorporationtype;
                $newUserDetails->registered = $credentials->registered;
                $newUserDetails->referralPartner = $credentials->referralpartner;   
                if($newUserDetails->save()){
                    return redirect()->route('users.listing')->withSuccess('User is successfully inserted!');
                }else{
                    return back()->with('error','Some error is occur.'); 
                }
            }else{
                return back()->with('error','Some error is occur.');
            }
        }
        return view('users/add-user');
    }
}
