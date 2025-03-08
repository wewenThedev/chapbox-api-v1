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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('logo_id')->nullable()->constrained('media')->onDelete('set null');
            $table->text('terms_conditions')->nullable();
            $table->decimal('fees', 5, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropForeignId(['logo_id']);
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropConstrainedForeignId('logo_id');
        });
        Schema::dropIfExists('payment_methods');
    }
};
