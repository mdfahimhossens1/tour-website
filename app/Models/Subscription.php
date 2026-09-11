<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $attributes = [
        'status' => 'pending',
        'price' => 0,
        'currency' => 'USD',
        'billing_cycle' => 'monthly',
        'is_trial' => false,
        'cancel_at_period_end' => false,
    ];

    protected $fillable = [

        'user_id',
        'subscription_plan_id',

        'subscription_code',

        'status',

        'price',
        'currency',
        'billing_cycle',

        'is_trial',
        'trial_starts_at',
        'trial_ends_at',

        'starts_at',
        'ends_at',
        'next_billing_at',

        'cancel_at_period_end',
        'cancelled_at',
        'cancellation_reason',

        'payment_method',
        'payment_gateway',

        'gateway_subscription_id',
        'gateway_customer_id',

        'metadata',
    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            'price' => 'decimal:2',

            'is_trial' => 'boolean',

            'trial_starts_at' => 'datetime',
            'trial_ends_at' => 'datetime',

            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'next_billing_at' => 'datetime',

            'cancel_at_period_end' => 'boolean',
            'cancelled_at' => 'datetime',

            'metadata' => 'array',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Subscription owner.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Subscription plan.
     */
    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(
            SubscriptionPlan::class,
            'subscription_plan_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Only active subscriptions.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }


    /**
     * Only trial subscriptions.
     */
    public function scopeTrialing(Builder $query): Builder
    {
        return $query->where('status', 'trialing');
    }


    /**
     * Only pending subscriptions.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }


    /**
     * Only cancelled subscriptions.
     */
    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', 'cancelled');
    }


    /**
     * Only expired subscriptions.
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', 'expired');
    }


    /**
     * Only suspended subscriptions.
     */
    public function scopeSuspended(Builder $query): Builder
    {
        return $query->where('status', 'suspended');
    }


    /**
     * Only past due subscriptions.
     */
    public function scopePastDue(Builder $query): Builder
    {
        return $query->where('status', 'past_due');
    }


    /**
     * Trial subscriptions.
     */
    public function scopeTrials(Builder $query): Builder
    {
        return $query->where('is_trial', true);
    }


    /**
     * Non-trial subscriptions.
     */
    public function scopeNonTrials(Builder $query): Builder
    {
        return $query->where('is_trial', false);
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Is subscription active?
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }


    /**
     * Is subscription trialing?
     */
    public function isTrialing(): bool
    {
        return $this->status === 'trialing';
    }


    /**
     * Is subscription pending?
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }


    /**
     * Is subscription cancelled?
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }


    /**
     * Is subscription expired?
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }


    /**
     * Is subscription suspended?
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }


    /**
     * Is subscription past due?
     */
    public function isPastDue(): bool
    {
        return $this->status === 'past_due';
    }


    /*
    |--------------------------------------------------------------------------
    | Billing Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Is lifetime subscription?
     */
    public function isLifetime(): bool
    {
        return $this->billing_cycle === 'lifetime';
    }


    /**
     * Is monthly subscription?
     */
    public function isMonthly(): bool
    {
        return $this->billing_cycle === 'monthly';
    }


    /**
     * Is yearly subscription?
     */
    public function isYearly(): bool
    {
        return $this->billing_cycle === 'yearly';
    }


    /*
    |--------------------------------------------------------------------------
    | Trial Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Does this subscription have a trial?
     */
    public function hasTrial(): bool
    {
        return $this->is_trial === true;
    }


    /**
     * Has trial started?
     */
    public function hasTrialStarted(): bool
    {
        if (!$this->is_trial) {
            return false;
        }

        if (!$this->trial_starts_at) {
            return true;
        }

        return now()->greaterThanOrEqualTo(
            $this->trial_starts_at
        );
    }


    /**
     * Has trial expired?
     */
    public function hasTrialExpired(): bool
    {
        if (!$this->is_trial) {
            return false;
        }

        if (!$this->trial_ends_at) {
            return false;
        }

        return now()->greaterThan(
            $this->trial_ends_at
        );
    }


    /**
     * Is trial currently active?
     */
    public function isTrialCurrentlyActive(): bool
    {
        if (!$this->is_trial) {
            return false;
        }

        if (
            $this->trial_starts_at &&
            now()->lessThan($this->trial_starts_at)
        ) {
            return false;
        }

        if (
            $this->trial_ends_at &&
            now()->greaterThan($this->trial_ends_at)
        ) {
            return false;
        }

        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | Period Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Has subscription started?
     */
    public function hasStarted(): bool
    {
        if (!$this->starts_at) {
            return true;
        }

        return now()->greaterThanOrEqualTo(
            $this->starts_at
        );
    }


    /**
     * Has subscription ended?
     */
    public function hasEnded(): bool
    {
        if ($this->isLifetime()) {
            return false;
        }

        if (!$this->ends_at) {
            return false;
        }

        return now()->greaterThan(
            $this->ends_at
        );
    }


    /**
     * Is subscription currently within its period?
     */
    public function isCurrentlyValid(): bool
    {
        if (
            !$this->isActive() &&
            !$this->isTrialing()
        ) {
            return false;
        }

        if (!$this->hasStarted()) {
            return false;
        }

        if ($this->hasEnded()) {
            return false;
        }

        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | Cancellation Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Is cancellation scheduled?
     */
    public function isCancellationScheduled(): bool
    {
        return $this->cancel_at_period_end === true;
    }


    /**
     * Was subscription cancelled?
     */
    public function wasCancelled(): bool
    {
        return !is_null($this->cancelled_at);
    }


    /*
    |--------------------------------------------------------------------------
    | Display Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Get readable status.
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {

            'pending'   => 'Pending',

            'active'    => 'Active',

            'trialing'  => 'Trialing',

            'past_due'  => 'Past Due',

            'cancelled' => 'Cancelled',

            'expired'   => 'Expired',

            'suspended' => 'Suspended',

            default     => ucfirst($this->status),
        };
    }


    /**
     * Get readable billing cycle.
     */
    public function getBillingCycleLabel(): string
    {
        return match ($this->billing_cycle) {

            'monthly'  => 'Monthly',

            'yearly'   => 'Yearly',

            'lifetime' => 'Lifetime',

            default    => ucfirst($this->billing_cycle),
        };
    }


    /**
     * Get formatted price.
     */
    public function getFormattedPrice(): string
    {
        return $this->currency . ' ' . number_format(
            (float) $this->price,
            2
        );
    }


    /*
|--------------------------------------------------------------------------
| Trial Management
|--------------------------------------------------------------------------
*/

/**
 * Start a trial for this subscription.
 */
public function startTrial(?TrialSetting $settings = null): self
{
    $settings ??= TrialSetting::current();

    $now = now();

    $trialEndsAt = $now->copy()
        ->addDays($settings->trial_days);

    $this->update([
        'status' => 'trialing',

        'is_trial' => true,

        'trial_starts_at' => $now,

        'trial_ends_at' => $trialEndsAt,

        'starts_at' => $now,

        'ends_at' => $trialEndsAt,

        'cancel_at_period_end' => false,

        'cancelled_at' => null,

        'cancellation_reason' => null,
    ]);

    return $this->fresh();
}




/**
 * Get remaining trial days.
 */
public function getRemainingTrialDays(): int
{
    if (! $this->isTrialCurrentlyActive()) {
        return 0;
    }

    return max(
        0,
        now()->diffInDays(
            $this->trial_ends_at,
            false
        )
    );
}


/**
 * Get trial duration in days.
 */
public function getTrialDurationDays(): int
{
    if (! $this->trial_starts_at || ! $this->trial_ends_at) {
        return 0;
    }

    return $this->trial_starts_at->diffInDays(
        $this->trial_ends_at
    );
}

}