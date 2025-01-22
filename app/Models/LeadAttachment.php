<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadAttachment extends Model
{
   protected $fillable=[
    'lead_id',
    'document'
   ];
}
