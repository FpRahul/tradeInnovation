<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\LeadAssign;
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
        $assignedData = User::with('assignLead')->where('id',auth()->user()->id)->orderBy('id','DESC')->get();
        // $assignedData = AssignLead::with('userDetails')->where('user_id',auth()->user()->id)->orderBy('id','DESC')->get();

        return view('components.get-assigned-notification',compact('assignedData'));
    }
}
