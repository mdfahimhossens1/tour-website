<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'starts_at',
        'ends_at',
        'usage_limit',
        'usage_per_user',
        'used_count',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',

        'starts_at' => 'datetime',
        'ends_at' => 'datetime',

        'usage_limit' => 'integer',
        'usage_per_user' => 'integer',
        'used_count' => 'integer',

        'is_active' => 'boolean',
        'is_featured' => 'boolean',

        'sort_order' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopePercentage(Builder $query): Builder
    {
        return $query->where('type', 'percentage');
    }

    public function scopeFixed(Builder $query): Builder
    {
        return $query->where('type', 'fixed');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc');
    }

    public function scopeCurrentlyValid(Builder $query): Builder
    {
        $now = now();

        return $query
            ->where('is_active', true)
            ->where(function ($query) use ($now) {
                $query
                    ->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query
                    ->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $now);
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function hasStarted(): bool
    {
        return !$this->starts_at || $this->starts_at->lte(now());
    }

    public function hasExpired(): bool
    {
        return $this->ends_at && $this->ends_at->lt(now());
    }

    public function isCurrentlyValid(): bool
    {
        return $this->is_active
            && $this->hasStarted()
            && !$this->hasExpired();
    }

    public function hasUsageLimit(): bool
    {
        return !is_null($this->usage_limit);
    }

    public function isUsageLimitReached(): bool
    {
        if (!$this->hasUsageLimit()) {
            return false;
        }

        return $this->used_count >= $this->usage_limit;
    }

    public function remainingUsage(): ?int
    {
        if (!$this->hasUsageLimit()) {
            return null;
        }

        return max(
            0,
            $this->usage_limit - $this->used_count
        );
    }

    public function calculateDiscount(float $amount): float
    {
        $amount = max(0, $amount);

        if (!$this->isCurrentlyValid()) {
            return 0;
        }

        if ($amount < (float) $this->minimum_amount) {
            return 0;
        }

        if ($this->isUsageLimitReached()) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = $amount * ((float) $this->value / 100);

            if (!is_null($this->maximum_discount)) {
                $discount = min(
                    $discount,
                    (float) $this->maximum_discount
                );
            }

            return round(
                min($discount, $amount),
                2
            );
        }

        if ($this->type === 'fixed') {
            return round(
                min((float) $this->value, $amount),
                2
            );
        }

        return 0;
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    public function usages()
{
    return $this->hasMany(
        PromotionUsage::class,
        'promotion_id'
    );
}

public function userUsageCount(int $userId): int
{
    return $this->usages()
        ->where('user_id', $userId)
        ->where('status', 'used')
        ->count();
}

public function hasUserUsageLimit(): bool
{
    return !is_null($this->usage_per_user);
}

public function isUserUsageLimitReached(int $userId): bool
{
    if (!$this->hasUserUsageLimit()) {
        return false;
    }

    return $this->userUsageCount($userId)
        >= $this->usage_per_user;
}

}