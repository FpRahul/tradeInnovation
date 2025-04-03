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
        Schema::create('service_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lead_id')->default(0);
            $table->bigInteger('task_id')->default(0);
            $table->bigInteger('service_id')->default(0);
            $table->string('journal_number')->nullable();
            $table->string('class_rule')->nullable();
            $table->string('applied_for')->nullable();
            $table->string('application_number')->nullable();
            $table->string('service_logo')->nullable();
            $table->string('filing_mode')->nullable();
            $table->date('filing_date')->nullable();
            $table->string('applicant_name')->nullable();
            $table->string('inventor_name')->nullable();
            $table->string('title_of_invention')->nullable();
            $table->string('category_of_invention')->nullable();
            $table->string('type_of_application')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 for In Progress, 1 for Register ,2 for Refused ');
            $table->tinyInteger('client_status')->default(0)->comment('1 for Applicant, 2 for opponent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_details');
    }
};
