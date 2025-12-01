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
            $table->dateTime('borrowed_at');
            $table->dateTime('due_at');
            $table->dateTime('returned_at')->nullable();

            $table->enum('status', ['borrowed', 'returned', 'overdue', 'lost', 'cancelled'])->index();
            $table->integer('renewal_count')->default(0);
            $table->decimal('fine_charged')->default(0);
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unsignedBigInteger('processed_by');
            $table->unsignedBigInteger('accession_id');
            $table->unsignedBigInteger('patron_id');
            $table->unsignedBigInteger('loan_mode_id');
            $table->unsignedBigInteger('return_received_by');
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
