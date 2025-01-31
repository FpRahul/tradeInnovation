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
                0 => 'user.logout',
                1 => 'user.myprofile',
                2 => 'chart.data',
                3 => 'dashboard',
                4 => 'serviceStages',
                5 => 'lead.subservice'
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

            if (!$permissionDetails['status']) {
                abort(403, 'You do not have permission to access this page.');
            }
            $systemMenus = Menu::whereIn('id',$permissionDetails['menuId'])->get();
        }
        
        $serializeMenus = [];
        $menuSubMenuRoutes = [];
        if($systemMenus->isNotEmpty()){
            foreach($systemMenus as $k =>$v){
                if($v->parentId==0){
                    $serializeMenus[$v->id]['menu']['name'] = $v->menuName;
                    $serializeMenus[$v->id]['menu']['url'] = $v->url;
                    $serializeMenus[$v->id]['menu']['icon'] = $v->icon;
                    $serializeMenus[$v->id]['menu']['groupedRoutes'] = $v->actionRoutes;
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
            
            view()->share(compact('serializeMenus','menuSubMenuRoutes','permissionDetails'));
        }

        return $next($request);
    }
}
