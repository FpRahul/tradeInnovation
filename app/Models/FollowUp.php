<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
   protected $fillable = [
      'task_id', 
      'service_id', 
      'stage_id', 
      'url', 
      'route'
  ];
}
