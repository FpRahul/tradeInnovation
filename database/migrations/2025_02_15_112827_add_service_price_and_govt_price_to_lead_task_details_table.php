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
        Schema::table('lead_task_details', function (Blueprint $table) {
            $table->string('mail_subject')->nullable()->after('comment');
            $table->decimal('service_price', 10, 2)->nullable()->after('mail_subject'); 
            $table->decimal('govt_price', 10, 2)->nullable()->after('service_price');
            $table->decimal('gst', 10, 2)->nullable()->after('govt_price'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_task_details', function (Blueprint $table) {
            $table->dropColumn(['service_price', 'govt_price', 'gst', 'mail_subject']);
        });
    }
};
