<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            $table->morphs('paymentable');

            $table->string('trx_id')->unique();

            $table->string('payment_method')->nullable();
            // bkash, nagad, card, stripe, paypal, manual

            $table->decimal('amount', 10, 2);

            $table->enum('status', [
                'pending',
                'processing',
                'paid',
                'failed',
                'refunded',
            ])->default('pending');

            $table->json('payment_data')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};