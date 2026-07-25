<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{

public function index()
{
    $members = TeamMember::orderBy('sort_order')
        ->latest('id')
        ->get();

    return view('admin.team-members.index', compact('members'));
}

public function create()
{
    return view('admin.team-members.create');
}

public function store(Request $request)
{
    $request->validate([

        'name' => 'required|string|max:255',

        'designation_en' => 'required|string|max:255',
        'designation_bn' => 'required|string|max:255',

        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:50',

        'facebook' => 'nullable|url',
        'linkedin' => 'nullable|url',
        'twitter' => 'nullable|url',

        'bio_en' => 'nullable',
        'bio_bn' => 'nullable',

        'status' => 'required|boolean',

        'sort_order' => 'nullable|integer',

    ]);

    $data = $request->except('image');

    if ($request->hasFile('image')) {

        $data['image'] = $request
            ->file('image')
            ->store('team-members', 'public');
    }

    TeamMember::create($data);

    return redirect()
        ->route('admin.team-members.index')
        ->with('success', 'Team member added successfully.');
}

public function edit(TeamMember $teamMember)
{
    return view(
        'admin.team-members.edit',
        compact('teamMember')
    );
}

public function update(Request $request, TeamMember $teamMember)
{
    $request->validate([

        'name' => 'required|string|max:255',

        'designation_en' => 'required|string|max:255',
        'designation_bn' => 'required|string|max:255',

        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:50',

        'facebook' => 'nullable|url',
        'linkedin' => 'nullable|url',
        'twitter' => 'nullable|url',

        'bio_en' => 'nullable',
        'bio_bn' => 'nullable',

        'status' => 'required|boolean',

        'sort_order' => 'nullable|integer',

    ]);

    $data = $request->except('image');

    if ($request->hasFile('image')) {

        if (
            $teamMember->image &&
            Storage::disk('public')->exists($teamMember->image)
        ) {
            Storage::disk('public')->delete($teamMember->image);
        }

        $data['image'] = $request
            ->file('image')
            ->store('team-members', 'public');
    }

    $teamMember->update($data);

    return redirect()
        ->route('team-members.index')
        ->with('success', 'Team member updated successfully.');
}

public function destroy(TeamMember $teamMember)
{
    if (
        $teamMember->image &&
        Storage::disk('public')->exists($teamMember->image)
    ) {
        Storage::disk('public')->delete($teamMember->image);
    }

    $teamMember->delete();

    return redirect()
        ->route('admin.team-members.index')
        ->with('success', 'Team member deleted successfully.');
}

public function toggleStatus(TeamMember $teamMember)
{
    $teamMember->update([
        'status' => !$teamMember->status
    ]);

    return back()->with(
        'success',
        'Status updated successfully.'
    );
}

}