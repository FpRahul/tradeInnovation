<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('lead_task_details', function (Blueprint $table) {
             $table->text('comment')->nullable()->after('status_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_task_details', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
    }
};
