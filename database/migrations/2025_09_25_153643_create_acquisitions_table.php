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
        Schema::create('acquisitions', function (Blueprint $table) {
            $table->id();
            $table->string('purchaseId')->nullable(); // ISU-E-2025-MM-DD-ID
            $table->string('dealer');
            $table->enum('acquisition_mode', ['purchased', 'donated', 'gift', 'exchange']);
            $table->date('acquisition_date');
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('received_by');
            $table->unsignedBigInteger('acquisition_request_id')->nullable(); // If the acquisition came from
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acquisitions');
    }
};
