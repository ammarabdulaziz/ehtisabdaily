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
        Schema::create('asset_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_management_id')->constrained('asset_management')->onDelete('cascade');
            $table->foreignId('account_type_id')->constrained()->onDelete('cascade');
            $table->string('account_name');
            $table->decimal('amount', 15, 2);
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['asset_management_id', 'account_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_accounts');
    }
};
