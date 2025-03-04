<?php
namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\RoleMenu; 
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function roles(Request $request){ 
        $allRoles = Role::where('id', '!=', '1')->with('roleMenus')->get();
        $searchKey = $request->input('key') ?? '';
        $requestType = $request->input('requestType') ?? '';
        if ($searchKey) {
            $allRoles = Role::where('id', '!=', '1')  
            ->where('name', 'LIKE', '%' . $searchKey . '%') 
            ->with('roleMenus')  
            ->get();
        } 
        if(empty($requestType)) {
            $header_title_name = 'User';
            $allRoles = Role::where('id', '!=', '1')->with('roleMenus')->get();
            return view('settings/roles', compact('allRoles','header_title_name', 'searchKey'));
        }else{
            $trData = view('settings/role-listing-data',compact('allRoles', 'searchKey'))->render();
            $dataArray = [
                'trData' => $trData,
            ];
            return response()->json($dataArray);
        }
    }
    public function addRole(Request $request, $id = null){
        $header_title_name="Setting";
        $menuAddedAction = [];
        if($id>0){
            $submitButton = "Update";
            $roleData = Role::with('roleMenus')->find($id);
            foreach($roleData->roleMenus as $menu){
                $vaerifSubs = Menu::find($menu->menuId);
                if($vaerifSubs->parentId>0){
                    $menuAddedAction[$vaerifSubs->parentId] = [];
                }
                $menuAddedAction[$menu->menuId] = explode(',',$menu->permission);
            }
        }else{
            $submitButton = "Create";
            $roleData = new Role();
        }
        if($request->isMethod('post')){
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
                    foreach ($roleMenuPermission as $rolePermission) {
                        if ($rolePermission['permission'] !== null) {
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
                        }else{
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
        return view('settings/add-role',compact('header_title_name','submitButton','roleData','menuAddedAction'));
    }

    public function viewMenu(){
        $header_title_name = 'Assign Menu';
        $data = Menu::select('id', 'parentId', 'menuName', 'icon', 'url', 'actionRoutes')->get();   
        return view('settings.assign_menue',compact('header_title_name','data'));
    }

    public function getMenu(Request $request){
        $menuParentID = $request->menuName ?? 0;
        $rule = [
            'name' => 'required',
            'icon' => 'nullable',
            'url' => 'required',
            'action' => 'required',
        ];

        $validator = Validator::make($request->all(), $rule);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $menu = new Menu();
        $menu->parentId = $menuParentID;
        $menu->menuName = $request->name;
        $menu->icon = $request->icon;
        $menu->url = $request->url;
        $menu->actionRoutes = $request->action;
        if ($menu) {
            $menu->save();
            return response()->json(['message' => 'success' ,  'status' => 200]);
        }else{
            return response()->json(['message' => 'error' ,  'status' => 400]);
        }
    }
}
