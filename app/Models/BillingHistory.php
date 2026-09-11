<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingHistory extends Model
{
    protected $table = 'billing_histories';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'subscription_plan_id',

        'invoice_number',
        'transaction_id',
        'gateway_invoice_id',
        'gateway_transaction_id',

        'amount',
        'discount',
        'tax',
        'total_amount',
        'currency',

        'billing_cycle',

        'payment_method',
        'payment_gateway',

        'status',

        'billing_date',
        'paid_at',
        'due_at',

        'refunded_amount',
        'refunded_at',
        'refund_reason',

        'description',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'refunded_amount' => 'decimal:2',

            'billing_date' => 'datetime',
            'paid_at' => 'datetime',
            'due_at' => 'datetime',
            'refunded_at' => 'datetime',

            'metadata' => 'array',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(
            Subscription::class,
            'subscription_id'
        );
    }

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(
            SubscriptionPlan::class,
            'subscription_plan_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', 'failed');
    }

    public function scopeRefunded(Builder $query): Builder
    {
        return $query->where('status', 'refunded');
    }

    public function scopePartiallyRefunded(Builder $query): Builder
    {
        return $query->where('status', 'partially_refunded');
    }

    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeMonthly(Builder $query): Builder
    {
        return $query->where('billing_cycle', 'monthly');
    }

    public function scopeYearly(Builder $query): Builder
    {
        return $query->where('billing_cycle', 'yearly');
    }

    public function scopeLifetime(Builder $query): Builder
    {
        return $query->where('billing_cycle', 'lifetime');
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function isPartiallyRefunded(): bool
    {
        return $this->status === 'partially_refunded';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }


    /*
    |--------------------------------------------------------------------------
    | Billing Cycle Helpers
    |--------------------------------------------------------------------------
    */

    public function isMonthly(): bool
    {
        return $this->billing_cycle === 'monthly';
    }

    public function isYearly(): bool
    {
        return $this->billing_cycle === 'yearly';
    }

    public function isLifetime(): bool
    {
        return $this->billing_cycle === 'lifetime';
    }


    /*
    |--------------------------------------------------------------------------
    | Refund Helpers
    |--------------------------------------------------------------------------
    */

    public function hasRefund(): bool
    {
        return (float) $this->refunded_amount > 0;
    }

    public function isFullyRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function isPartiallyRefundedAmount(): bool
    {
        return $this->status === 'partially_refunded';
    }


    /*
    |--------------------------------------------------------------------------
    | Payment Helpers
    |--------------------------------------------------------------------------
    */

    public function hasPaymentGateway(): bool
    {
        return !empty($this->payment_gateway);
    }

    public function hasTransaction(): bool
    {
        return !empty($this->transaction_id);
    }


    /*
    |--------------------------------------------------------------------------
    | Display Helpers
    |--------------------------------------------------------------------------
    */

    public function getStatusLabel(): string
    {
        return match ($this->status) {

            'pending' =>
                'Pending',

            'paid' =>
                'Paid',

            'failed' =>
                'Failed',

            'refunded' =>
                'Refunded',

            'partially_refunded' =>
                'Partially Refunded',

            'cancelled' =>
                'Cancelled',

            default =>
                ucfirst(
                    str_replace(
                        '_',
                        ' ',
                        $this->status ?? 'Unknown'
                    )
                ),
        };
    }

    public function getBillingCycleLabel(): string
    {
        return match ($this->billing_cycle) {

            'monthly' =>
                'Monthly',

            'yearly' =>
                'Yearly',

            'lifetime' =>
                'Lifetime',

            default =>
                ucfirst(
                    $this->billing_cycle ?? 'Unknown'
                ),
        };
    }

    public function getFormattedAmount(): string
    {
        return ($this->currency ?? 'USD')
            . ' '
            . number_format(
                (float) ($this->total_amount ?? 0),
                2
            );
    }

    public function getFormattedRefundedAmount(): string
    {
        return ($this->currency ?? 'USD')
            . ' '
            . number_format(
                (float) ($this->refunded_amount ?? 0),
                2
            );
    }
}