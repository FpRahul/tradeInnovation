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
        Schema::create('category_options', function (Blueprint $table) {
            $table->id();
            $table->integer('authId')->default(0);
            $table->tinyInteger('type')->default(0)->comment('1 for professions , 2 for incorporation and 3 for referral');
            $table->string('name')->nullable(true);
            $table->tinyInteger('status')->default(1)->comment('0 for inactive & 1 for active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_options');
    }
};
