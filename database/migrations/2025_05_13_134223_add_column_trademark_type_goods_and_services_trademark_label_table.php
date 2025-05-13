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
            $table->tinyInteger('trademark_type')
                ->nullable()
                ->comment('1: Wordmark, 2: Logomark, 3: Logo and Label')->after('applied_for');
          $table->string('trademark_service_label')->nullable()->after('trademark_type');
          $table->text('trademark_goods')->nullable()->after('trademark_service_label');
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
