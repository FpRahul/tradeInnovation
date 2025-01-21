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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('source')->default(0);
            $table->integer('source_id')->default(0);
            $table->string('client_name')->nullable(true);
            $table->string('company_name')->nullable(true);
            $table->string('mobile_number')->nullable(true);
            $table->string('email')->nullable(true);
            $table->integer('assign_to')->nullable(true);
            $table->text('description')->nullable(true);
            $table->tinyInteger('status')->default(0);            
            $table->datetime('completed_date')->nullable(true);
            $table->tinyInteger('quotation_sent')->default(0);  
            $table->datetime('quotation_sent_date')->nullable(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
