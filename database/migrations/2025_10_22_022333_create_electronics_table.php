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
        Schema::create('electronics', function (Blueprint $table) {
            $table->id();
            $table->string('isbn_issn')->nullable();
            $table->string('file_size')->nullable();
            $table->string('access_url')->nullable();
            $table->timestamps();
            
            $table->unsignedBigInteger('item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electronics');
    }
};
