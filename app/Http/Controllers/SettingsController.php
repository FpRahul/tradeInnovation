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
        $allRoles = Role::get();
        $moduleName="Manage Roles";
        return view('settings/roles',compact('allRoles','header_title_name','moduleName'));
    }

    public function addRole(Request $request, $id = null){
        $header_title_name="Setting";
        $moduleName="Create Role";
        if($id>0){
            $roleData = Role::find($id);
        }else{
            $roleData = new Role();
        }
        if($request->isMethod('post')){
            $credentials = $request->validate([
                'rolename' => 'required|unique:roles,name'
            ]);
            echo "<pre>"; print_R($request->all());die;
            //Saving Role
            $roleData->name = $request->rolename;
            $roleData->save();

            //Saving Role Permission
            $allSavedPermissions = $request->permission;
            $roleMenuPermission = [];
            $recordCounter = 0;
            foreach($allSavedPermissions as $menuId => $menuItems){
                if($menuItems=='on'){
                    $roleMenuPermission[$recordCounter]['roleId'] = $roleData->id;
                    $roleMenuPermission[$recordCounter]['menuId'] = $menuId;
                    $roleMenuPermission[$recordCounter]['permission'] = NULL;
                    $recordCounter++;
                }
                foreach($menuItems as $subMenuId => $subMenuItems){
                    if($subMenuId=='menu'){
                        $allActions = [];
                        foreach($subMenuItems as $actionId => $subMenuItems){
                            $allActions[] = $actionId;
                        }
                        $roleMenuPermission[$recordCounter]['roleId'] = $roleData->id;
                        $roleMenuPermission[$recordCounter]['menuId'] = $menuId;
                        $roleMenuPermission[$recordCounter]['permission'] = implode(',',$allActions);
                        $recordCounter++;
                    }else{
                        $allActions = [];
                        if($subMenuItems!='on'){
                            foreach($subMenuItems as $actionId => $subMenuItems){
                                $allActions[] = $actionId;
                            }
                        }
                        if($subMenuId!='menu'){
                            $roleMenuPermission = new RoleMenu();
                            $roleMenuPermission->roleId = $roleData->id;
                            $roleMenuPermission->menuId = $subMenuId;
                            $roleMenuPermission->permission = implode(',',$allActions);
                            $roleMenuPermission->save();
                        }
                    }
                }
            }
            return redirect()->back()->withSuccess('Permission updated successfully.');
        }
        return view('settings/add-role',compact('header_title_name','moduleName','roleData'));
    }
}
