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
        Schema::create('payments', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('method_id')->constrained('payment_methods')->onDelete('cascade');
            $table->timestamp('paid_at')->nullable();
            $table->enum('status', ['processing', 'failed', 'successful', 'canceled'])->default('processing');
            //$table->string('status')->default('pending');
            //$table->foreignId('code_promo_id')->nullable()->constrained('promos')->onDelete('set null');
            $table->foreignId('code_promo_id')->nullable()->constrained('promos')->onDelete('restrict');
            $table->decimal('total_paid', 8, 2);
            $table->text('details')->nullable();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->primary(['order_id', 'method_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropForeignId(['promo_id', 'order_id', 'order_id', 'method_id']);
        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('code_promo_id');
            $table->dropConstrainedForeignId('order_id');
            $table->dropConstrainedForeignId('method_id');
            $table->dropConstrainedForeignId('invoice_id');
        });
        Schema::dropIfExists('payments');
    }
};
