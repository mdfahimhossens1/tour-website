<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    /**
     * ----------------------------------------------------------
     * Fillable
     * ----------------------------------------------------------
     */
    protected $fillable = [
        'name',
        'designation',
        'bio',
        'email',
        'phone',
        'image',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'twitter_url',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    /**
     * ----------------------------------------------------------
     * Casts
     * ----------------------------------------------------------
     */
    protected $casts = [
        'sort_order'  => 'integer',
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * ----------------------------------------------------------
     * Active Scope
     * ----------------------------------------------------------
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * ----------------------------------------------------------
     * Inactive Scope
     * ----------------------------------------------------------
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * ----------------------------------------------------------
     * Featured Scope
     * ----------------------------------------------------------
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * ----------------------------------------------------------
     * Ordered Scope
     * ----------------------------------------------------------
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc');
    }

    /**
     * ----------------------------------------------------------
     * Active + Ordered
     * ----------------------------------------------------------
     */
    public function scopeActiveOrdered(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc');
    }

    /**
     * ----------------------------------------------------------
     * Image URL
     * ----------------------------------------------------------
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return asset('storage/' . ltrim($this->image, '/'));
    }
}