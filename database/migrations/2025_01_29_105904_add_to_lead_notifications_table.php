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
        Schema::table('lead_notifications', function (Blueprint $table) {
            $table->integer('task_id')->nullable()->constrained('lead_tasks')->onDelete('cascade')->after('lead_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_notifications', function (Blueprint $table) {
            //
        });
    }
};
