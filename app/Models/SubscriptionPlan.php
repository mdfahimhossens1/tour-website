<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubscriptionPlan extends Model
{
    /**
     * ---------------------------------------------------------
     * Table
     * ---------------------------------------------------------
     */
    protected $table = 'subscription_plans';

    /**
     * ---------------------------------------------------------
     * Mass Assignable Fields
     * ---------------------------------------------------------
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'currency',
        'max_vendors',
        'max_tours',
        'max_bookings',
        'max_storage_mb',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    /**
     * ---------------------------------------------------------
     * Attribute Casting
     * ---------------------------------------------------------
     */
    protected function casts(): array
    {
        return [
            'price'           => 'decimal:2',
            'max_vendors'     => 'integer',
            'max_tours'       => 'integer',
            'max_bookings'    => 'integer',
            'max_storage_mb'  => 'integer',
            'is_featured'     => 'boolean',
            'is_active'       => 'boolean',
            'sort_order'      => 'integer',
        ];
    }

    /**
     * ---------------------------------------------------------
     * Relationships
     * ---------------------------------------------------------
     */

    /**
     * A subscription plan can have many subscriptions.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * A subscription plan can have many billing histories.
     */
    public function billingHistories(): HasMany
    {
        return $this->hasMany(BillingHistory::class);
    }

    /**
     * ---------------------------------------------------------
     * Scopes
     * ---------------------------------------------------------
     */

    /**
     * Only active plans.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Only inactive plans.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * Only featured plans.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Only monthly plans.
     */
    public function scopeMonthly(Builder $query): Builder
    {
        return $query->where('billing_cycle', 'monthly');
    }

    /**
     * Only yearly plans.
     */
    public function scopeYearly(Builder $query): Builder
    {
        return $query->where('billing_cycle', 'yearly');
    }

    /**
     * Only lifetime plans.
     */
    public function scopeLifetime(Builder $query): Builder
    {
        return $query->where('billing_cycle', 'lifetime');
    }

    /**
     * Plans ordered by sort order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('sort_order')
            ->orderBy('price');
    }

    /**
     * ---------------------------------------------------------
     * Helper Methods
     * ---------------------------------------------------------
     */

    /**
     * Check whether the plan is active.
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Check whether the plan is featured.
     */
    public function isFeatured(): bool
    {
        return $this->is_featured === true;
    }

    /**
     * Check whether this is a lifetime plan.
     */
    public function isLifetime(): bool
    {
        return $this->billing_cycle === 'lifetime';
    }

    /**
     * Check whether this is a monthly plan.
     */
    public function isMonthly(): bool
    {
        return $this->billing_cycle === 'monthly';
    }

    /**
     * Check whether this is a yearly plan.
     */
    public function isYearly(): bool
    {
        return $this->billing_cycle === 'yearly';
    }

    /**
     * Check whether vendor limit is unlimited.
     */
    public function hasUnlimitedVendors(): bool
    {
        return is_null($this->max_vendors);
    }

    /**
     * Check whether tour limit is unlimited.
     */
    public function hasUnlimitedTours(): bool
    {
        return is_null($this->max_tours);
    }

    /**
     * Check whether booking limit is unlimited.
     */
    public function hasUnlimitedBookings(): bool
    {
        return is_null($this->max_bookings);
    }

    /**
     * Check whether storage limit is unlimited.
     */
    public function hasUnlimitedStorage(): bool
    {
        return is_null($this->max_storage_mb);
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


    /**
     * A subscription plan can have many features.
     */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(
            Feature::class,
            'plan_features',
            'subscription_plan_id',
            'feature_id'
        )
        ->withPivot('is_enabled')
        ->withTimestamps();
    }

}