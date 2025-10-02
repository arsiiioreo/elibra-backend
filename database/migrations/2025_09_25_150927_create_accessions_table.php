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
        Schema::create('accessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->string('accession_number');
            $table->unsignedBigInteger('branch_id');
            $table->enum('status', ['available', 'on_load', 'lost', 'missing', 'archived'])->default('available');
            $table->string('shelf_location');
            $table->dateTime('date_acquired');
            $table->unsignedBigInteger('acquisition_id');
            $table->float('price');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accessions');
    }
};
