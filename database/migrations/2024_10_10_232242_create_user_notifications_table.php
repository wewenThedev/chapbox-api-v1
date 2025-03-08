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
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('notification_id')->constrained('notifications')->onDelete('cascade');
            $table->timestamp('sent_at')->nullable();
            $table->primary(['user_id', 'notification_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropForeignId(['notification_id', 'user_id']);
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('notification_id');
            $table->dropConstrainedForeignId('user_id');
        });
        Schema::dropIfExists('user__notifications');
    }
};
