<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component; 
use App\Models\LeadTask;

class ClientTaskDetails extends Component
{
   
   public $taskID;
    public function __construct($taskID)
    {
        $this->taskID = $taskID;
    }
    
    public function render(): View|Closure|string
    {   
        $taskDetails = LeadTask::with([
                'user', 
                'lead', 
                'leadTaskDetails', 
                'services',
                 'subService',
                'serviceSatge'
            ])
          ->where('id', $this->taskID)->first();
          return view('components.client-task-details', compact('taskDetails'));
    }
}
