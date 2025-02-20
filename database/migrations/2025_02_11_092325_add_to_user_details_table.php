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
        Schema::table('user_details', function (Blueprint $table) {
            $table->string('curr_city')->nullable(true)->after('currentAddress');
            $table->string('curr_state')->nullable(true)->after('curr_city');
            $table->string('curr_zip')->nullable(true)->after('curr_state');

            $table->string('perma_city')->nullable(true)->after('permanentAddress');
            $table->string('perma_state')->nullable(true)->after('perma_city');
            $table->string('perma_zip')->nullable(true)->after('perma_state');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            //
        });
    }
};
