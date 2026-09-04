<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxRule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxRuleController extends Controller
{
    /**
     * Display all tax rules.
     */
    public function index(Request $request)
    {
        $query = TaxRule::query();

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Applies To filter
        if ($request->filled('applies_to')) {
            $query->where('applies_to', $request->applies_to);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            }

            if ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $taxRules = $query
            ->orderBy('priority', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Statistics
        $stats = [
            'total' => TaxRule::count(),

            'active' => TaxRule::where('is_active', true)->count(),

            'inactive' => TaxRule::where('is_active', false)->count(),

            'percentage' => TaxRule::where('type', 'percentage')->count(),

            'fixed' => TaxRule::where('type', 'fixed')->count(),
        ];

        return view('admin.tax-rules.index', compact(
            'taxRules',
            'stats'
        ));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.tax-rules.create');
    }

    /**
     * Store a new tax rule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:191',
            ],

            'code' => [
                'required',
                'string',
                'max:100',
                'unique:tax_rules,code',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'type' => [
                'required',
                Rule::in([
                    'percentage',
                    'fixed',
                ]),
            ],

            'rate' => [
                'required',
                'numeric',
                'min:0',
            ],

            'applies_to' => [
                'required',
                Rule::in([
                    'booking',
                    'vendor_payout',
                    'both',
                ]),
            ],

            'priority' => [
                'nullable',
                'integer',
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

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        // Percentage tax cannot exceed 100%
        if (
            $validated['type'] === 'percentage' &&
            $validated['rate'] > 100
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'rate' => 'Percentage tax rate cannot exceed 100%.',
                ]);
        }

        $validated['code'] = strtoupper(trim($validated['code']));

        $validated['priority'] = $validated['priority'] ?? 0;

        $validated['is_active'] = $request->boolean('is_active');

        TaxRule::create($validated);

        return redirect()
            ->route('admin.tax-rules.index')
            ->with('success', 'Tax rule created successfully.');
    }

    /**
     * Display a single tax rule.
     */
    public function show($id)
    {
        $taxRule = TaxRule::findOrFail($id);

        return view('admin.tax-rules.show', compact('taxRule'));
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $taxRule = TaxRule::findOrFail($id);

        return view('admin.tax-rules.edit', compact('taxRule'));
    }

    /**
     * Update tax rule.
     */
    public function update(Request $request, $id)
    {
        $taxRule = TaxRule::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:191',
            ],

            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tax_rules', 'code')
                    ->ignore($taxRule->id),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'type' => [
                'required',
                Rule::in([
                    'percentage',
                    'fixed',
                ]),
            ],

            'rate' => [
                'required',
                'numeric',
                'min:0',
            ],

            'applies_to' => [
                'required',
                Rule::in([
                    'booking',
                    'vendor_payout',
                    'both',
                ]),
            ],

            'priority' => [
                'nullable',
                'integer',
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

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        // Percentage validation
        if (
            $validated['type'] === 'percentage' &&
            $validated['rate'] > 100
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'rate' => 'Percentage tax rate cannot exceed 100%.',
                ]);
        }

        $validated['code'] = strtoupper(trim($validated['code']));

        $validated['priority'] = $validated['priority'] ?? 0;

        $validated['is_active'] = $request->boolean('is_active');

        $taxRule->update($validated);

        return redirect()
            ->route('admin.tax-rules.index')
            ->with('success', 'Tax rule updated successfully.');
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus($id)
    {
        $taxRule = TaxRule::findOrFail($id);

        $taxRule->update([
            'is_active' => !$taxRule->is_active,
        ]);

        $status = $taxRule->is_active
            ? 'activated'
            : 'deactivated';

        return back()->with(
            'success',
            "Tax rule {$status} successfully."
        );
    }

    /**
     * Delete tax rule.
     */
    public function destroy($id)
    {
        $taxRule = TaxRule::findOrFail($id);

        $taxRule->delete();

        return redirect()
            ->route('admin.tax-rules.index')
            ->with('success', 'Tax rule deleted successfully.');
    }
}