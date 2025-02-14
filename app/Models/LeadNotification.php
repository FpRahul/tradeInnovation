<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LeadTask;
class LeadNotification extends Model
{
    protected $fillable = [
        'user_id',
        'lead_id',
        'description',
        'status',
    ];

    public function taskDeatials(){
        return $this->belongsTo(LeadTask::class , 'task_id');
    }
}
