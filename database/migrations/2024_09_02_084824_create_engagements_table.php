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
        Schema::create('engagements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('time_spent')->default(0); // in minutes
            $table->integer('interactions')->default(0); // number of interactions
            $table->integer('assignments_completed')->default(0); // assignments completed
            $table->timestamps(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('engagements');
    }
};
