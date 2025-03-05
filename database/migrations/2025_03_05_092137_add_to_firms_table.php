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
        Schema::table('firms', function (Blueprint $table) {
           $table->string('acc_holder_name')->nullable(true)->after('zipcode');
           $table->string('bank_name')->nullable(true)->after('acc_holder_name');
           $table->string('branch_name')->nullable(true)->after('bank_name');
           $table->string('account_number')->nullable(true)->after('branch_name');
           $table->string('ifsc_code')->nullable(true)->after('account_number');
           $table->string('swift_code')->nullable(true)->after('ifsc_code');
           $table->string('upi_id')->nullable(true)->after('swift_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('firms', function (Blueprint $table) {
            //
        });
    }
};
