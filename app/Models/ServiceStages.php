<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\LeadTask;

class ServiceStages extends Model
{
    use HasFactory, Notifiable;

   protected $fillable=[
    'service_id',
    'title',
    'description',
    'stage'
   ];

   public function task(){
    return $this->belongsTo(LeadTask::class, 'id', 'service_stage_id');
   }
}
