<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
