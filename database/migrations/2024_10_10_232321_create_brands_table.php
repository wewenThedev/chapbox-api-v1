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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('logo_id')->nullable()->constrained('media')->onDeleteCascade();
            $table->text('infos')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropForeignId('logo_id');
        Schema::table('brands', function (Blueprint $table) {
            $table->dropConstrainedForeignId('logo_id');
        });
        Schema::dropIfExists('brands');
    }
};
