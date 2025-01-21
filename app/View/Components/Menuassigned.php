<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\RoleMenu;

class Menuassigned extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct($roleId)
    {
       $this->roleId = $roleId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $allRoleMenu = RoleMenu::where('roleId',$this->roleId)->get();
        $assignedMenuToRole = [];
        if(!$allRoleMenu && $allRoleMenu->isNotEmpty()){
            foreach($allRoleMenu as $k => $v){
                $assignedMenuToRole[] = '';
            }
        }else{
            return '<div>N/A</div>';
        }
        
        return view('components.menuassigned',compact('userRole'));
    }
}
