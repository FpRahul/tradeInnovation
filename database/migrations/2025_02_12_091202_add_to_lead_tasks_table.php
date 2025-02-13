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
            $table->integer('service_id')->default(0)->after('lead_id');
            $table->integer('subservice_id')->default(0)->after('service_id');;
            $table->string('quoted_price')->nullable(true)->after('task_description');
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
