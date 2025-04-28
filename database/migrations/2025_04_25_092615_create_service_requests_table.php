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
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('set null');
            $table->string('client')->nullable();
            // Loading and Delivery Info
            $table->string('loading_place')->nullable();
            $table->string('destination')->nullable();

            // Measurement and Pricing
            $table->decimal('quantity', 10, 2)->nullable();
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable(); // auto-calculated
            $table->decimal('revenue', 10, 2)->nullable();

            // Cost Breakdown (in percentages)
            $table->decimal('fuel', 10, 2)->nullable();       // 48%
            $table->decimal('allowance', 10, 2)->nullable();  // 10%
            $table->decimal('feeding', 10, 2)->nullable();    // 2%
            $table->decimal('maintenance', 10, 2)->nullable(); // 5%
            $table->decimal('owner', 10, 2)->nullable();
            $table->decimal('other_expenses', 10, 2)->nullable();
            $table->enum('status', ['pending', 'completed', 'in_progress'])->default('pending');
            $table->date('date')->nullable();
            $table->longText('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
