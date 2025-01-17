<?php

namespace App\Models;
use App\Models\Lead;

use Illuminate\Database\Eloquent\Model;

class LeadService extends Model
{
   protected $fillable = [
        'lead_id',
        'service_id',
        'subservice_id',
        'quoted_price'
   ];

   public function lead(){
      return $this->belongsTo(Lead::class,'lead_id');
   }
}
