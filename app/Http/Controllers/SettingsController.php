<?php
namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\RoleMenu; 
use App\Models\Menu;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function roles(Request $request)
{ 
    $search =  $request->data ?? '';
    
    if ($search) {
        $allRoles = Role::where('id', '!=', '1')  
        ->where('name', 'LIKE',  $search . '%')  
        ->with('roleMenus')  
        ->get();
    } else {
        $allRoles = Role::where('id', '!=', '1')->with('roleMenus')->get();
    }
    $header_title_name = 'Role';
    if (empty($search)) {
        
        return view('settings/roles', compact('allRoles','header_title_name'));
    } else {
        
        $trData = view('settings/role-listing-data', compact('allRoles'))->render();
        return response()->json([
            'trData' => $trData,
        ]);
    }
}

   
    public function addRole(Request $request, $id = null){
        // dd($request->all());
        $header_title_name="Setting";
        $menuAddedAction = [];
        if($id>0){
            $roleData = Role::with('roleMenus')->find($id);
            // dd($roleData);
            foreach($roleData->roleMenus as $menu){
                $vaerifSubs = Menu::find($menu->menuId);
                // dd($vaerifSubs);
                if($vaerifSubs->parentId>0){
                    $menuAddedAction[$vaerifSubs->parentId] = [];
                    // dd($menuAddedAction[$vaerifSubs->parentId]);
                }
                $menuAddedAction[$menu->menuId] = explode(',',$menu->permission);
                // dd($menuAddedAction[$menu->menuId]);
            }
        }else{
            $roleData = new Role();
        }
        
        if($request->isMethod('post')){
            // $credentials = $request->validate([
            //     'rolename' => 'required|unique:roles,name'
            // ]);
            //Saving Role
            $name = $request->rolename;
            if(!$name){
               $name =  $request->name;
            }else{
                $name = $request->rolename;
            }
          
            $roleData->name = $name;
            $roleData->save();

            //Saving Role Permission
            $allSavedPermissions = $request->permission;
            
            $roleMenuPermission = [];
            $recordCounter = 0;
            if(!empty($allSavedPermissions)){
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
                                        // dd($allActions);

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
                            $roleMenuPermission[$recordCounter]['roleId'] = $roleData->id;
                            $roleMenuPermission[$recordCounter]['menuId'] = $menuId;
                            $roleMenuPermission[$recordCounter]['permission'] = NULL;
                            $recordCounter++; 
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
            }else{
                $roleMenuPermission[0]['roleId'] = $roleData->id;
                $roleMenuPermission[0]['menuId'] = 1;
                $roleMenuPermission[0]['permission'] = NULL;
            }
            if($id>0){
                $updatedIds = [];
                
                if(!empty($roleMenuPermission)){
                    // dd($roleMenuPermission);    
                    foreach ($roleMenuPermission as $rolePermission) {
                        if ($rolePermission['permission'] !== null) {
                            // dd($rolePermission['permission']);
                            $oldPermission = RoleMenu::where('roleId', $rolePermission['roleId'])
                                ->where('menuId', $rolePermission['menuId'])
                                ->first();
                            if (!$oldPermission) {
                                $newEntry = RoleMenu::create([
                                    'roleId' => $rolePermission['roleId'],
                                    'menuId' => $rolePermission['menuId'],
                                    'permission' => $rolePermission['permission'],
                                ]);
                                $updatedIds[] = $newEntry->id;
                            } else {
                                $oldPermission->permission = $rolePermission['permission'];
                                $oldPermission->save();
                                $updatedIds[] = $oldPermission->id;
                            }
                        }

                        else{
                            
                            if ($rolePermission['permission'] == null && $rolePermission['menuId'] == 1) {
                                $oldPermission = RoleMenu::where('roleId', $rolePermission['roleId'])
                                ->first();
                                $oldPermission->delete();
                            }

                        }
                    }
                    
                }

                if(!empty($updatedIds)){
                    RoleMenu::whereNotIn('id', $updatedIds)->where('roleId',$id)->delete();
                }
                return redirect()->route('settings.roles')->with('success','Permission updated.');
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
        return view('settings/add-role',compact('header_title_name','roleData','menuAddedAction'));
    }
}
