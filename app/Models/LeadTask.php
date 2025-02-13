<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class LeadTask extends Model
{
    protected $fillable=[
        'user_id',
        'lead_id',
        'assign_by',
        'task_title',
        'task_description',
        'service_id',
        'subservice_id', 
        'quoted_price'
    ]; 

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function lead(){
        return $this->belongsTo(Lead::class,'lead_id');
    }
    
    public function leadTaskDetails()
    {
        return $this->hasOne(LeadTaskDetail::class, 'task_id', 'id');
    }
    public function serviceSatge()
    {
        return $this->belongsTo(ServiceStages::class, 'service_stage_id');
    }
}
