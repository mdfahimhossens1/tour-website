<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resort_rooms', function (Blueprint $table) {

            $table->id();

            $table->foreignId('resort_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('room_name');

            $table->string('slug')->unique();

            $table->string('room_number')->nullable();

            $table->enum('room_type', [
                'single',
                'double',
                'family',
                'suite',
                'deluxe'
            ]);

            $table->integer('capacity')->default(2);

            $table->decimal('price_per_night',10,2);

            $table->integer('total_rooms')->default(1);

            $table->integer('available_rooms')->default(1);

            $table->boolean('breakfast')->default(false);

            $table->boolean('ac')->default(true);

            $table->boolean('wifi')->default(true);

            $table->boolean('parking')->default(false);

            $table->boolean('status')->default(true);

            $table->longText('description')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resort_rooms');
    }
};