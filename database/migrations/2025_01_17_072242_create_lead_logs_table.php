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
        Schema::create('lead_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('leadId')->default(0);
            $table->integer('userId')->default(0);
            $table->integer('status')->default(0);
            $table->tinyInteger('isFollowUp')->default(0);
            $table->datetime('followUpDate')->nullable(true);
            $table->datetime('verfiedOn')->nullable(true);
            $table->datetime('deadLine')->nullable(true);
            $table->text('description')->nullable(true);            
            $table->datetime('actionDate')->nullable(true);
            $table->string('objectReason')->nullable(true);
            $table->datetime('hearingDate')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_logs');
    }
};
