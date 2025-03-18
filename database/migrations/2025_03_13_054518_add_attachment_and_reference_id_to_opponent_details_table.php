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
        Schema::table('opponent_details', function (Blueprint $table) {
            $table->string('reference_id')->nullable()->after('lead_id'); 
            $table->string('attachment')->nullable()->after('status'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opponent_details', function (Blueprint $table) {
            $table->dropColumn('attachment');
            $table->dropColumn('reference_id');
        });
    }
};
