<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index(){
        $header_title_name="Service";
        $moduleName="Manage Services";
        return view('services/index',compact('header_title_name','moduleName'));
    }

    public function add(){
        $header_title_name="Service";
        $moduleName="Create Service";
        return view('services/add',compact('header_title_name','moduleName'));
    }
}
