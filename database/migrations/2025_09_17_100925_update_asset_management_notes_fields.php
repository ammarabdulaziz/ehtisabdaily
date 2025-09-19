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
        // Change notes fields from text to varchar in all asset management related tables
        Schema::table('asset_management', function (Blueprint $table) {
            $table->string('notes')->nullable()->change();
        });

        Schema::table('asset_accounts', function (Blueprint $table) {
            $table->string('notes')->nullable()->change();
        });

        Schema::table('asset_lent_money', function (Blueprint $table) {
            $table->string('notes')->nullable()->change();
        });

        Schema::table('asset_borrowed_money', function (Blueprint $table) {
            $table->string('notes')->nullable()->change();
        });

        Schema::table('asset_investments', function (Blueprint $table) {
            $table->string('notes')->nullable()->change();
        });

        Schema::table('asset_deposits', function (Blueprint $table) {
            $table->string('notes')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert notes fields back to text
        Schema::table('asset_management', function (Blueprint $table) {
            $table->text('notes')->nullable()->change();
        });

        Schema::table('asset_accounts', function (Blueprint $table) {
            $table->text('notes')->nullable()->change();
        });

        Schema::table('asset_lent_money', function (Blueprint $table) {
            $table->text('notes')->nullable()->change();
        });

        Schema::table('asset_borrowed_money', function (Blueprint $table) {
            $table->text('notes')->nullable()->change();
        });

        Schema::table('asset_investments', function (Blueprint $table) {
            $table->text('notes')->nullable()->change();
        });

        Schema::table('asset_deposits', function (Blueprint $table) {
            $table->text('notes')->nullable()->change();
        });
    }
};
