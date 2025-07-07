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
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('keyword');
            $table->integer('position')->nullable();
            $table->integer('search_volume')->nullable();
            $table->decimal('difficulty', 5, 2)->nullable();
            $table->json('tracking_data')->nullable();
            $table->timestamps();
            
            $table->unique(['project_id', 'keyword']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
};
