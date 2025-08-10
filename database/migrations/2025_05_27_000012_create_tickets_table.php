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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('title');
            $table->longText('description');
            $table->foreignId('category_id')->constrained(
                table:  'categories',
            );  
              // Department and Location tracking (nullable if optional)
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->foreignId('location_id')->nullable()->constrained('locations');

            // Priority (low, medium, high)
            $table->string('priority')->default('medium');

            // Status (open, in_progress, resolved, etc.)
            $table->string('status')->default('open');
            $table->string('contact_number')->nullable();
            $table->string('patient_name')->nullable();
            $table->string('equipment_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
