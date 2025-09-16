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
        Schema::create('asset_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_management_id')->constrained()->onDelete('cascade');
            $table->string('deposit_type');
            $table->string('deposit_name');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('QAR');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['asset_management_id', 'deposit_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_deposits');
    }
};
