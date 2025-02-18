<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LeadNotification;

class LeadTaskDetail extends Model
{
   protected $fillable = [
    'task_id',
    'dead_line',
    'status',
    'status_date'
   ];

   public function leadTask()
    {
        return $this->belongsTo(LeadTask::class, 'task_id', 'id');
    }

    public function leadNotifications()
    {
        return $this->hasMany(LeadNotification::class, 'task_id', 'task_id');
    }
}
