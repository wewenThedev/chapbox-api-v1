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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->text('description')->nullable();
            $table->string('type');
            $table->foreignId('supermarket_id')->nullable()->constrained('supermarkets')->onDelete('cascade');
            $table->decimal('discount', 5, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropForeignId(['supermarket_id']);
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('supermarket_id');
        });
        Schema::dropIfExists('promos');
    }
};
