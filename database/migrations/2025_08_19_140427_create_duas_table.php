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
        Schema::create('duas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->index();
            $table->text('arabic_text');
            $table->text('transliteration')->nullable();
            $table->text('english_translation')->nullable();
            $table->text('english_meaning')->nullable();
            $table->json('categories')->nullable();
            $table->string('source')->nullable();
            $table->string('reference')->nullable();
            $table->text('benefits')->nullable();
            $table->integer('recitation_count')->nullable()->default(1);
            $table->integer('sort_order')->nullable()->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duas');
    }
};
