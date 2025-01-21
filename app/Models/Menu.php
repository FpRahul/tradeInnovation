<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'roleId',
        'menuName'
    ];

    public function getRoleMenu(){
        return $this->hasOne(RoleMenu::class,'menuId', 'id');
    }
}
