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
        Schema::table('asset_accounts', function (Blueprint $table) {
            $table->renameColumn('amount', 'actual_amount');
        });

        Schema::table('asset_lent_money', function (Blueprint $table) {
            $table->renameColumn('amount', 'actual_amount');
        });

        Schema::table('asset_borrowed_money', function (Blueprint $table) {
            $table->renameColumn('amount', 'actual_amount');
        });

        Schema::table('asset_investments', function (Blueprint $table) {
            $table->renameColumn('amount', 'actual_amount');
        });

        Schema::table('asset_deposits', function (Blueprint $table) {
            $table->renameColumn('amount', 'actual_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_accounts', function (Blueprint $table) {
            $table->renameColumn('actual_amount', 'amount');
        });

        Schema::table('asset_lent_money', function (Blueprint $table) {
            $table->renameColumn('actual_amount', 'amount');
        });

        Schema::table('asset_borrowed_money', function (Blueprint $table) {
            $table->renameColumn('actual_amount', 'amount');
        });

        Schema::table('asset_investments', function (Blueprint $table) {
            $table->renameColumn('actual_amount', 'amount');
        });

        Schema::table('asset_deposits', function (Blueprint $table) {
            $table->renameColumn('actual_amount', 'amount');
        });
    }
};
