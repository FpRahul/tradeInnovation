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
    'status'
  ];
}
