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
        Schema::create('patrons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('ebc')->unique()->nullable();
            $table->string('id_number')->unique()->nullable();
            $table->unsignedBigInteger('program_id')->nullable();
            $table->unsignedBigInteger('patron_type_id');
            $table->string('external_organization')->nullable();
            $table->string('address')->nullable();
            $table->dateTime('date_joined')->default(now());
            $table->dateTime('date_expiry')->nullable();
            $table->enum('status', ['active', 'suspended', 'expired']);
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrons');
    }
};
