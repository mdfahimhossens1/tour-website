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
Schema::create('room_booking_guests', function (Blueprint $table) {

    $table->id();

    $table->foreignId('room_booking_id')
        ->constrained('room_bookings')
        ->cascadeOnDelete();

    $table->string('name');

    $table->unsignedInteger('age')->nullable();

    $table->enum('gender', [
        'male',
        'female',
        'other',
    ])->nullable();

    $table->string('phone', 20)->nullable();

    $table->string('nid', 100)->nullable();

    $table->string('passport', 100)->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_booking_guests');
    }
};
