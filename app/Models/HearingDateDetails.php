<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HearingDateDetails extends Model
{
    protected $fillable = [
        'task_id',
        'lead_id',
        'reason',
        'hearing_date',
        'count',
    ];
}
