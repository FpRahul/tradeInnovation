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
            $table->integer('userId')->default(0);
            $table->tinyInteger('source')->default(0);
            $table->integer('sourceId')->default(0);
            $table->string('clientName')->nullable(true);
            $table->string('companyName')->nullable(true);
            $table->string('mobileNumber')->nullable(true);
            $table->string('email')->nullable(true);
            $table->tinyInteger('assignTo')->nullable(true);
            $table->text('description')->nullable(true);
            $table->tinyInteger('status')->default(0);            
            $table->datetime('completedDate')->nullable(true);
            $table->tinyInteger('quotationSent')->default(0);  
            $table->datetime('quotationSentDate')->nullable(true);

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
