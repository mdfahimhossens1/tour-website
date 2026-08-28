<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_bookings', function (Blueprint $table) {

            $table->id();

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
            | Vendor
            |--------------------------------------------------------------------------
            */

            $table->foreignId('vendor_id')
                ->constrained('vendors')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Vehicle
            |--------------------------------------------------------------------------
            */

            $table->foreignId('vehicle_id')
                ->constrained('vehicles')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Booking Information
            |--------------------------------------------------------------------------
            */

            $table->string('booking_code')
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | Journey
            |--------------------------------------------------------------------------
            */

            $table->date('start_date');

            $table->date('end_date');

            $table->unsignedInteger('total_days')
                ->default(1);


            /*
            |--------------------------------------------------------------------------
            | Passenger
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('passengers')
                ->default(1);


            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            $table->decimal('price_per_day', 12, 2)
                ->default(0);

            $table->decimal('subtotal', 12, 2)
                ->default(0);

            $table->decimal('discount', 12, 2)
                ->default(0);

            $table->decimal('tax', 12, 2)
                ->default(0);

            $table->decimal('total_amount', 12, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Commission
            |--------------------------------------------------------------------------
            */

            $table->decimal('commission_rate', 5, 2)
                ->default(10);

            $table->decimal('admin_commission', 12, 2)
                ->default(0);

            $table->decimal('vendor_earning', 12, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded',
            ])->default('pending');


            /*
            |--------------------------------------------------------------------------
            | Booking Status
            |--------------------------------------------------------------------------
            */

            $table->enum('booking_status', [
                'pending',
                'confirmed',
                'cancelled',
                'completed',
            ])->default('pending');


            /*
            |--------------------------------------------------------------------------
            | Additional Information
            |--------------------------------------------------------------------------
            */

            $table->text('pickup_location')
                ->nullable();

            $table->text('dropoff_location')
                ->nullable();

            $table->text('special_request')
                ->nullable();


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

            $table->index([
                'vehicle_id',
                'start_date',
                'end_date',
            ]);

            $table->index([
                'vendor_id',
                'booking_status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_bookings');
    }
};
