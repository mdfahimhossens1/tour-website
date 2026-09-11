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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('price', 12, 2)->default(0);
            $table->enum('billing_cycle', [
                'monthly',
                'yearly',
                'lifetime',
            ])->default('monthly');

            $table->string('currency', 10)->default('USD');

            // Usage Limits
            // NULL = Unlimited
            $table->unsignedInteger('max_vendors')->nullable();
            $table->unsignedInteger('max_tours')->nullable();
            $table->unsignedInteger('max_bookings')->nullable();
            $table->unsignedInteger('max_storage_mb')->nullable();

            // Display / Status
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            // Indexes
            $table->index('billing_cycle');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};