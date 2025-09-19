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
        // Create asset_management table
        Schema::create('asset_management', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->string('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'month', 'year']);
            $table->index(['user_id', 'year', 'month']);
        });

        // Create account_types table
        Schema::create('account_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            $table->unique(['user_id', 'name']);
            $table->index(['user_id', 'is_default']);
        });

        // Create friends table
        Schema::create('friends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            $table->unique(['user_id', 'name']);
            $table->index(['user_id', 'is_default']);
        });

        // Create deposit_types table
        Schema::create('deposit_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            $table->unique(['user_id', 'name']);
            $table->index(['user_id', 'is_default']);
        });

        // Create investment_types table
        Schema::create('investment_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            $table->unique(['user_id', 'name']);
            $table->index(['user_id', 'is_default']);
        });

        // Create asset_accounts table
        Schema::create('asset_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_management_id')->constrained('asset_management')->onDelete('cascade');
            $table->foreignId('account_type_id')->constrained()->onDelete('cascade');
            $table->decimal('actual_amount', 15, 2);
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);
            $table->string('notes')->nullable();
            $table->timestamps();
            
            $table->index(['asset_management_id', 'account_type_id']);
        });

        // Create asset_lent_money table
        Schema::create('asset_lent_money', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_management_id')->constrained()->onDelete('cascade');
            $table->foreignId('friend_id')->constrained()->onDelete('cascade');
            $table->decimal('actual_amount', 15, 2);
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);
            $table->string('notes')->nullable();
            $table->timestamps();
            
            $table->index(['asset_management_id', 'friend_id']);
        });

        // Create asset_borrowed_money table
        Schema::create('asset_borrowed_money', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_management_id')->constrained()->onDelete('cascade');
            $table->foreignId('friend_id')->constrained()->onDelete('cascade');
            $table->decimal('actual_amount', 15, 2);
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);
            $table->string('notes')->nullable();
            $table->timestamps();
            
            $table->index(['asset_management_id', 'friend_id']);
        });

        // Create asset_investments table
        Schema::create('asset_investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_management_id')->constrained()->onDelete('cascade');
            $table->foreignId('investment_type_id')->constrained()->onDelete('cascade');
            $table->decimal('actual_amount', 15, 2);
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);
            $table->string('notes')->nullable();
            $table->timestamps();
            
            $table->index(['asset_management_id', 'investment_type_id']);
        });

        // Create asset_deposits table
        Schema::create('asset_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_management_id')->constrained()->onDelete('cascade');
            $table->foreignId('deposit_type_id')->constrained()->onDelete('cascade');
            $table->decimal('actual_amount', 15, 2);
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);
            $table->string('notes')->nullable();
            $table->timestamps();
            
            $table->index(['asset_management_id', 'deposit_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_deposits');
        Schema::dropIfExists('asset_investments');
        Schema::dropIfExists('asset_borrowed_money');
        Schema::dropIfExists('asset_lent_money');
        Schema::dropIfExists('asset_accounts');
        Schema::dropIfExists('investment_types');
        Schema::dropIfExists('deposit_types');
        Schema::dropIfExists('friends');
        Schema::dropIfExists('account_types');
        Schema::dropIfExists('asset_management');
    }
};
