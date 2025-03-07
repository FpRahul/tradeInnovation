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
        Schema::create('hearing_date_details', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('task_id'); 
            $table->unsignedBigInteger('lead_id'); 
            $table->text('reason')->nullable(); 
            $table->date('hearing_date');
            $table->integer('count'); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hearing_date_details');
    }
};
