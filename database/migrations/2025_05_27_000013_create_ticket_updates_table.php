<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_updates', function (Blueprint $table) {
            $table->id();

            // Which ticket this update belongs to
            $table->foreignId('ticket_id')
                  ->constrained('tickets')
                  ->cascadeOnDelete(); // delete updates when ticket is deleted

            // Who made this update
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->restrictOnDelete(); // don't delete updates if user is removed

            // The actual update content
            $table->text('message');

            // Type of update: 'comment', 'status_change', 'assignment', 'internal_note'
            $table->string('type', 50)->default('comment');

            // For internal notes (visible only to agents/admins)
            $table->boolean('is_internal')->default(false);

            // Optional: track what changed (for status changes, assignments, etc.)
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();

            $table->timestamps();

            // Indexes for common queries
            $table->index(['ticket_id', 'created_at']);
            $table->index(['ticket_id', 'is_internal']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_updates');
    }
};

