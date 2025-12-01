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
            $table->string('title');
            $table->string('author');
            $table->string('publisher');
            $table->year('year')->nullable();
            $table->string('edition')->nullable();
            $table->integer('quantity')->default(1);
            $table->float('estimated_unit_price')->nullable();
            $table->string('supplier')->nullable();
            $table->string('dept')->nullable();
            $table->string('dept_head')->nullable();
            $table->enum('item_type', ['audio', 'book', 'dissertation', 'electronic', 'newspaper', 'periodical', 'serial', 'vertical']);
            $table->date('date_ordered')->nullable();
            $table->enum('status', ['request', 'pending', 'ordered', 'done', 'declined', 'cancelled'])->default('request');
            $table->timestamps();

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('last_modified_by')->nullable();
            $table->unsignedBigInteger('requested_by');
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
