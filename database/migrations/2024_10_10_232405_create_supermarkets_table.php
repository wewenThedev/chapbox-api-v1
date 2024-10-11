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
        Schema::create('supermarkets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('denomination')->nullable();
            $table->string('rccm')->nullable();
            $table->string('ifu')->nullable();
            $table->string('website')->nullable();
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade');
            $table->foreignId('logo_id')->nullable()->constrained('media')->onDelete('set null');
            $table->foreignId('market_manager_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropForeignId(['address_id', 'logo_id', 'market_manager_id']);
        Schema::dropIfExists('supermarkets');
    }
};
