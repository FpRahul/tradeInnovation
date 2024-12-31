<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeadsController extends Controller
{
    public function index(){
        return view('leads/index');
    }

    public function add(){
        return view('leads/add');
    }
}
