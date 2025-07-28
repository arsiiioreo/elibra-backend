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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // Example: "BSCS", "BSIT"
            $table->string('name'); // Example: "Bachelor of Science in Computer Science", "Bachelor of Science in Information Technology"

            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade'); // Foreign key to the departments table
            $table->enum('is_deleted', ['0', '1'])->default('0'); // Soft delete flag (0: No, 1: Yes)
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable()->default(null); // Soft delete timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
