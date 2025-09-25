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

        Schema::table('sections', function (Blueprint $table) {
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

        Schema::table('librarians', function (Blueprint $table) {
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table->foreign('campus_id')
            ->references('id')
            ->on('campuses')
            ->onDelete('cascade');
            
            $table->foreign('section_id')
            ->references('id')
            ->on('sections')
            ->onDelete('cascade');
        });

        Schema::table('patrons', function (Blueprint $table) {
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table->foreign('program_id')
            ->references('id')
            ->on('programs')
            ->onDelete('cascade');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreign('campus_id')
            ->references('id')
            ->on('campuses')
            ->onDelete('cascade');
        });

        Schema::table('accessions', function (Blueprint $table) {
            $table->foreign('item_id')
            ->references('id')
            ->on('items')
            ->onDelete('cascade');
        });

        Schema::table('theses', function (Blueprint $table) {
            $table->foreign('item_id')
            ->references('id')
            ->on('items')
            ->onDelete('cascade');
        });

        Schema::table('serials', function (Blueprint $table) {
            $table->foreign('item_id')
            ->references('id')
            ->on('items')
            ->onDelete('cascade');
        });

        Schema::table('technicals', function (Blueprint $table) {
            $table->foreign('item_id')
            ->references('id')
            ->on('items')
            ->onDelete('cascade');
        });

        Schema::table('verticals', function (Blueprint $table) {
            $table->foreign('item_id')
            ->references('id')
            ->on('items')
            ->onDelete('cascade');
        });

        Schema::table('electronics', function (Blueprint $table) {
            $table->foreign('item_id')
            ->references('id')
            ->on('items')
            ->onDelete('cascade');
        });

        Schema::table('circulations', function (Blueprint $table) {
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table->foreign('accession_id')
            ->references('id')
            ->on('accessions')
            ->onDelete('cascade');

            $table->foreign('issued_by')
            ->references('id')
            ->on('librarians')
            ->onDelete('cascade');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table->foreign('section_id')
            ->references('id')
            ->on('sections')
            ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['section_id']);
        });

        Schema::table('circulations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['accession_id']);
            $table->dropForeign(['issued_by']);
        });

        Schema::table('electronics', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('verticals', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('technicals', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('serials', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('theses', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('accessions', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
        });

        Schema::table('patrons', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['program_id']);
        });

        Schema::table('librarians', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['campus_id']);
            $table->dropForeign(['section_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['profile_picture']);
        });

        Schema::table('sections', function (Blueprint $table) {
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