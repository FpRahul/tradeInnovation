<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class LeadAssign extends Model
{
    protected $fillable=[
        'user_id',
        'lead_id',
        'description',
        'dead_line'
    ];

    public function userDetails(){
        return $this->belongs(User::class,'id','user_id');
    }
}
