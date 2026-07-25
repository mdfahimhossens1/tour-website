<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resort_bookings', function (Blueprint $table) {

            $table->id();

            // Customer
            $table->foreignId('user_id')->constrained();

            // Vendor
            $table->foreignId('vendor_id')->constrained();

            // Resort
            $table->foreignId('resort_id')->constrained();

            // Room
            $table->foreignId('room_id')->constrained();

            // Booking Code
            $table->string('booking_code')->unique();

            // Dates
            $table->date('check_in');

            $table->date('check_out');

            $table->integer('total_nights');

            // Guests
            $table->integer('adults')->default(1);

            $table->integer('children')->default(0);

            // Pricing
            $table->decimal('room_price',10,2);

            $table->decimal('subtotal',10,2);

            $table->decimal('discount',10,2)->default(0);

            $table->decimal('tax',10,2)->default(0);

            $table->decimal('total_amount',10,2);

            // Commission
            $table->decimal('commission_rate',5,2)->default(10);

            $table->decimal('admin_commission',10,2)->default(0);

            $table->decimal('vendor_earning',10,2)->default(0);

            // Status
            $table->enum('payment_status',[
                'pending',
                'paid',
                'failed',
                'refunded'
            ])->default('pending');

            $table->enum('booking_status',[
                'pending',
                'confirmed',
                'checked_in',
                'checked_out',
                'cancelled'
            ])->default('pending');

            $table->text('special_request')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resort_bookings');
    }
};