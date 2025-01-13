<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\RoleMenu;
use App\Models\Menu;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function roles(){
        $header_title_name="Setting";
        $allRoles = Role::with('roleMenus')->get();
        $moduleName="Manage Roles";
        return view('settings/roles',compact('allRoles','header_title_name','moduleName'));
    }

    public function addRole(Request $request, $id = null){
        $header_title_name="Setting";
        $moduleName="Create Role";
        $menuAddedAction = [];
        if($id>0){
            $roleData = Role::with('roleMenus')->find($id);
            foreach($roleData->roleMenus as $menu){
                $vaerifSubs = Menu::find($menu->menuId);
                if($vaerifSubs->parentId>0){
                    $menuAddedAction[$vaerifSubs->parentId] = [];
                }
                $menuAddedAction[$menu->menuId] = explode(',',$menu->permission);
            }
        }else{
            $roleData = new Role();
        }
        
        if($request->isMethod('post')){
            $credentials = $request->validate([
                'rolename' => 'required|unique:roles,name'
            ]);
            //Saving Role
            $roleData->name = $request->rolename;
            $roleData->save();

            //Saving Role Permission
            $allSavedPermissions = $request->permission;
            $roleMenuPermission = [];
            $recordCounter = 0;
            foreach($allSavedPermissions['mainMenu'] as $menuId => $menuItems){
                if($menuItems=='on'){
                    $roleMenuPermission[$recordCounter]['roleId'] = $roleData->id;
                    $roleMenuPermission[$recordCounter]['menuId'] = $menuId;
                    $roleMenuPermission[$recordCounter]['permission'] = NULL;
                    $recordCounter++;
                }else{
                    if(isset($menuItems['subMenu'])){
                        foreach($menuItems['subMenu'] as $subMenuId => $subMenuItems){
                            if($subMenuItems=='on'){
                                $roleMenuPermission[$recordCounter]['roleId'] = $roleData->id;
                                $roleMenuPermission[$recordCounter]['menuId'] = $subMenuId;
                                $roleMenuPermission[$recordCounter]['permission'] = NULL;
                                $recordCounter++;
                            }else{
                                if(isset($subMenuItems['action'])){
                                    $allActions = [];
                                    foreach($subMenuItems['action'] as $actionId => $actionVal){
                                        $allActions[] = $actionId;
                                    }
                                    $roleMenuPermission[$recordCounter]['roleId'] = $roleData->id;
                                    $roleMenuPermission[$recordCounter]['menuId'] = $subMenuId;
                                    $roleMenuPermission[$recordCounter]['permission'] =implode(',',$allActions);
                                    $recordCounter++; 
                                }else{
                                    if(isset($subMenuItems['subSubMenu'])){
                                        foreach($subMenuItems['subSubMenu'] as $subSubMenuId => $subSubMenuItems){
                                            if(isset($subSubMenuItems['action'])){
                                                $allActions = [];
                                                foreach($subSubMenuItems['action'] as $actionId => $actionVal){
                                                    $allActions[] = $actionId;
                                                }
                                                $roleMenuPermission[$recordCounter]['roleId'] = $roleData->id;
                                                $roleMenuPermission[$recordCounter]['menuId'] = $subSubMenuId;
                                                $roleMenuPermission[$recordCounter]['permission'] =implode(',',$allActions);
                                                $recordCounter++; 
                                            }else{
                                                $roleMenuPermission[$recordCounter]['roleId'] = $roleData->id;
                                                $roleMenuPermission[$recordCounter]['menuId'] = $subSubMenuId;
                                                $roleMenuPermission[$recordCounter]['permission'] = NULL;
                                                $recordCounter++; 
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }else{
                        if(isset($menuItems['action'])){
                            $allActions = [];
                            foreach($menuItems['action'] as $actionId => $actionVal){
                                $allActions[] = $actionId;
                            }
                            $roleMenuPermission[$recordCounter]['roleId'] = $roleData->id;
                            $roleMenuPermission[$recordCounter]['menuId'] = $menuId;
                            $roleMenuPermission[$recordCounter]['permission'] =implode(',',$allActions);
                            $recordCounter++; 
                        }
                    }
                }
            }

            if($id>0){
                echo "<pre>"; print_R($roleMenuPermission);die;
            }else{
                if(!empty($roleMenuPermission)){
                    foreach($roleMenuPermission as $rolePermission){
                        $newEntry = new RoleMenu();
                        $newEntry->roleId = $rolePermission['roleId'];
                        $newEntry->menuId = $rolePermission['menuId'];
                        $newEntry->permission = $rolePermission['permission'];
                        $newEntry->save();
                    }
                    return redirect()->route('settings.roles')->with('success','Permission added.');
                }else{
                    return redirect()->route('settings.roles')->with('error','Invalid permission request.');
                }
            }
            
            return redirect()->back()->withSuccess('Permission updated successfully.');
        }
        return view('settings/add-role',compact('header_title_name','moduleName','roleData','menuAddedAction'));
    }
}
