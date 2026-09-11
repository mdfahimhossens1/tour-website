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
        Schema::create('subscriptions', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Subscription Plan
            |--------------------------------------------------------------------------
            */
            $table->foreignId('subscription_plan_id')
                ->constrained('subscription_plans')
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Subscription Reference
            |--------------------------------------------------------------------------
            */
            $table->string('subscription_code')->unique();


 
            $table->enum('status', [
                'pending',
                'active',
                'trialing',
                'past_due',
                'cancelled',
                'expired',
                'suspended',
            ])->default('pending');


            /*
            |--------------------------------------------------------------------------
            | Billing Information
            |--------------------------------------------------------------------------
            */
            $table->decimal('price', 12, 2)->default(0);

            $table->string('currency', 10)->default('USD');

            $table->enum('billing_cycle', [
                'monthly',
                'yearly',
                'lifetime',
            ])->default('monthly');


            /*
            |--------------------------------------------------------------------------
            | Trial
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_trial')->default(false);

            $table->dateTime('trial_starts_at')->nullable();

            $table->dateTime('trial_ends_at')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Subscription Period
            |--------------------------------------------------------------------------
            */
            $table->dateTime('starts_at')->nullable();

            $table->dateTime('ends_at')->nullable();

            $table->dateTime('next_billing_at')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Cancellation
            |--------------------------------------------------------------------------
            */
            $table->boolean('cancel_at_period_end')->default(false);

            $table->dateTime('cancelled_at')->nullable();

            $table->text('cancellation_reason')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Payment Provider
            |--------------------------------------------------------------------------
            */
            $table->string('payment_method')->nullable();

            $table->string('payment_gateway')->nullable();

            $table->string('gateway_subscription_id')->nullable();

            $table->string('gateway_customer_id')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */
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

            $table->index('subscription_plan_id');

            $table->index('status');

            $table->index('is_trial');

            $table->index('starts_at');

            $table->index('ends_at');

            $table->index('next_billing_at');

            $table->index('gateway_subscription_id');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};