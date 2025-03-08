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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            //$table->foreignId('notification_category_id')->constrained('notification_categories')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropForeignId('notification_category_id');
        /*Schema::table('notifications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('notification_category_id');
        });*/
        Schema::dropIfExists('notifications');
    }
};
