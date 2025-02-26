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
            $table->tinyInteger('status')->default(0)->comment('0 = New Register, 1 = Complete, 2 = Pending, 3 = Rejected, 4 = Hold');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_tasks', function (Blueprint $table) {
            $table->dropColumn('status'); // Drop the 'status' column
        });
    }
};
