<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\LeadNotification;
use App\Models\User;

class GetAssignedNotification extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {    
        $assignUser = auth()->user();
        $notificationData = LeadNotification::with('taskDeatials')->where('status',0)->orderBy('id','DESC')->get();
        $notifyCount = $notificationData->count();
        if ($assignUser->role != 1) {
            $notificationData = $notificationData->where('user_id', $assignUser->id);
            $notifyCount = $notificationData->count();
        }
        
        return view('components.get-assigned-notification',compact('notificationData','notifyCount'));
    }
}
