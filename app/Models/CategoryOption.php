<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryOption extends Model
{
    protected $fillable = [
        'authId',
        'type',
        'name',
        'status'       
    ];
}
