<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Remove old FK
            |--------------------------------------------------------------------------
            |
            | Old:
            | booking_id -> bookings.id
            |
            */

            $table->dropForeign([
                'booking_id'
            ]);
        });


        Schema::table('wallet_transactions', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Correct FK
            |--------------------------------------------------------------------------
            |
            | New:
            | booking_id -> room_bookings.id
            |
            */

            $table->foreign('booking_id')
                ->references('id')
                ->on('room_bookings')
                ->nullOnDelete();
        });
    }


    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {

            $table->dropForeign([
                'booking_id'
            ]);

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->nullOnDelete();
        });
    }
};