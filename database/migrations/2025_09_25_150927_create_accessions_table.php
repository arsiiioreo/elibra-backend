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
            $table->string('accession_code');
            $table->string('shelf_location');
            $table->enum('status', ['available', 'reserved', 'on_load', 'lost', 'missing', 'archived', 'condemned'])->default('available');
            // $table->dateTime('date_acquired');
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('section_id'); // Location
            $table->unsignedBigInteger('acquisition_id');
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
