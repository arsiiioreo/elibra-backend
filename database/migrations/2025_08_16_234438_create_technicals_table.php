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
        Schema::create('technicals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('source')->nullable();
            $table->string('volume')->nullable();
            $table->string('location')->nullable();
            $table->string('issn_isbn')->nullable();
            $table->enum('accession_type', ['0', '1', '2']); // Type of accession (0: New, 1: Replacement, 2: Transfer)
            $table->string('receipt_type')->nullable(); // Type of receipt (e.g., official, provisional)
            $table->string('mode')->nullable(); // Mode of acquisition (e.g., purchase, donation)
            $table->bigInteger('unit_price')->default(0); // Price of the technical item
            $table->string('edition')->nullable(); // Edition of the technical item
            $table->text('remarks')->nullable(); // Additional remarks about the technical item
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technicals');
    }
};
