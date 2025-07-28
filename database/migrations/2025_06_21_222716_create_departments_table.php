<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // Unique code for the department (e.g., "CCSICT")
            $table->string('name'); // Full name of the department (e.g., "College of Computer Studies and Information Communication Technology")
            $table->foreignId('campus_id')->constrained('campuses')->onDelete('cascade'); // Foreign key to the campuses table

            $table->enum('is_deleted', ['0', '1'])->default('0'); // Soft delete flag

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable()->default(null); // Soft delete timestamp
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
