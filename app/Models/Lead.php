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

    public function leadTask()
    {
        return $this->hasOne(LeadTask::class, 'lead_id')->latest();
    }

    public function lead_logs()
    {
        return $this->hasMany(LeadLog::class, 'lead_id', 'id');
    }

}
