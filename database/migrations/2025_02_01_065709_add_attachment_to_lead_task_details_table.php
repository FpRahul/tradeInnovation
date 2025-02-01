<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;



return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lead_task_details', function (Blueprint $table) {
            $table->json('attachment')->nullable();
        });
        DB::statement('ALTER TABLE lead_task_details MODIFY COLUMN attachment JSON AFTER `updated_at`');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_task_details', function (Blueprint $table) {
            $table->dropColumn('attachment');
        });
    }
};
