<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'child_id',
        'is_move',
        'description',
    ];
}
