<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadLogAttachment extends Model
{
    protected $fillable = [
        'lead_id',
        'user_id',
        'file_name'        
    ];
}
