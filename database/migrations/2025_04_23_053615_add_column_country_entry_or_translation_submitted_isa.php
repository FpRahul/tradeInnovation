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
        Schema::table('service_details', function (Blueprint $table) {
            $table->string('filing_type')->nullable(true)->comment('national,pct international,pct national')->after('client_status');
            $table->string('country_entry')->nullable(true)->comment('receiving office & country of entry')->after('filing_type');
            $table->string('translation_submitted')->nullable(true)->after('country_entry');
            $table->string('international_searching')->nullable(true)->after('translation_submitted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_details', function (Blueprint $table) {
            //
        });
    }
};
