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
        // Add soft deletes to main asset tables
        Schema::table('assets', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('asset_accounts', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('asset_investments', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('asset_deposits', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('asset_borrowed_money', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('asset_lent_money', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        // Add soft deletes to reference tables
        Schema::table('account_types', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('deposit_types', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('investment_types', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('friends', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove soft deletes from main asset tables
        Schema::table('assets', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('asset_accounts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('asset_investments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('asset_deposits', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('asset_borrowed_money', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('asset_lent_money', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        // Remove soft deletes from reference tables
        Schema::table('account_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('deposit_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('investment_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('friends', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
