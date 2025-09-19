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
        Schema::create('asset_investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_management_id')->constrained()->onDelete('cascade');
            $table->string('investment_type');
            $table->string('investment_name');
            $table->decimal('amount', 15, 2);
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['asset_management_id', 'investment_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_investments');
    }
};
