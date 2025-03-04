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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('lead_id'); 
            $table->unsignedBigInteger('task_id'); 
            $table->string('reference_id')->nullable(); 
            $table->decimal('service_price', 20,4)->nullable(); 
            $table->decimal('govt_price', 20,4)->nullable();
            $table->decimal('gst', 10, 2)->nullable(); 
            $table->decimal('total', 20,4)->nullable(); 
            $table->decimal('pending_amount', 20,4)->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
