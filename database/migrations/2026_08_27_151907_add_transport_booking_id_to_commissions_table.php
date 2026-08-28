<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commissions', function (Blueprint $table) {

            $table->foreignId('transport_booking_id')
                ->nullable()
                ->after('room_booking_id')
                ->constrained('transport_bookings')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {

            $table->dropForeign([
                'transport_booking_id'
            ]);

            $table->dropColumn(
                'transport_booking_id'
            );
        });
    }
};