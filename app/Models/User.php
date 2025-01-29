<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserDetail;
use App\Models\RoleMenu;
use App\Models\MenuAction;
use App\Models\Menu;
use App\Models\LeadAssign;

use Illuminate\Support\Facades\Auth;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'mobile',
        'companyName',
        'address',
        'archive',
        'altNumber',
        'altEmail',
        'communicationAdress'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasPermission($routeName)
    {
        return true;
        $user = Auth::user();
        $returnMenus = [
            'status' => false,
            'permission' => [1]
        ];
        if($user->role==1){
            $returnMenus['status'] = true;
            return $returnMenus;
        }

        $permissions = RoleMenu::where('roleId',$user->role)->get();
        echo "<pre>"; print_R($permissions->toArray());die;
        $accessableRoutes = [];
        if($permissions->isNotEmpty()){
            foreach($permissions as $permission){
                $menuDetail = Menu::find($permission->menuId);
                $availableActions = explode(',',$permission->permission);
                $menuActions = MenuAction::whereIn('id',$availableActions)->get();
                if($menuActions->isNotEmpty()){
                    foreach($menuActions as $menuAction){
                        $accessableRoutes[] = $menuAction->route;
                    }
                    $accessableRoutes[] = $menuDetail->url;
                    $returnMenus['permission'][$menuDetail->id] = $menuDetail->id;
                    if($menuDetail->parentId>0){
                        $returnMenus['permission'][$menuDetail->parentId] = $menuDetail->parentId;
                    }
                }else{
                    $accessableRoutes[] = $menuDetail->url;
                    $returnMenus['permission'][$permission->menuId] = $permission->menuId;
                }
            }
        }
        echo "<pre>"; print_R($returnMenus);die;
        //mandatory routes
        $accessableRoutes[] = 'user.logout';
        $accessableRoutes[] = 'user.myprofile';
        $accessableRoutes[] = 'chart.data';
        $accessableRoutes[] = 'dashboard';
        //end
        
        if(in_array($routeName, $accessableRoutes)){
            $returnMenus['status'] = true;
        }
        return $returnMenus;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function userdetail(){
        return $this->hasOne(UserDetail::class,'userId', 'id');
    }

    public function userLogs(){
        return $this->hasOne(Log::class,'user_id', 'id');
    }

    public function userexperience(){
        return $this->hasOne(UserExperience::class,'userId','id');
    }

    // public function assignLead(){
    //     return $this->hasMany(LeadAssign::class,'user_id','id');
    // }
}
