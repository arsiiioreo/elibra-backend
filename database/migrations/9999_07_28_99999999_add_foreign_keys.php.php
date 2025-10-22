<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('profile_picture')->references('id')->on('profile_photos')->onDelete('cascade');
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
            $table->foreign('settings_id')->references('id')->on('system_settings')->onDelete('cascade');
        });

        Schema::table('patrons', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('patron_type_id')->references('id')->on('patron_types')->onDelete('cascade');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('cascade');
            $table->foreign('item_type_id')->references('id')->on('item_types')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        Schema::table('theses', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
        });

        Schema::table('audio', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        Schema::table('serials', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        Schema::table('periodicals', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        Schema::table('electronics', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        Schema::table('verticals', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        Schema::table('newspapers', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        Schema::table('accessions', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('acquisition_id')->references('id')->on('acquisitions')->onDelete('cascade');
        });

        Schema::table('item_authors', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
        });

        Schema::table('item_categories', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        Schema::table('acquisition_lines', function (Blueprint $table) {
            $table->foreign('acquisition_id')->references('id')->on('acquisitions')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('accession_id')->references('id')->on('accessions')->onDelete('cascade');
        });

        Schema::table('acquisition_requests', function (Blueprint $table) {
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('item_type_id')->references('id')->on('item_types')->onDelete('cascade');
        });

        Schema::table('circulations', function (Blueprint $table) {
            $table->foreign('accession_id')->references('id')->on('accessions')->onDelete('cascade');
            $table->foreign('patron_id')->references('id')->on('patrons')->onDelete('cascade');
            $table->foreign('loan_mode_id')->references('id')->on('loan_modes')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('loan_modes')->onDelete('cascade');
        });

        Schema::table('fines_transactions', function (Blueprint $table) {
            $table->foreign('patron_id')->references('id')->on('patrons')->onDelete('cascade');
            $table->foreign('loan_id')->references('id')->on('circulations')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('patron_type_loan_policies', function (Blueprint $table) {
            $table->foreign('patron_type_id')->references('id')->on('patron_types')->onDelete('cascade');
            $table->foreign('loan_mode_id')->references('id')->on('loan_modes')->onDelete('cascade');
        });

        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->foreign('patron_id')->references('id')->on('patrons')->onDelete('cascade');
            $table->foreign('branches_id')->references('id')->on('branches')->onDelete('cascade');
        });

        Schema::table('user_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('system_settings', function (Blueprint $table) {
            $table->foreign('setting_key')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('affected_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['affected_user_id']);
        });

        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropForeign(['setting_key']);
        });

        Schema::table('user_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->dropForeign(['patron_id']);
            $table->dropForeign(['branches_id']);
        });

        Schema::table('patron_type_loan_policies', function (Blueprint $table) {
            $table->dropForeign(['patron_type_id']);
            $table->dropForeign(['loan_mode_id']);
        });

        Schema::table('fines_transactions', function (Blueprint $table) {
            $table->dropForeign(['patron_id']);
            $table->dropForeign(['loan_id']);
            $table->dropForeign(['processed_by']);
        });

        Schema::table('circulations', function (Blueprint $table) {
            $table->dropForeign(['accession_id']);
            $table->dropForeign(['patron_id']);
            $table->dropForeign(['loan_mode_id']);
            $table->dropForeign(['processed_by']);
        });

        Schema::table('acquisition_requests', function (Blueprint $table) {
            $table->dropForeign(['requested_by']);
            $table->dropForeign(['item_type_id']);
        });

        Schema::table('acquisition_lines', function (Blueprint $table) {
            $table->dropForeign(['acquisition_id']);
            $table->dropForeign(['item_id']);
            $table->dropForeign(['accession_id']);
        });

        Schema::table('item_categories', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['category_id']);
        });

        Schema::table('item_authors', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['author_id']);
        });

        Schema::table('accessions', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['acquisition_id']);
        });

        Schema::table('newspapers', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('verticals', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('electronics', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('periodicals', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('serials', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('audio', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('theses', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['program_id']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['publisher_id']);
            $table->dropForeign(['item_type_id']);
            $table->dropForeign(['language_id']);
        });

        Schema::table('patrons', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['program_id']);
            $table->dropForeign(['patron_type_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['profile_picture']);
            $table->dropForeign(['campus_id']);
            $table->dropForeign(['settings_id']);
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
            $table->dropForeign(['department_id']);
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
        });
    }
};
