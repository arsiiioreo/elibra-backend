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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('call_number');
            $table->year('year_published')->nullable();
            $table->enum('item_type', ['audio', 'book', 'dissertation', 'electronic', 'newspaper', 'periodical', 'serial', 'vertical']);
            
            $table->string('description')->nullable();
            $table->json('maintext_raw')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('language_id')->nullable();
            $table->unsignedBigInteger('publisher_id')->nullable();
            $table->index(['title', 'call_number'], 'items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
