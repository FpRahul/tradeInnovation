<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserDetail extends Model
{
    protected $fillable = [
        'userId',
        'incorporationType',
        'registered	',
        'referralPartner',        
    ];

    public function user(){
        return $this->belongsTo(User::class, 'userId');
    }
}


