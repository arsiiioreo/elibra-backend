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
        Schema::create('patron_type_loan_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patron_type_id');
            $table->unsignedBigInteger('loan_mode_id');
            $table->integer('loan_period_days');
            $table->integer('max_items');
            $table->integer('max_renewals');
            $table->float('fine_per_due');
            $table->integer('grace_period');
            $table->string('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patron_type_loan_policies');
    }
};
