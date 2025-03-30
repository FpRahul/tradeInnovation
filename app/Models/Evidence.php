<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Lead;

class Evidence extends Model
{  
    //  OpponentDetails
    use HasFactory;

    // Table name is automatically inferred as 'payments', but you can explicitly define it
    protected $table = 'evidence';

    // Specify the fillable attributes for mass assignment
    protected $fillable = [
        'lead_id',
        'task_id',
        'opposition_number',
        'opponent_name',
        'advocate_name',
        'address',
        'opposition_date'
    ];
    
    public function lead(){
        return $this->belongsTo(Lead::class,'lead_id');
    }
   

}
