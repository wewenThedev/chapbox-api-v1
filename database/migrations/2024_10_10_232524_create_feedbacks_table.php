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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->decimal('score', 3, 2);
            $table->text('comment')->nullable();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropForeignId('order_id');
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
        });
        Schema::dropIfExists('feedbacks');
    }
};
