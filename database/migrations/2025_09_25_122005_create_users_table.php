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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('last_name')->nullable();
            $table->string('middle_initial')->nullable();
            $table->string('first_name')->nullable();

            $table->enum('sex', ['male', 'female', 'others'])->nullable();
            $table->string('contact_number', 13)->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('password')->nullable();

            $table->enum('role', ['0', '1', '2'])->default('2');
            $table->uuid('code')->unique()->nullable();
            $table->unsignedBigInteger('profile_picture')->nullable();
            $table->unsignedBigInteger('settings_id')->nullable();

            $table->enum('status', ['0', '1', '2'])->default('1'); // 0: Active, 1: Suspended, 2: Expired
            $table->enum('pending_registration_approval', ['0', '1', '2'])->default('1'); // 0: Approved, 1: Pending, 2: Rejected
            // $table->enum('is_disabled', ['0', '1'])->default('0'); // 0: No, 1: Yes
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
