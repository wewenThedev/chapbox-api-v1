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
        Schema::create('shop_media', function (Blueprint $table) {
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
    $table->foreignId('media_id')->constrained('media')->onDelete('cascade');
    $table->primary(['shop_id', 'media_id']);
    $table->timestamps();
    $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropForeignId(['shop_id', 'media_id']);
        Schema::dropIfExists('shop_media');
    }
};
