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
        Schema::create('order_items', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('shopping_details_id')->constrained('shopping_details')->onDelete('cascade');
            $table->primary(['order_id', 'shopping_details_id']);
            $table->timestamps();
            $table->softDeletes();

            /*$table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('shopping_details_id');

            // Clés étrangères
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('shopping_details_id')->references('id')->on('shopping_details')->onDelete('restrict');
            */

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::disableForeignKeyConstraints(['order_id', 'shopping_details_id']);
        
        //Schema::dropForeign(['shopping_details_id']);
        //Schema::dropColumn('shopping_details_id');
        //Schema::dropForeign(['order_id']);
        //Schema::dropColumn('order_id');
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
            $table->dropConstrainedForeignId('shopping_details_id');
        });
        Schema::dropIfExists('order_items');
    }
};
