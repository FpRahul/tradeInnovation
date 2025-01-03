<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeadsController extends Controller
{
    public function index(){
        $header_title_name = 'Leads';
        $moduleName = "Manage Leads";
        return view('leads/index',compact('header_title_name','moduleName'));
    }

    public function add(){
        $header_title_name = 'Lead';
        $moduleName = "Add Leads";
        return view('leads/add',compact('header_title_name','moduleName'));
    }

    public function sendQuote(){
        $header_title_name = 'Lead';
        $moduleName = "Quote";
        return view('leads/sendquote', compact('header_title_name', 'moduleName'));
    }
    public function leadLogs()
    {
        $header_title_name = 'Lead';
        $moduleName = "Logs";
        return view('leads/logs', compact('header_title_name', 'moduleName'));
    }
}
