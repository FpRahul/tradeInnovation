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
            $table->unsignedBigInteger('service_detail_id')->default(0)->after('lead_id');

            // Now add the foreign key constraint
            $table->foreign('service_detail_id')
                ->references('id')
                ->on('service_details') 
                ->onDelete('cascade');  
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
