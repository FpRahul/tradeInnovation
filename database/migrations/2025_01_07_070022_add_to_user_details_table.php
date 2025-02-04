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
            $table->string('fatherHusbandName')->nullable(true)->after('userId');
            $table->string('qualification')->nullable(true)->after('fatherHusbandName');
            $table->string('skills')->nullable(true)->after('qualification');
            $table->string('keyResponsibilityArea')->nullable(true)->after('skills');
            $table->string('keyPerformanceIndicator')->nullable(true)->after('keyResponsibilityArea');
            $table->string('emergencyContactDetails')->nullable(true)->after('keyPerformanceIndicator');
            $table->string('currentAddress')->nullable(true)->after('emergencyContactDetails');
            $table->string('permanentAddress')->nullable(true)->after('currentAddress');
            $table->string('uploadPhotograph')->nullable(true)->after('permanentAddress');
            $table->string('uploadPan')->nullable(true)->after('uploadPhotograph');
            $table->string('uploadAadhar')->nullable(true)->after('uploadPan');
            $table->string('uploadDrivingLicence')->nullable(true)->after('uploadAadhar');
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
