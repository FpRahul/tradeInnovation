<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadLog extends Model
{
    protected $fillable=[
        'lead_id',
        'user_id',
        'status',
        'is_follow_up',
        'follow_up_date',
        'verfied_on',
        'dead_line',
        'description',
        'action_date',
        'object_reason',
        'hearing_date'
    ];
}
