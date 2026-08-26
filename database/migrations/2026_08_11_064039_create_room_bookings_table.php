<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_bookings', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('vendor_id')
                ->constrained('vendors')
                ->cascadeOnDelete();

            $table->foreignId('resort_id')
                ->constrained('resorts')
                ->cascadeOnDelete();

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            $table->string('booking_code')->unique();

            $table->unsignedInteger('room_count')->default(1);

            $table->date('check_in');
            $table->date('check_out');

            $table->unsignedInteger('total_nights');

            $table->unsignedInteger('adults')->default(1);
            $table->unsignedInteger('children')->default(0);

            $table->decimal('room_price', 12, 2)->default(0);

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);

            $table->decimal('commission_rate', 5, 2)->default(10);
            $table->decimal('admin_commission', 12, 2)->default(0);
            $table->decimal('vendor_earning', 12, 2)->default(0);

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded',
            ])->default('pending');

            $table->enum('booking_status', [
                'pending',
                'confirmed',
                'cancelled',
                'completed',
            ])->default('pending');

            $table->text('special_request')->nullable();

            $table->timestamps();

            $table->index([
                'room_id',
                'check_in',
                'check_out',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_bookings');
    }
};