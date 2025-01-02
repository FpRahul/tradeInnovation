<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $header_title_name = 'Dashboard';

        return view('dashboard/index',compact('header_title_name'));
    } 
}
