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
        Schema::create('fines_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patron_id');
            $table->unsignedBigInteger('loan_id');
            $table->float('amount');
            $table->enum('transaction_type', ['charged', 'paid', 'waived']);
            $table->unsignedBigInteger('processed_by');
            $table->string('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines_transactions');
    }
};
