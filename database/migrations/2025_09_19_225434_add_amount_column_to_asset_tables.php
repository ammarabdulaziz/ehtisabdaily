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
        // Add amount column to asset_accounts table
        Schema::table('asset_accounts', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->nullable()->after('actual_amount');
        });

        // Add amount column to asset_borrowed_money table
        Schema::table('asset_borrowed_money', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->nullable()->after('actual_amount');
        });

        // Add amount column to asset_deposits table
        Schema::table('asset_deposits', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->nullable()->after('actual_amount');
        });

        // Add amount column to asset_investments table
        Schema::table('asset_investments', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->nullable()->after('actual_amount');
        });

        // Add amount column to asset_lent_money table
        Schema::table('asset_lent_money', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->nullable()->after('actual_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove amount column from all asset tables
        Schema::table('asset_accounts', function (Blueprint $table) {
            $table->dropColumn('amount');
        });

        Schema::table('asset_borrowed_money', function (Blueprint $table) {
            $table->dropColumn('amount');
        });

        Schema::table('asset_deposits', function (Blueprint $table) {
            $table->dropColumn('amount');
        });

        Schema::table('asset_investments', function (Blueprint $table) {
            $table->dropColumn('amount');
        });

        Schema::table('asset_lent_money', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }
};