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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
    $table->foreignId('shopping_details_id')->constrained('shopping_details')->onDelete('cascade');
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->string('guest_firstname')->nullable();
    $table->string('guest_lastname')->nullable();
    $table->string('guest_phone')->nullable();
    $table->string('guest_email')->nullable();
    $table->decimal('total_ht', 8, 2);
    $table->decimal('total_ttc', 8, 2);
    $table->timestamp('ordering_date');
    $table->timestamp('shipping_date')->nullable();
    $table->text('shipping_address')->nullable();
    $table->string('status')->default('pending');
    $table->timestamps();
    $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropForeignId(['user_id', 'shopping_details_id']);
        Schema::dropIfExists('orders');
    }
};
