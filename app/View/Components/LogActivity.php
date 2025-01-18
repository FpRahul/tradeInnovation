<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Log; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LogActivity extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct($activityDetails)
    {
        $this->activityDetails = $activityDetails;
    }

    public function log()
    {
        $user = Auth::user();

        if ($user) {
            Log::insert($this->activityDetails);
        }

        return null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.log-activity');
    }
}
