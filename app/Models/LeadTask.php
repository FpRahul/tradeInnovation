<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadTask extends Model
{
    protected $fillable=[
        'user_id',
        'lead_id',
        'assign_by',
        'task_title',
        'task_description'
    ];
}
