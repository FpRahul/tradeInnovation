<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserDetail extends Model
{
    protected $fillable = [
        'userId',
        'fatherHusbandName',
        'qualification',
        'skills',
        'keyResponsibilityArea',
        'keyPerformanceIndicator',
        'emergencyContactDetails',
        'currentAddress',
        'permanentAddress',
        'uploadPhotograph',
        'uploadPan',
        'uploadAadhar',
        'uploadDrivingLicence',
        'incorporationType',
        'registered	',
        'referralPartner',        
    ];

    public function user(){
        return $this->belongsTo(User::class, 'userId');
    }
}


