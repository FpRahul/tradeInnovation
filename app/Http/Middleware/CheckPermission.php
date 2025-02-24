<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use App\Models\RoleMenu;
use App\Models\MenuAction;

class CheckPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        //Ensure user is authenticated
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }
        $routeName = $request->route()->getName();
        $permissionDetails = [
            'status' => false,
            'menuId' => [1],
            'accessableRoutes' => [
                0 => 'user.logout', //default logout permission
                1 => 'user.myprofile', //default profile permission
                2 => 'chart.data', //default chat preview permission
                3 => 'dashboard', //default dashboard permission
                4 => 'serviceStages', //default permission to get stages according to selected service on load add form
                5 => 'lead.subservice',  //default permission to get sub service on lead add form
                6 => 'lead.getsourcetypename', //default permission to get source name
                7 => 'users.deleterepeater', //default permission to delete user experience
                8 => 'lead.deleterepeater', //default permission to delete service from lead add page
                9 => 'lead.deleteattachmentrepeater', //default permission to delete attachmanet on lead add page
                10 => 'leads.fetch', // default permission to fetch lead data on popup
                11 => 'leads.edit', // default permission to lead edit on popup
                12 => 'lead.checkDuplicateEmail' // default permission to check duplicate email
            ]
        ];
        if($user->role==1){
            $permissionDetails['status'] = true;
            $systemMenus = Menu::get();
        }else{
            $permissions = RoleMenu::where('roleId',$user->role)->get();
            if($permissions->isNotEmpty()){
                foreach($permissions as $permission){
                    $menuDetail = Menu::find($permission->menuId);
                    $availableActions = explode(',',$permission->permission);
                    $menuActions = MenuAction::whereIn('id',$availableActions)->get();
                    if($menuActions->isNotEmpty()){
                        foreach($menuActions as $menuAction){
                            $permissionDetails['accessableRoutes'][] = $menuAction->route;
                        }
                        $permissionDetails['accessableRoutes'][] = $menuDetail->url;
                        $permissionDetails['menuId'][] = $menuDetail->id;
                        if($menuDetail->parentId>0){
                            $permissionDetails['menuId'][] = $menuDetail->parentId;
                        }
                    }else{
                        $permissionDetails['accessableRoutes'][] = $menuDetail->url;
                        $permissionDetails['menuId'][] = $permission->menuId;
                    }
                }
            }
            $permissionDetails['status'] = in_array($routeName,$permissionDetails['accessableRoutes']);

            //special condition for all forms step
            $affFormRoutes = $this->getAllFormRoutes();
            if(in_array('task.followup',$permissionDetails['accessableRoutes']) && in_array($routeName,$affFormRoutes)){
                $permissionDetails['status'] = true;
            }
            //End
            if (!$permissionDetails['status']) {
                abort(403, 'You do not have permission to access this page.');
            }
            $systemMenus = Menu::whereIn('id',$permissionDetails['menuId'])->get();
        }
        
        $serializeMenus = [];
        $menuSubMenuRoutes = [];
        $subMenuActions = [];
        if($systemMenus->isNotEmpty()){
            foreach($systemMenus as $k =>$v){
                if($v->parentId==0){
                    $serializeMenus[$v->id]['menu']['name'] = $v->menuName;
                    $serializeMenus[$v->id]['menu']['url'] = $v->url;
                    $serializeMenus[$v->id]['menu']['icon'] = $v->icon;
                    $serializeMenus[$v->id]['menu']['groupedRoutes'] = $v->actionRoutes;
                    $serializeMenus[$v->id]['menu']['sequence'] = $v->sequence;
                }
                if($v->parentId>0){
                    //Check if it is sub menu or sub sub menu
                    $checkLinkedMenus = Menu::find($v->parentId);
                    if($checkLinkedMenus->parentId>0){
                        //it is sub sub menu
                        $serializeMenus[$checkLinkedMenus->parentId]['subSubMenu'][$checkLinkedMenus->id][$v->id]['name'] = $v->menuName;
                        $serializeMenus[$checkLinkedMenus->parentId]['subSubMenu'][$checkLinkedMenus->id][$v->id]['url'] = $v->url;
                        $serializeMenus[$checkLinkedMenus->parentId]['subSubMenu'][$checkLinkedMenus->id][$v->id]['icon'] = $v->icon;
                    }else{
                        $serializeMenus[$v->parentId]['subMenu'][$v->id]['name'] = $v->menuName;
                        $serializeMenus[$v->parentId]['subMenu'][$v->id]['url'] = $v->url;
                        $serializeMenus[$v->parentId]['subMenu'][$v->id]['icon'] = $v->icon;
                    }
                }

                //Grouping routes per menu for active class
                if($v->parentId>0){
                    $groupedRoutes = explode(',',$v->actionRoutes);
                    $subMenuActions[$v->id] = $groupedRoutes;
                    if(!empty($groupedRoutes)){
                        foreach($groupedRoutes as $groupedRoute){
                            $menuSubMenuRoutes[$v->parentId][] = $groupedRoute;
                            if($v->url=='javascript:void(0);'){
                                $menuSubMenuRoutes[$v->parentId][$v->id][] = $groupedRoute;
                            }
                        }
                    }
                }
                //End
            }
            uasort($serializeMenus, function ($a, $b) {
                return $a['menu']['sequence'] <=> $b['menu']['sequence'];
            });
            view()->share(compact('serializeMenus','menuSubMenuRoutes','permissionDetails','subMenuActions'));
        }

        return $next($request);
    } 

    public function getAllFormRoutes(){
        return [
            '0' => 'task.chekDuplication',
            '1' => 'task.documentVerified',
            '2' => 'task.documentVerifiedChildSatge',
            '3' => 'task.sendQuotation',
            '4' => 'task.checkPayment',
            '5' => 'task.paymentStatus',
            '6' => 'task.patentSendQuotation',
            '7' => 'task.patentPaymentVerification',
            '8' => 'task.patentPriorArt',
            '9' => 'task.documentation',
            '10' => 'task.documenStatus',
            '11' => 'task.clientApproval',
            '12' => 'task.clientApprovalStatus', 
            '13' => 'task.draftApplication', 
            '14' => 'task.draftApplicationStatus', 
            '15' => 'leads.getLogs', 
            '16' => 'task.formalityCheck',
            '17' => 'task.formalityCheckStatus',
            '18' => 'task.patentSubmitPriorArt',

        ];
    }
}
