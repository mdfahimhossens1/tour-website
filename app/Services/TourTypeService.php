<?php

namespace App\Services;

use App\Models\TourType;

class TourTypeService
{
    /**
     * Get all active tour types
     */
    public function getAll()
    {
        return TourType::withCount('tours')
            ->where('status', 1)
            ->latest()
            ->get();
    }

    /**
     * Get tour type by slug
     */
    public function getBySlug($slug)
    {
        return TourType::with([
            'tours' => function ($query) {
                $query->where('status', 1);
            }
        ])
        ->where('slug', $slug)
        ->where('status', 1)
        ->firstOrFail();
    }

    /**
     * Get tour type by ID
     */
    public function getById($id)
    {
        return TourType::findOrFail($id);
    }
}