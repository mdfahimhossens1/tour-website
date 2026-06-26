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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();

            $table->foreignId('destination_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)
                ->nullable();
            $table->string('duration')->nullable();
            $table->string('location')->nullable();
            $table->string('featured_image')->nullable();
            $table->longText('included')->nullable();
            $table->longText('excluded')->nullable();
            $table->longText('tour_plan')->nullable();
            $table->integer('max_seat')->default(0);
            $table->text('map_iframe')->nullable();
            $table->string('hotel_name')->nullable();
            $table->text('food_menu')->nullable();
            $table->decimal('backpack_price',10,2)->default(0);
            $table->decimal('moderate_price',10,2)->default(0);
            $table->decimal('luxury_price',10,2)->default(0);
            $table->longText('ai_highlights')->nullable();
            $table->boolean('is_featured')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
