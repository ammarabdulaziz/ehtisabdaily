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
        // Drop indexes first before dropping columns
        Schema::table('asset_deposits', function (Blueprint $table) {
            $table->dropIndex('asset_deposits_asset_management_id_deposit_type_index');
        });

        Schema::table('asset_investments', function (Blueprint $table) {
            $table->dropIndex('asset_investments_asset_management_id_investment_type_index');
        });

        // Remove deposit_type field from asset_deposits table
        Schema::table('asset_deposits', function (Blueprint $table) {
            $table->dropColumn('deposit_type');
        });

        // Remove investment_type field from asset_investments table
        Schema::table('asset_investments', function (Blueprint $table) {
            $table->dropColumn('investment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back deposit_type field to asset_deposits table
        Schema::table('asset_deposits', function (Blueprint $table) {
            $table->string('deposit_type')->nullable();
        });

        // Add back investment_type field to asset_investments table
        Schema::table('asset_investments', function (Blueprint $table) {
            $table->string('investment_type')->nullable();
        });

        // Recreate indexes
        Schema::table('asset_deposits', function (Blueprint $table) {
            $table->index(['asset_management_id', 'deposit_type'], 'asset_deposits_asset_management_id_deposit_type_index');
        });

        Schema::table('asset_investments', function (Blueprint $table) {
            $table->index(['asset_management_id', 'investment_type'], 'asset_investments_asset_management_id_investment_type_index');
        });
    }
};
