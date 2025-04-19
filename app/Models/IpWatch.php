<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpWatch extends Model
{
    protected $fillable = [
        'lead_id',
        'task_id',
        'service_details_id',
        'service_id',
        'frequency_status',
    ];
}
