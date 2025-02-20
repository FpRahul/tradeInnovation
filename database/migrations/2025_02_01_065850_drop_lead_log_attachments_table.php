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
        Schema::dropIfExists('lead_log_attachments');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('lead_log_attachments', function ($table) {
            $table->id();
            $table->timestamps();
            // Add any other columns that the table originally had
        });
    }
};
