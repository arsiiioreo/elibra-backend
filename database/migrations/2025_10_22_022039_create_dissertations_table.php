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
        Schema::create('dissertations', function (Blueprint $table) {
            $table->id();
            $table->text('abstract')->nullable();
            $table->string('advisor')->nullable();
            $table->json('researchers')->nullable();
            $table->string('doi')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('program_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dissertations');
    }
};
