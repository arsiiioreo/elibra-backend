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
        Schema::create('circulations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accession_id');
            $table->unsignedBigInteger('patron_id');
            $table->unsignedBigInteger('loan_mode_id');
            $table->dateTime('borrowed_at');
            $table->dateTime('due_at');
            $table->dateTime('returned_at');
            $table->unsignedBigInteger('processed_by');
            $table->enum('status', ['borrowed', 'returned', 'overdue', 'lost', 'cancelled']);
            $table->integer('renewal_count')->default(1);
            $table->float('fine_charged')->default(0);
            $table->string('notes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('circulations');
    }
};
