<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeamMemberController extends Controller
{
    /**
     * ----------------------------------------------------------
     * Display all team members
     * ----------------------------------------------------------
     */
    public function index(Request $request)
    {
        $query = TeamMember::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Featured Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('featured')) {
            if ($request->featured === 'yes') {
                $query->where('is_featured', true);
            } elseif ($request->featured === 'no') {
                $query->where('is_featured', false);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Ordering
        |--------------------------------------------------------------------------
        */
        $teamMembers = $query
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */
        $totalMembers = TeamMember::count();

        $activeMembers = TeamMember::where('is_active', true)->count();

        $inactiveMembers = TeamMember::where('is_active', false)->count();

        $featuredMembers = TeamMember::where('is_featured', true)->count();

        return view('admin.team-members.index', compact(
            'teamMembers',
            'totalMembers',
            'activeMembers',
            'inactiveMembers',
            'featuredMembers'
        ));
    }

    /**
     * ----------------------------------------------------------
     * Show create form
     * ----------------------------------------------------------
     */
    public function create()
    {
        return view('admin.team-members.create');
    }

    /**
     * ----------------------------------------------------------
     * Store new team member
     * ----------------------------------------------------------
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'bio' => [
                'nullable',
                'string',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'facebook_url' => [
                'nullable',
                'url',
                'max:500',
            ],

            'instagram_url' => [
                'nullable',
                'url',
                'max:500',
            ],

            'linkedin_url' => [
                'nullable',
                'url',
                'max:500',
            ],

            'twitter_url' => [
                'nullable',
                'url',
                'max:500',
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

        /*
        |--------------------------------------------------------------------------
        | Image Upload
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('image')) {
            $validated['image'] = $request
                ->file('image')
                ->store('team-members', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Checkbox Defaults
        |--------------------------------------------------------------------------
        */
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */
        TeamMember::create($validated);

        return redirect()
            ->route('admin.team-members.index')
            ->with('success', 'Team member created successfully.');
    }

    /**
     * ----------------------------------------------------------
     * Display team member details
     * ----------------------------------------------------------
     */
    public function show($id)
    {
        $teamMember = TeamMember::findOrFail($id);

        return view(
            'admin.team-members.show',
            compact('teamMember')
        );
    }

    /**
     * ----------------------------------------------------------
     * Show edit form
     * ----------------------------------------------------------
     */
    public function edit($id)
    {
        $teamMember = TeamMember::findOrFail($id);

        return view(
            'admin.team-members.edit',
            compact('teamMember')
        );
    }

    /**
     * ----------------------------------------------------------
     * Update team member
     * ----------------------------------------------------------
     */
    public function update(Request $request, $id)
    {
        $teamMember = TeamMember::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'bio' => [
                'nullable',
                'string',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'facebook_url' => [
                'nullable',
                'url',
                'max:500',
            ],

            'instagram_url' => [
                'nullable',
                'url',
                'max:500',
            ],

            'linkedin_url' => [
                'nullable',
                'url',
                'max:500',
            ],

            'twitter_url' => [
                'nullable',
                'url',
                'max:500',
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

        /*
        |--------------------------------------------------------------------------
        | Image Upload
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('image')) {

            // Delete old image
            if ($teamMember->image) {
                Storage::disk('public')->delete(
                    $teamMember->image
                );
            }

            // Store new image
            $validated['image'] = $request
                ->file('image')
                ->store('team-members', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Checkbox Defaults
        |--------------------------------------------------------------------------
        */
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */
        $teamMember->update($validated);

        return redirect()
            ->route('admin.team-members.index')
            ->with('success', 'Team member updated successfully.');
    }

    /**
     * ----------------------------------------------------------
     * Toggle Active / Inactive
     * ----------------------------------------------------------
     */
    public function toggleStatus($id)
    {
        $teamMember = TeamMember::findOrFail($id);

        $teamMember->update([
            'is_active' => !$teamMember->is_active,
        ]);

        return back()->with(
            'success',
            $teamMember->is_active
                ? 'Team member activated successfully.'
                : 'Team member deactivated successfully.'
        );
    }

    /**
     * ----------------------------------------------------------
     * Toggle Featured / Not Featured
     * ----------------------------------------------------------
     */
    public function toggleFeatured($id)
    {
        $teamMember = TeamMember::findOrFail($id);

        $teamMember->update([
            'is_featured' => !$teamMember->is_featured,
        ]);

        return back()->with(
            'success',
            $teamMember->is_featured
                ? 'Team member marked as featured.'
                : 'Team member removed from featured.'
        );
    }

    /**
     * ----------------------------------------------------------
     * Delete team member
     * ----------------------------------------------------------
     */
    public function destroy($id)
    {
        $teamMember = TeamMember::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Delete Profile Image
        |--------------------------------------------------------------------------
        */
        if ($teamMember->image) {
            Storage::disk('public')->delete(
                $teamMember->image
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Record
        |--------------------------------------------------------------------------
        */
        $teamMember->delete();

        return back()->with(
            'success',
            'Team member deleted successfully.'
        );
    }
}