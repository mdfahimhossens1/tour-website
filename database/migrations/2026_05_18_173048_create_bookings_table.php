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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('tour_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('tour_date_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->decimal('admin_commission',10,2)->default(0);
            $table->decimal('vendor_earning',10,2)->default(0);
            $table->string('booking_code')->unique();

            $table->integer('person_count');

            $table->decimal('total_amount', 10, 2);

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded'
            ])->default('pending');

            $table->enum('booking_status', [
                'pending',
                'confirmed',
                'cancelled',
                'completed'
            ])->default('pending');

            $table->text('special_request')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
