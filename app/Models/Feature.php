<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    /**
     * ---------------------------------------------------------
     * Table
     * ---------------------------------------------------------
     */
    protected $table = 'features';

    /**
     * ---------------------------------------------------------
     * Mass Assignable Fields
     * ---------------------------------------------------------
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'key',
        'category',
        'icon',
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
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * ---------------------------------------------------------
     * Relationships
     * ---------------------------------------------------------
     */

    /**
     * A feature can belong to many subscription plans.
     */
    public function subscriptionPlans(): BelongsToMany
    {
        return $this->belongsToMany(
            SubscriptionPlan::class,
            'plan_features',
            'feature_id',
            'subscription_plan_id'
        )
        ->withPivot('is_enabled')
        ->withTimestamps();
    }

    /**
     * ---------------------------------------------------------
     * Scopes
     * ---------------------------------------------------------
     */

    /**
     * Only active features.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Only inactive features.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * Order features.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    /**
     * Filter by category.
     */
    public function scopeCategory(
        Builder $query,
        string $category
    ): Builder {
        return $query->where('category', $category);
    }

    /**
     * ---------------------------------------------------------
     * Helper Methods
     * ---------------------------------------------------------
     */

    /**
     * Check whether feature is active.
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Get readable category.
     */
    public function getCategoryLabel(): string
    {
        return $this->category
            ? ucwords(str_replace('_', ' ', $this->category))
            : 'General';
    }
}