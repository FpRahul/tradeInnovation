<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\Menu;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function roles(){
        $allRoles = Role::get();
        return view('settings/roles',compact('allRoles'));
    }

    public function addRole(){
        return view('settings/add-role');
    }
}
