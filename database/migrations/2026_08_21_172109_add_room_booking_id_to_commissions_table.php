<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commissions', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Existing tour booking relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId('booking_id')
                ->nullable()
                ->change();

            /*
            |--------------------------------------------------------------------------
            | Room booking relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId('room_booking_id')
                ->nullable()
                ->after('booking_id')
                ->constrained('room_bookings')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {

            $table->dropForeign([
                'room_booking_id'
            ]);

            $table->dropColumn([
                'room_booking_id'
            ]);

            $table->foreignId('booking_id')
                ->nullable(false)
                ->change();
        });
    }
};