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
        Schema::create('lead_task_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->nullable()->constrained('lead_tasks')->onDelete('cascade');
            $table->date('dead_line')->nullable(true);
            $table->integer('status')->nullable(true)->comment('0 for pending , 1 for completed , 2 for hold , 3 for follow-up ,  4 for rejected');
            $table->date('status_date')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_task_details');
    }
};
