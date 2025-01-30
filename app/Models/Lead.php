<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LeadService;

class Lead extends Model
{
    protected $fillable = [
        'user_id',
        'source',
        'source_id',
        'client_name',
        'company_name',
        'mobile_number',
        'email',
        'assign_to',
        'description',
        'status',
        'completed_date',
        'quotation_sent',
        'quotation_sent_date'
    ];
    public function leadService(){
        return $this->hasMany(LeadService::class,'lead_id','id');
    }

    public function leadTasks()
    {
        return $this->hasMany(LeadTask::class, 'lead_id');
    }

}
