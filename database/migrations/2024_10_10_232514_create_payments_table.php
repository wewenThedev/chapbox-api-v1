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
            $table->string('status')->default('pending');
            $table->foreignId('code_promo_id')->nullable()->constrained('promos')->onDelete('set null');
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
        Schema::dropIfExists('payments');
    }
};
