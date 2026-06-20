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
        Schema::create('tour_dates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tour_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('start_date');

            $table->date('end_date');

            $table->integer('available_seat')->default(0);
            $table->decimal('special_price',10,2)->nullable();
            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_dates');
    }
};
