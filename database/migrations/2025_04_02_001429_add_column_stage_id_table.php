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
        Schema::table('hearing_date_details', function (Blueprint $table) {
            $table->integer('stage_id')->nullable()->after('lead_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hearing_date_details', function (Blueprint $table) {
            //
        });
    }
};
