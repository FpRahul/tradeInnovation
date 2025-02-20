<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {  
        Schema::dropIfExists('follow_ups');
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('task_id')->nullable(); // task_id as unsignedBigInteger (reference to tasks table)
            $table->unsignedBigInteger('lead_id')->nullable(); // lead_id as unsignedBigInteger (reference to leads table)
            $table->unsignedBigInteger('service_id')->nullable(); // service_id as unsignedBigInteger (reference to services table)
            $table->integer('stage_id'); // stage_id as an integer
            $table->string('title'); // title as a varchar field
            $table->integer('sub_stage_id')->nullable(); // sub_stage_id as an integer, default to null
            $table->tinyInteger('status')->default(0)->comment('0 = Inactive, 1 = Active'); // status as tinyint with comment
            $table->timestamps(); // created_at and updated_at columns
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
