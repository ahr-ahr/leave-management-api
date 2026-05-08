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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('start_date');

            $table->date('end_date');

            $table->unsignedInteger('days');

            $table->text('reason');

            $table->string('attachment');

            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')
                ->nullable();

            $table->text('rejection_reason')
                ->nullable();

            $table->timestamps();

            $table->index('user_id');

            $table->index('status');

            $table->index('approved_by');

            $table->index('start_date');

            $table->index('end_date');

            $table->index('created_at');

            $table->index('updated_at');

            $table->index([
                'user_id',
                'status'
            ]);

            $table->index([
                'user_id',
                'start_date',
                'end_date'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
