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
            $table->unsignedBigInteger('campus_id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
