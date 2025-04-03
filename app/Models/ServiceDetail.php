<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceDetail extends Model
{
    protected $fillable = [
        'lead_id',
        'task_id',
        'service_id',
        'journal_number',
        'class_rule',
        'applied_for',
        'application_number',
        'service_logo',
        'filing_mode',
        'filing_date',
        'applicant_name',
        'inventor_name',
        'title_of_invention',
        'category_of_invention',
        'type_of_application',
        'status',
        'client_status'
    ];
}
