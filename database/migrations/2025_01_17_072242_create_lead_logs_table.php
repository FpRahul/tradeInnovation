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
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('status')->default(0);
            $table->tinyInteger('is_follow_up')->default(0);
            $table->datetime('follow_up_date')->nullable(true);
            $table->datetime('verfied_on')->nullable(true);
            $table->datetime('dead_line')->nullable(true);
            $table->text('description')->nullable(true);            
            $table->datetime('action_date')->nullable(true);
            $table->string('object_reason')->nullable(true);
            $table->datetime('hearing_date')->nullable(true);
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
