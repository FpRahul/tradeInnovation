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
        Schema::table('lead_tasks', function (Blueprint $table) {
           $table->string('class_rule')->nullable(true)->after('sub_stage_id');
           $table->string('applied_for')->nullable(true)->after('class_rule');
           $table->string('service_logo')->nullable(true)->after('applied_for');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_tasks', function (Blueprint $table) {
            //
        });
    }
};
