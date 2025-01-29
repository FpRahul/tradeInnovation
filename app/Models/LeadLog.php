<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadLog extends Model
{
    protected $fillable=[
        'lead_id',
        'user_id',
        'task_is',
        'assign_by',
        'description',        
    ];
}
