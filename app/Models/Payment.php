<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Table name is automatically inferred as 'payments', but you can explicitly define it
    protected $table = 'payments';

    // Specify the fillable attributes for mass assignment
    protected $fillable = [
        'lead_id',
        'task_id',
        'service_price',
        'govt_price',
        'gst',
        'total',
        'pending_amount',
        'reference_id',
        'submitted_amount'
    ];
}
