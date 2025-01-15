<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubService extends Model
{
    protected $fillable = [
        'serviceId',
        'subServiceName',
        'subServiceDescription'
    ];
}
