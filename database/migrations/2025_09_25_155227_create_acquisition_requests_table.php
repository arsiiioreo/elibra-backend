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
        Schema::create('acquisition_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requested_by');
            $table->string('title');
            $table->string('author');
            $table->string('publisher');
            $table->year('year')->nullable();
            $table->string('edition')->nullable();
            $table->integer('quantity')->default(1);
            $table->float('estimated_unit_price')->nullable();
            $table->string('dealer')->nullable();
            $table->string('dept')->nullable();
            $table->string('dept_head')->nullable();
            $table->unsignedBigInteger('item_type_id');
            $table->date('date_ordered')->nullable();
            $table->enum('status', ['pending', 'ordered', 'declined', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acquisition_requests');
    }
};
