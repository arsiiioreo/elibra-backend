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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('isbn_issn')->nullable();
            $table->enum('category', ['encyclopedia', 'english',
                'filipiniana', 'fiction', 'general', 'math',
                'novel', 'reference', 'science', 'textbook',
            ]);
            $table->string('edition')->nullable();
            $table->integer('pages')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
