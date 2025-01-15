<?php

namespace App\Models;
use App\Models\SubService;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'serviceName',
        'status',
        'serviceDescription'
    ];

    public function subService(){
        return $this->hasMany(SubService::class,'serviceId', 'id');
    }
}
