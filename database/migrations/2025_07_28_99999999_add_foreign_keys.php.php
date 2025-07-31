<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('campus_id')
                  ->references('id')
                  ->on('campuses')
                  ->onDelete('cascade');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->foreign('department_id')
                  ->references('id')
                  ->on('departments')
                  ->onDelete('cascade');
        });

        Schema::table('profile_photos', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('campus_id')
                  ->references('id')
                  ->on('campuses')
                  ->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('profile_picture')
                  ->references('id')
                  ->on('profile_photos')
                  ->onDelete('set null');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('program_id')
                  ->references('id')
                  ->on('programs')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['profile_picture']);
            $table->dropForeign(['campus_id']);
        });

        Schema::table('profile_photos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
        });
    }
};