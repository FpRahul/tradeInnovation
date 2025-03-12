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
            $table->string('status')->default(0)->comment('0:  for Opponent  , 1: for Applicant')->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opponent_details', function (Blueprint $table) {
            //
        });
    }
};
