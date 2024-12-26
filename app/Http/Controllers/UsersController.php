<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    
    public function login(){
        //piyush
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
