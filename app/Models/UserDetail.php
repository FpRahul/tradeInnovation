<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'userId',
        'incorporationType',
        'registered	',
        'referralPartner',        
    ];
}
