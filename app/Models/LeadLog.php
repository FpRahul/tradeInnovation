<?php
namespace App\Models;
use App\Models\LeadTask;
use App\Models\LeadService;
use App\Models\LeadAttachment;

use Illuminate\Database\Eloquent\Model;
class LeadLog extends Model
{
    protected $fillable=[
        'lead_id',
        'user_id',
        'task_is',
        'assign_by',
        'description',        
    ];
    public function leadTask(){
        return $this->belongsTo(LeadTask::class, 'task_id');
    }
    public function leadService()
    {
        return $this->hasMany(LeadService::class, 'lead_id', 'lead_id');
    }
    public function lead(){
        return $this->belongsTo(Lead::class, 'lead_id' , 'id');
    }
    public function leadAttch(){
        return $this->belongsTo(LeadAttachment::class, 'lead_id');
    }
}
