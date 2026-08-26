<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_wallets', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->decimal('balance', 12, 2)
                ->default(0);

            $table->decimal('total_deposited', 12, 2)
                ->default(0);

            $table->decimal('total_spent', 12, 2)
                ->default(0);

            $table->decimal('total_refunded', 12, 2)
                ->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_wallets');
    }
};
