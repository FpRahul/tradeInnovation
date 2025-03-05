<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
  protected $fillable=[
    'name',
    'city',
    'state',
    'zipcode',
    'status',
    'acc_holder_name',
    'bank_name',
    'branch_name',
    'account_number',
    'ifsc_code',
    'swift_code',
    'upi_id',
  ];
}
