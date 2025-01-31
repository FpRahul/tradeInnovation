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
        'task_description'
    ]; 

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function lead(){
        return $this->belongsTo(Lead::class,'lead_id');
    }

    public function leadAssign()
    {
        return $this->belongsTo(LeadAssign::class, 'lead_id');  
    }

    public function leadServices()
    {
        return $this->hasMany(LeadService::class, 'lead_id', 'lead_id'); 
    }
       
    public function leadTaskDetails()
    {
        return $this->hasOne(LeadTaskDetail::class, 'task_id', 'id');
    }
}
