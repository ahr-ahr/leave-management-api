<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->string('email')->unique();

            $table->timestamp('email_verified_at')->nullable();

            $table->string('password')->nullable();

            $table->enum('role', ['admin', 'employee'])
                ->default('employee')
                ->index();

            $table->string('provider')
                ->nullable()
                ->index();

            $table->string('provider_id')
                ->nullable()
                ->index();

            $table->unique(['provider', 'provider_id']);

            $table->rememberToken();

            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();

            $table->string('token');

            $table->timestamp('created_at')->nullable();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');

        Schema::dropIfExists('users');
    }
};
