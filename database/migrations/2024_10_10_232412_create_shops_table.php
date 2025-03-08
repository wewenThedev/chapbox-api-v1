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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('phone');
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade');
            $table->foreignId('supermarket_id')->constrained('supermarkets')->onDelete('cascade');
            $table->foreignId('shop_manager_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropForeignId(['address_id', 'shop_manager_id', 'supermarket_id']);
        Schema::table('shops', function (Blueprint $table) {
            $table->dropConstrainedForeignId('address_id');
            $table->dropConstrainedForeignId('shop_manager_id');
            $table->dropConstrainedForeignId('supermarket_id');
        });
        Schema::dropIfExists('shops');
    }
};
