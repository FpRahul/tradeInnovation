<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LeadService;
use App\Models\LeadAttachment;

class Lead extends Model
{
    protected $fillable = [
        'user_id',
        'lead_id',
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
        'quotation_sent_date',
        'msmem',
        'business_scope',
        'firm'
    ];
    public function leadService(){
        return $this->hasMany(LeadService::class,'lead_id','id');
    }

    public function leadTasks(){
        return $this->hasMany(LeadTask::class, 'lead_id');
    }

    public function leadTask(){
        return $this->hasOne(LeadTask::class, 'lead_id')->latest();
    }

    public function lead_logs(){
        return $this->hasMany(LeadLog::class, 'lead_id', 'id');
    }

    public function leadAttachments(){
        return $this->hasMany(LeadAttachment::class, 'lead_id','id');
    }

    public function categoryOptions(){
        return $this->hasOne(CategoryOption::class, 'id','source');
    }
    public function referral(){
        return $this->belongsTo(User::class, 'source_id');
    }
}
