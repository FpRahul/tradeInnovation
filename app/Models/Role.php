<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name'
    ];

    public function roleMenus()
    {
        return $this->hasMany(RoleMenu::class, 'roleId', 'id');
    }

}
