<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Lead;
use App\Models\Service;


use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $header_title_name = 'Dashboard';
        $activeAssociate = User::where('role', 3)->where('status', 1)->where('archive',1)->count();
        $activeClient = User::where('role', 2)->where('status', 1)->where('archive',1)->count();
        $activeClient = User::where('role', 2)->where('status', 1)->where('archive',1)->count();
        $activeLeads = Lead::where('status', 1)->count();
        $activeServices = Service::where('status', 1)->count();
        return view('dashboard/index',compact('header_title_name','activeAssociate','activeClient','activeLeads','activeServices'));
    } 

    public function chartData()
    {   
        
        $labels = ['Jan', 'Febr', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Patent',
                    'data' => [12, 19, 3, 5, 2, 8, 15, 18, 10, 14, 20, 25],
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.7)',
                    'fill' => false,
                    'tension' => 0.1,
                ],
                [
                    'label' => 'Trademark',
                    'data' => [15, 10, 22, 30, 25, 28, 18, 22, 12, 14, 24, 26],
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.7)',
                    'fill' => false,
                    'tension' => 0.1,
                ],
                [
                    'label' => 'Design',
                    'data' => [8, 12, 18, 24, 10, 14, 20, 16, 22, 18, 25, 30],
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.7)',
                    'fill' => false,
                    'tension' => 0.1,
                ],
                [
                    'label' => 'Copyright',
                    'data' => [7, 14, 21, 28, 14, 20, 12, 18, 24, 30, 16, 10],
                    'borderColor' => 'rgba(255, 206, 86, 1)',
                    'backgroundColor' => 'rgba(255, 206, 86, 0.7)',
                    'fill' => false,
                    'tension' => 0.1,
                ],
                [
                    'label' => 'GI Tag',
                    'data' => [5, 25, 15, 35, 20, 22, 18, 14, 30, 28, 12, 10],
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'backgroundColor' => 'rgba(153, 102, 255, 0.7)',
                    'fill' => false,
                    'tension' => 0.1,
                ],
                [
                    'label' => 'ISO Certification',
                    'data' => [10, 20, 30, 40, 50, 35, 25, 15, 45, 55, 65, 75],
                    'borderColor' => 'rgba(255, 159, 64, 1)',
                    'backgroundColor' => 'rgba(255, 159, 64, 0.7)',
                    'fill' => false,
                    'tension' => 0.1,
                ],
                [
                    'label' => 'ZED Certification',
                    'data' => [22, 14, 19, 30, 25, 20, 28, 16, 18, 24, 12, 14],
                    'borderColor' => 'rgba(0, 128, 128, 1)',
                    'backgroundColor' => 'rgba(0, 128, 128, 0.7)',
                    'fill' => false,
                    'tension' => 0.1,
                ],
                [
                    'label' => 'Food Licence',
                    'data' => [12, 18, 24, 30, 36, 42, 48, 20, 15, 25, 35, 45],
                    'borderColor' => 'rgba(128, 0, 128, 1)',
                    'backgroundColor' => 'rgba(128, 0, 128, 0.7)',
                    'fill' => false,
                    'tension' => 0.1,
                ]
            ],
        ]);
    }

  
}
