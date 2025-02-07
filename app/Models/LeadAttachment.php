<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;

class LeadAttachment extends Model
{
   protected $fillable=[
    'lead_id',
    'document'
   ];

   public function lead(){
      return $this->belongsTo(Lead::class ,'lead_id');
   }
}
