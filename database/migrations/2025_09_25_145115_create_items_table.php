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
            $table->unsignedBigInteger('publisher_id');
            $table->year('year_published')->nullable();
            $table->string('isbn_issn')->nullable();
            $table->string('edition')->nullable();
            $table->string('call_number');
            $table->unsignedBigInteger('item_type_id');
            $table->unsignedBigInteger('language_id');
            $table->string('remarks')->nullable();
            $table->json('maintext_raw')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
