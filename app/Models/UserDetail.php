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
        'curr_city',
        'curr_state',
        'curr_zip',
        'permanentAddress',
        'perma_city',
        'perma_state',
        'perma_zip',
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


