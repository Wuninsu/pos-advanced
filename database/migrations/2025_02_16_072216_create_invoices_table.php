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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');

            $table->decimal('invoice_amount', 10, 2)->default(0.00); // original total before discount
            $table->decimal('discount', 10, 2)->default(0.00); // discount applied (not percentage, but actual value)
            $table->decimal('amount_payable', 10, 2)->default(0.00); // after discount
            $table->decimal('amount_paid', 10, 2)->default(0.00); // customer paid
            $table->decimal('balance', 10, 2)->default(0.00); // balance = discounted_amount - amount_paid
            $table->enum('payment_method', ['cash', 'bank', 'cheque', 'credit', 'mobile_money'])->nullable();
            $table->enum('status', ['unpaid', 'paid', 'canceled'])->default('unpaid');
            $table->date('invoice_date')->default(now());
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
