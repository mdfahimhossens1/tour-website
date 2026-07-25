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
Schema::create('resort_booking_guests', function (Blueprint $table) {

    $table->id();

    $table->foreignId('resort_booking_id')->constrained()->cascadeOnDelete();

    $table->string('name');

    $table->integer('age')->nullable();

    $table->enum('gender', ['male','female','other'])->nullable();

    $table->string('phone')->nullable();

    $table->string('nid')->nullable();

    $table->string('passport')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resort_booking_guests');
    }
};
