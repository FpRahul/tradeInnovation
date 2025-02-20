<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   

    public function up(): void
    {
        Schema::create('sub_stages', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('stage_id')->constrained('service_stages')->onDelete('cascade'); 
            $table->string('title'); 
            $table->integer('sub_stage_id'); 
            $table->timestamps(); 
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('sub_stages');
    }
};
