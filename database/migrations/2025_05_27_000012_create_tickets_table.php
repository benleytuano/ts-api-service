<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            // Requester (who created the ticket)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->restrictOnDelete(); // don't delete tickets if a user is removed

            // Current assignee (nullable)
            $table->foreignId('assignee_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();     // if assignee is deleted, set to NULL
            $table->timestamp('assigned_at')->nullable();

            // Core fields
            $table->string('title');
            $table->longText('description');

            // Taxonomy / placement
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->restrictOnDelete();
            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained('departments')
                  ->nullOnDelete();
            $table->foreignId('location_id')
                  ->nullable()
                  ->constrained('locations')
                  ->nullOnDelete();

            // Workflow
            $table->string('priority', 20)->default('medium');   // low|medium|high
            $table->string('status', 20)->default('open');       // open|in_progress|resolved|closed

            // Optional details
            $table->string('contact_number', 32)->nullable();
            $table->string('patient_name')->nullable();
            $table->string('equipment_details')->nullable();

            $table->timestamps();

            // Useful indexes for common filters
            $table->index(['status', 'assignee_id'], 'tickets_status_assignee_idx');
            $table->index(['category_id', 'status']);
            $table->index('priority');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
