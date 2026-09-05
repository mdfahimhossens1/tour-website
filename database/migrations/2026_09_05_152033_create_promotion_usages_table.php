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
        Schema::create('promotion_usages', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Promotion
            |--------------------------------------------------------------------------
            */
            $table->foreignId('promotion_id')
                ->constrained('promotions')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Booking
            |--------------------------------------------------------------------------
            |
            | One booking should normally use one promotion.
            |
            */
            $table->foreignId('booking_id')
                ->nullable()
                ->constrained('bookings')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Promotion Snapshot
            |--------------------------------------------------------------------------
            |
            | Keep the actual code used at the time of purchase.
            |
            */
            $table->string('promotion_code')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Amount Information
            |--------------------------------------------------------------------------
            */
            $table->decimal('base_amount', 12, 2)
                ->default(0);

            $table->decimal('discount_amount', 12, 2)
                ->default(0);

            $table->decimal('final_amount', 12, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Usage Status
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'used',
                'cancelled',
                'refunded',
            ])->default('used');

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index('promotion_id');
            $table->index('user_id');
            $table->index('booking_id');
            $table->index('status');
            $table->index('created_at');

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Promotion Usage
            |--------------------------------------------------------------------------
            |
            | A booking can only have one promotion usage record.
            |
            */
            $table->unique(
                'booking_id',
                'promotion_usages_booking_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_usages');
    }
};