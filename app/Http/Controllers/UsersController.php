<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
              
                return redirect()->route('admin.dashboard')->withSuccess('You have successfully logged in!');
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
        return view('users/users-listing');
    }

    public function addUser(){
        return view('users/add-user');
    }
}
