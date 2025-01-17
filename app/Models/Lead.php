<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'userId',
        'source',
        'sourceId',
        'clientName',
        'companyName',
        'mobileNumber',
        'email',
        'assignTo',
        'description',
        'status',
        'completedDate',
        'quotationSent',
        'quotationSentDate'
    ];
}
