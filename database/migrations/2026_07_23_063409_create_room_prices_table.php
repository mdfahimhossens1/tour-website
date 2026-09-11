<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_prices', function (Blueprint $table) {

            $table->id();

            $table->foreignId('room_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('from_date');

            $table->date('to_date');

            $table->decimal('price', 10, 2);

            $table->enum('discount_type', [
                'percentage',
                'amount'
            ])->nullable();

            $table->decimal('discount_value', 10, 2)->nullable();

            $table->enum('type', [
                'normal',
                'weekend',
                'holiday',
                'festival',
                'seasonal'
            ])->default('normal');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_prices');
    }
};