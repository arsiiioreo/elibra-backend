<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Departments Up
        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
        });

        // Programs Up
        Schema::table('programs', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });

        // Branches Up
        Schema::table('branches', function (Blueprint $table) {
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });

        // Sections Up
        Schema::table('sections', function (Blueprint $table) {
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Users Up
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('profile_picture')->references('id')->on('profile_photos')->onDelete('cascade');
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
            $table->foreign('settings_id')->references('id')->on('system_settings')->onDelete('cascade');
        });

        // Librarians Up
        Schema::table('librarians', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });

        // Patrons Up
        Schema::table('patrons', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('patron_type_id')->references('id')->on('patron_types')->onDelete('cascade');
        });

        // Profile Pictures Up
        Schema::table('profile_photos', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Items Up
        Schema::table('items', function (Blueprint $table) {
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });

        // Accessions Up
        Schema::table('accessions', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('acquisition_id')->references('id')->on('acquisitions')->onDelete('cascade');
        });

        // Item Authors Up
        Schema::table('item_authors', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
        });

        // Acquisition Up
        Schema::table('acquisitions', function (Blueprint $table) {
            $table->foreign('received_by')->references('id')->on('librarians')->onDelete('cascade');
            $table->foreign('acquisition_request_id')->references('id')->on('acquisition_requests')->onDelete('cascade');
        });

        // Acquisition Lines Up
        Schema::table('acquisition_lines', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('acquisition_id')->references('id')->on('acquisitions')->onDelete('cascade');
        });

        // Acquistion Requests Up
        Schema::table('acquisition_requests', function (Blueprint $table) {
            $table->foreign('approved_by')->references('id')->on('librarians')->onDelete('cascade');
            $table->foreign('last_modified_by')->references('id')->on('librarians')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('patrons')->onDelete('cascade');
        });

        // Circulations Up
        Schema::table('circulations', function (Blueprint $table) {
            $table->foreign('processed_by')->references('id')->on('librarians')->onDelete('cascade');
            $table->foreign('accession_id')->references('id')->on('accessions')->onDelete('cascade');
            $table->foreign('patron_id')->references('id')->on('patrons')->onDelete('cascade');
            $table->foreign('return_received_by')->references('id')->on('librarians')->onDelete('cascade');
        });

        // Patron Type Loan Policies Up
        Schema::table('patron_type_loan_policies', function (Blueprint $table) {
            $table->foreign('patron_type_id')->references('id')->on('patron_types')->onDelete('cascade');
            $table->foreign('loan_mode_id')->references('id')->on('loan_modes')->onDelete('cascade');
        });

        // Fines Transactions Up
        Schema::table('fines_transactions', function (Blueprint $table) {
            $table->foreign('patron_id')->references('id')->on('patrons')->onDelete('cascade');
            $table->foreign('loan_id')->references('id')->on('circulations')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('cascade');
        });

        // Attendance Logs Up
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->foreign('patron_id')->references('id')->on('patrons')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // User Logs
        Schema::table('user_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // System Settings Up
        Schema::table('system_settings', function (Blueprint $table) {
            $table->foreign('setting_key')->references('id')->on('users')->onDelete('cascade');
        });

        // Books Up
        Schema::table('books', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        // Thesis Up
        Schema::table('theses', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
        });

        // Dissertation Up
        Schema::table('dissertations', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
        });

        // Audio Up
        Schema::table('audio', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        // Serials Up
        Schema::table('serials', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        // Periodicals Up
        Schema::table('periodicals', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        // Electronics Up
        Schema::table('electronics', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        // Verticals Up
        Schema::table('verticals', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        // Newspapers Up
        Schema::table('newspapers', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        // Activity Logs Up
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('affected_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
            $table->dropForeign(['department_id']);
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
            $table->dropForeign(['settings_id']);
            $table->dropForeign(['profile_picture']);
        });

        Schema::table('librarians', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['section_id']);
        });

        Schema::table('patrons', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['program_id']);
            $table->dropForeign(['patron_type_id']);
        });

        Schema::table('profile_photos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['publisher_id']);
            $table->dropForeign(['language_id']);
        });

        Schema::table('accessions', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['section_id']);
            $table->dropForeign(['acquisition_id']);
        });

        Schema::table('item_authors', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['author_id']);
        });

        Schema::table('acquisitions', function (Blueprint $table) {
            $table->dropForeign(['received_by']);
            $table->dropForeign(['acquisition_request_id']);
        });

        Schema::table('acquisition_lines', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['acquisition_id']);
        });

        Schema::table('acquisition_requests', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['last_modified_by']);
            $table->dropForeign(['requested_by']);
        });

        Schema::table('circulations', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
            $table->dropForeign(['accession_id']);
            $table->dropForeign(['patron_id']);
            $table->dropForeign(['return_received_by']);
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

        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->dropForeign(['patron_id']);
            $table->dropForeign(['section_id']);
        });

        Schema::table('user_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropForeign(['setting_key']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('theses', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['program_id']);
        });

        Schema::table('dissertations', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['program_id']);
        });

        Schema::table('audio', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('serials', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('periodicals', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('electronics', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('verticals', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('newspapers', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['affected_user_id']);
        });
    }
};
