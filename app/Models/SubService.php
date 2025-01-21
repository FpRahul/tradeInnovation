<?php

namespace App\Models;
use App\Models\Service;

use Illuminate\Database\Eloquent\Model;

class SubService extends Model
{
    protected $fillable = [
        'serviceId',
        'subServiceName',
        'subServiceDescription'
    ];

    public function service(){
        return $this->belongsTo(Service::class, 'serviceId');
    }
}
