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
        Schema::create('billing_histories', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Subscription
            |--------------------------------------------------------------------------
            */

            $table->foreignId('subscription_id')
                ->nullable()
                ->constrained('subscriptions')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Subscription Plan
            |--------------------------------------------------------------------------
            */

            $table->foreignId('subscription_plan_id')
                ->nullable()
                ->constrained('subscription_plans')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Billing Reference
            |--------------------------------------------------------------------------
            */

            $table->string('invoice_number')->unique();

            $table->string('transaction_id')->nullable();

            $table->string('gateway_invoice_id')->nullable();

            $table->string('gateway_transaction_id')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Billing Information
            |--------------------------------------------------------------------------
            */

            $table->decimal('amount', 12, 2)->default(0);

            $table->decimal('discount', 12, 2)->default(0);

            $table->decimal('tax', 12, 2)->default(0);

            $table->decimal('total_amount', 12, 2)->default(0);

            $table->string('currency', 10)->default('USD');


            /*
            |--------------------------------------------------------------------------
            | Billing Cycle
            |--------------------------------------------------------------------------
            */

            $table->enum('billing_cycle', [
                'monthly',
                'yearly',
                'lifetime',
            ])->default('monthly');


            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            $table->string('payment_method')->nullable();

            $table->string('payment_gateway')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'refunded',
                'partially_refunded',
                'cancelled',
            ])->default('pending');


            /*
            |--------------------------------------------------------------------------
            | Billing Dates
            |--------------------------------------------------------------------------
            */

            $table->dateTime('billing_date')->nullable();

            $table->dateTime('paid_at')->nullable();

            $table->dateTime('due_at')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Refund Information
            |--------------------------------------------------------------------------
            */

            $table->decimal('refunded_amount', 12, 2)->default(0);

            $table->dateTime('refunded_at')->nullable();

            $table->text('refund_reason')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Additional Information
            |--------------------------------------------------------------------------
            */

            $table->text('description')->nullable();

            $table->json('metadata')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('user_id');

            $table->index('subscription_id');

            $table->index('subscription_plan_id');

            $table->index('transaction_id');

            $table->index('status');

            $table->index('billing_cycle');

            $table->index('billing_date');

            $table->index('paid_at');

            $table->index('payment_gateway');

            $table->index('created_at');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_histories');
    }
};