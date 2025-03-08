<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrdersTableForOrderItems extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Suppression de la colonne shopping_details_id si elle existe
            if (Schema::hasColumn('orders', 'shopping_details_id')) {
                $table->dropForeign(['shopping_details_id']);
                $table->dropColumn('shopping_details_id');
            }});
            /*Ajout de la colonne shop_id pour vite trouver la boutique*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Retrait de la colonne shop_id
            /*$table->dropForeign(['shop_id']);
            $table->dropColumn('shop_id');*/

            // Optionnel : réintroduire shopping_details_id pour revenir à l'ancienne structure
            $table->unsignedBigInteger('shopping_details_id')->after('user_id');
            $table->foreign('shopping_details_id')->references('id')->on('shopping_details')->onDelete('cascade');
        });

    }
};
