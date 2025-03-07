<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('opponent_details', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('task_id'); 
            $table->unsignedBigInteger('lead_id'); 
            $table->string('opposition_number'); 
            $table->string('opponent_name');
            $table->string('advocate_name'); 
            $table->string('address'); 
            $table->date('opposition_date'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opponent_details');
    }
};
