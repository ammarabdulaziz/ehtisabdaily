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
        // Update asset_lent_money table
        Schema::table('asset_lent_money', function (Blueprint $table) {
            $table->dropIndex('asset_lent_money_asset_management_id_friend_name_index');
            $table->dropColumn('friend_name');
            $table->foreignId('friend_id')->after('asset_management_id')->constrained()->onDelete('cascade');
        });

        // Update asset_borrowed_money table
        Schema::table('asset_borrowed_money', function (Blueprint $table) {
            $table->dropIndex('asset_borrowed_money_asset_management_id_friend_name_index');
            $table->dropColumn('friend_name');
            $table->foreignId('friend_id')->after('asset_management_id')->constrained()->onDelete('cascade');
        });

        // Update asset_investments table
        Schema::table('asset_investments', function (Blueprint $table) {
            $table->dropColumn('investment_name');
            $table->foreignId('investment_type_id')->after('asset_management_id')->constrained()->onDelete('cascade');
        });

        // Update asset_deposits table
        Schema::table('asset_deposits', function (Blueprint $table) {
            $table->dropColumn('deposit_name');
            $table->foreignId('deposit_type_id')->after('asset_management_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse asset_lent_money table
        Schema::table('asset_lent_money', function (Blueprint $table) {
            $table->dropForeign(['friend_id']);
            $table->dropColumn('friend_id');
            $table->string('friend_name');
        });

        // Reverse asset_borrowed_money table
        Schema::table('asset_borrowed_money', function (Blueprint $table) {
            $table->dropForeign(['friend_id']);
            $table->dropColumn('friend_id');
            $table->string('friend_name');
        });

        // Reverse asset_investments table
        Schema::table('asset_investments', function (Blueprint $table) {
            $table->dropForeign(['investment_type_id']);
            $table->dropColumn('investment_type_id');
            $table->string('investment_name');
        });

        // Reverse asset_deposits table
        Schema::table('asset_deposits', function (Blueprint $table) {
            $table->dropForeign(['deposit_type_id']);
            $table->dropColumn('deposit_type_id');
            $table->string('deposit_name');
        });
    }
};
