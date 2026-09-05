<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->text('description')->nullable();

            // Discount
            $table->enum('type', ['percentage', 'fixed'])
                ->default('percentage');

            $table->decimal('value', 12, 2)
                ->default(0);

            $table->decimal('minimum_amount', 12, 2)
                ->default(0);

            $table->decimal('maximum_discount', 12, 2)
                ->nullable();

            // Validity
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();

            // Usage Control
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('usage_per_user')->nullable();
            $table->unsignedInteger('used_count')->default(0);

            // Display / Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            // Indexes
            $table->index('type');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('starts_at');
            $table->index('ends_at');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};