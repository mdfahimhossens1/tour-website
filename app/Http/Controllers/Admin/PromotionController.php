<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PromotionController extends Controller
{
    /**
     * Display all promotions.
     */
    public function index(Request $request)
    {
        $query = Promotion::query();

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Type filter
        if ($request->filled('type')) {
            if (in_array($request->type, ['percentage', 'fixed'])) {
                $query->where('type', $request->type);
            }
        }

        // Featured filter
        if ($request->filled('featured')) {
            if ($request->featured === 'yes') {
                $query->where('is_featured', true);
            } elseif ($request->featured === 'no') {
                $query->where('is_featured', false);
            }
        }

        // Validity filter
        if ($request->filled('validity')) {
            if ($request->validity === 'valid') {
                $query->currentlyValid();
            } elseif ($request->validity === 'expired') {
                $query->whereNotNull('ends_at')
                    ->where('ends_at', '<', now());
            } elseif ($request->validity === 'upcoming') {
                $query->whereNotNull('starts_at')
                    ->where('starts_at', '>', now());
            }
        }

        $promotions = $query
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        // Statistics
        $totalPromotions = Promotion::count();

        $activePromotions = Promotion::where('is_active', true)
            ->count();

        $inactivePromotions = Promotion::where('is_active', false)
            ->count();

        $featuredPromotions = Promotion::where('is_featured', true)
            ->count();

        $currentlyValidPromotions = Promotion::currentlyValid()
            ->count();

        $expiredPromotions = Promotion::whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->count();

        return view(
            'admin.promotions.index',
            compact(
                'promotions',
                'totalPromotions',
                'activePromotions',
                'inactivePromotions',
                'featuredPromotions',
                'currentlyValidPromotions',
                'expiredPromotions'
            )
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.promotions.create');
    }

    /**
     * Store a new promotion.
     */
    public function store(Request $request)
    {
        $validated = $this->validatePromotion($request);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['used_count'] = 0;

        Promotion::create($validated);

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Promotion created successfully.');
    }

    /**
     * Show promotion details.
     */
    public function show($id)
    {
        $promotion = Promotion::findOrFail($id);

        return view(
            'admin.promotions.show',
            compact('promotion')
        );
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);

        return view(
            'admin.promotions.edit',
            compact('promotion')
        );
    }

    /**
     * Update promotion.
     */
    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $validated = $this->validatePromotion(
            $request,
            $promotion->id
        );

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $promotion->update($validated);

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Promotion updated successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus($id)
    {
        $promotion = Promotion::findOrFail($id);

        $promotion->update([
            'is_active' => !$promotion->is_active,
        ]);

        return back()->with(
            'success',
            $promotion->is_active
                ? 'Promotion activated successfully.'
                : 'Promotion deactivated successfully.'
        );
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured($id)
    {
        $promotion = Promotion::findOrFail($id);

        $promotion->update([
            'is_featured' => !$promotion->is_featured,
        ]);

        return back()->with(
            'success',
            $promotion->is_featured
                ? 'Promotion marked as featured.'
                : 'Promotion removed from featured.'
        );
    }

    /**
     * Delete promotion.
     */
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);

        $promotion->delete();

        return back()->with(
            'success',
            'Promotion deleted successfully.'
        );
    }

    /**
     * Validate promotion data.
     */
    protected function validatePromotion(
        Request $request,
        ?int $promotionId = null
    ): array {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('promotions', 'code')
                    ->ignore($promotionId),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'type' => [
                'required',
                Rule::in(['percentage', 'fixed']),
            ],

            'value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'minimum_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'maximum_discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'starts_at' => [
                'nullable',
                'date',
            ],

            'ends_at' => [
                'nullable',
                'date',
                'after_or_equal:starts_at',
            ],

            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'usage_per_user' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:999999',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],
        ]);
    }
}