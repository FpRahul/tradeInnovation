<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('service_stages', function (Blueprint $table) {
            $table->integer('stage_id')->nullable()->after('service_id');
            $table->integer('sub_stage_id')->nullable()->after('title');
        });
    }

    
    public function down(): void
    {
        Schema::table('service_stages', function (Blueprint $table) {
            //
        });
    }
};
