<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\Menu;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function roles(){
        $header_title_name="Setting";
        $allRoles = Role::get();
        $moduleName="Manage Roles";
        return view('settings/roles',compact('allRoles','header_title_name','moduleName'));
    }

    public function addRole(){
        $header_title_name="Setting";
        $moduleName="Create Role";
        return view('settings/add-role',compact('header_title_name','moduleName'));
    }
}
