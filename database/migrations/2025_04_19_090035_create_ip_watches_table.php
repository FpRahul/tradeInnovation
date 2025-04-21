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
        Schema::create('ip_watches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->nullable(true);
            $table->unsignedBigInteger('task_id')->nullable(true);
            $table->unsignedBigInteger('service_details_id')->nullable(true);
            $table->unsignedBigInteger('service_id')->nullable(true);
            $table->tinyInteger('frequency_status')
                  ->comment('1 = Weekly, 2 = Monthly, 3 = Quarterly');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_watches');
    }
};
