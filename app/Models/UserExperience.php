<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{
    protected $fillable = [
        'userId',
        'employerName',
        'startDate',
        'endDate',       
    ];
    public function user(){
        return $this->belongsTo(User::class, 'userId');
    }
}
