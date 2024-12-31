<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function roles(){
        return view('settings/roles');
    }

    public function addRole(){
        return view('settings/add-role');
    }
}
