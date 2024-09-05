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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User who made the purchase
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade'); // Course purchased
            $table->string('transaction_id')->nullable(); // Transaction ID from payment gateway
            $table->string('payment_method')->nullable(); // Payment method used
            $table->decimal('amount', 10, 2); // Amount paid
            $table->enum('status', ['completed', 'pending', 'failed', 'refunded'])->default('pending'); // Payment status
            $table->timestamp('purchase_date')->nullable(); // Date of purchase
            $table->timestamps(); // created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
