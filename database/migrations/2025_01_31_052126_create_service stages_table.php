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
        Schema::create('service_stages', function (Blueprint $table) {
            $table->id();
            $table->integer('service_id')->default(0)->nullable(true);
            $table->string('title')->nullable(true);
            $table->string('description')->nullable(true);
            $table->integer('stage')->nullable(true)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
