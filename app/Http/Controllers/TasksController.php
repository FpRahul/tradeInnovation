<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function index(){
        $header_title_name="Tasks";
        return view('tasks/index',compact('header_title_name'));
    }

    public function logs(){
        $header_title_name="Lead Logs";
        return view('tasks/logs',compact('header_title_name'));
    }

    public function detail(){
        $header_title_name="Lead action & details";
        return view('tasks/detail',compact('header_title_name'));
    }
}
