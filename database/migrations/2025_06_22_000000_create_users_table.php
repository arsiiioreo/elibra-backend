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
            $table->string('name')->nullable();
            $table->enum('sex', ['0', '1'])->nullable(); // 0: Female, 1: Male
            $table->string('contact_number', 13)->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();

            $table->enum('role', ['0', '1', '2', '3'])->default('2'); // 0: Super Admin, 1: Admin, 2: Student, 3. Student Assistant (Opt)
            $table->uuid('code')->unique()->nullable();
            $table->unsignedBigInteger('profile_picture')->nullable();

            $table->enum('pending_registration_approval', ['0', '1'])->default('1'); // 0: No, 1: Yes
            $table->enum('is_disabled', ['0', '1'])->default('0'); // 0: No, 1: Yes
            
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
