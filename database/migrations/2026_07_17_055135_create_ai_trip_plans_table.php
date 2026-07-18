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
        Schema::create('ai_trip_plans', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->string('from_location')->nullable();

    $table->string('destination');

    $table->integer('days');

    $table->integer('travelers');

    $table->decimal('budget',10,2);

    $table->string('travel_type')->nullable();

    $table->json('interests')->nullable();

    $table->string('hotel_type')->nullable();

    $table->string('transport')->nullable();

    $table->text('extra_note')->nullable();

    /*
    AI Response
    */

    $table->longText('prompt')->nullable();

    $table->longText('response')->nullable();

    /*
    JSON Response
    */

    $table->json('response_json')->nullable();

    $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_trip_plans');
    }
};
