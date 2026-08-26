<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();

            // Vendor wallet
            $table->foreignId('vendor_id')
                ->nullable()
                ->constrained('vendors')
                ->cascadeOnDelete();

            // User / Traveler wallet
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->decimal('balance', 12, 2)->default(0);

            $table->decimal('pending_balance', 12, 2)->default(0);

            $table->decimal('total_earned', 12, 2)->default(0);

            $table->decimal('total_withdrawn', 12, 2)->default(0);

            $table->timestamps();

            // One wallet per vendor
            $table->unique('vendor_id');

            // One wallet per user
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
