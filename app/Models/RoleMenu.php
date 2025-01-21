<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    protected $fillable = [
        'roleId',
        'menuId',
        'permission'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'roleId', 'id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'roleId', 'id');
    }
    
}
