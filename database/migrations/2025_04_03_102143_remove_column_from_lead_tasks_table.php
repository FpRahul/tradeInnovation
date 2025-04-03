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
            $table->dropColumn(['class_rule']);
            $table->dropColumn(['applied_for']);
            $table->dropColumn(['application_number']);
            $table->dropColumn(['service_logo']);
            $table->dropColumn(['filing_mode']);
            $table->dropColumn(['filing_date']);
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
