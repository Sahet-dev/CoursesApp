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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student'); // Default role
            $table->boolean('is_active')->default(true); // Default status
            $table->string('first_name')->nullable(); // Nullable if not required
            $table->timestamp('last_login_at')->nullable(); // Nullable for optional login tracking
            $table->string('gender')->nullable(); // Nullable if not required
            $table->integer('age')->nullable(); // Nullable if not required
            $table->string('location')->nullable(); // Nullable if not required
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active', 'first_name', 'last_login_at', 'gender', 'age', 'location']);
        });
    }

};
