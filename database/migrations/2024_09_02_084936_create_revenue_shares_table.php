<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('revenue_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_revenue', 10, 2); // Total subscription revenue
            $table->decimal('teacher_earnings', 10, 2); // Teacher’s share
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('revenue_shares');
    }
};
