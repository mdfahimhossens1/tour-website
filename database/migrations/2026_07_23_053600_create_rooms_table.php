<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {

            $table->id();

            $table->foreignId('resort_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('slug')->unique();

            $table->string('room_no')->nullable();

            $table->text('description')->nullable();

            $table->decimal('price',10,2);

            $table->decimal('discount_price',10,2)->nullable();

            $table->decimal('extra_bed_price',10,2)->nullable();

            $table->integer('total_rooms')->default(1);

            $table->integer('max_adult')->default(2);

            $table->integer('max_child')->default(0);

            $table->integer('beds')->default(1);

            $table->integer('bathrooms')->default(1);

            $table->decimal('size',8,2)->nullable();

            $table->string('size_unit')->default('sqft');

            $table->string('view_type')->nullable();

            $table->string('featured_image')->nullable();

            $table->boolean('is_featured')->default(false);

            $table->boolean('status')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};