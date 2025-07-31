<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campuses', function (Blueprint $table) {
            $table->id();
            $table->string('campus')->unique();
            $table->string('abbrev');
            $table->string('address')->nullable();

            $table->enum('is_active', ['0', '1'])->default('0'); // 0: No, 1: Yes
            $table->enum('is_deleted', ['0', '1'])->default('0'); // 0: No, 1: Yes

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campuses');
    }
};
