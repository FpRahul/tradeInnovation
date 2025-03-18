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
        Schema::table('lead_task_details', function (Blueprint $table) {
            $table->integer('status')->nullable(true)->comment('0 for pending , 1 for completed , 2 for hold , 3 for follow-up ,  4 for rejected')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_task_details', function (Blueprint $table) {
            //
        });
    }
};
