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
        Schema::table('lead_logs', function (Blueprint $table) {
            $table->text('remark')->nullable()->after('assign_by');
            $table->json('old_value')->nullable()->after('remark');
            $table->json('new_value')->nullable()->after('old_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_logs', function (Blueprint $table) {
            //
        });
    }
};
