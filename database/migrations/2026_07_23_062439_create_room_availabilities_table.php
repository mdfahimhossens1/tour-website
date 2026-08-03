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
        Schema::create('room_availabilities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            $table->date('date');

            $table->decimal('price', 10, 2)->nullable();

            $table->integer('total_rooms')->default(0);

            $table->integer('available_rooms')->default(0);

            $table->boolean('is_closed')->default(false);

            $table->boolean('is_sold_out')->default(false);

            $table->timestamps();

            // একই room-এর জন্য একই date যেন একবারই থাকে
            $table->unique(['room_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_availabilities');
    }
};