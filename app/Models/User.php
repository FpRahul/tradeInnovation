<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserDetail;
use App\Models\RoleMenu;
use App\Models\Menu;
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
        if($user->role==1){
            return true;
        }
        $permissions = RoleMenu::where('roleId',$user->role)->with('menu')->get();
        echo "<pre>"; print_R($permissions);die;
        if(!$permissions && $permissions->isNotEmpty()){
            foreach($permissions as $permission){

            }
        }
        echo "<pre>"; print_R($permission);die;
        return in_array($routeName, $permissions);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function userdetail(){
        return $this->hasOne(UserDetail::class,'userId', 'id');
    }

    public function userexperience(){
        return $this->hasOne(UserExperience::class,'userId','id');
    }
}
