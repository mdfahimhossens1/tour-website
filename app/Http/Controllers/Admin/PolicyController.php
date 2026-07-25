<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::orderBy('id')->get()->keyBy('type');

        return view('admin.policies.index', compact('policies'));
    }

public function update(Request $request)
{
    $request->validate([
        'policies' => ['required', 'array'],
    ]);

    DB::transaction(function () use ($request) {

        foreach ($request->policies as $data) {

            $policy = Policy::findOrFail($data['id']);

            $policy->update([
                'title_en'   => $data['title_en'] ?? '',
                'title_bn'   => $data['title_bn'] ?? '',
                'content_en' => $data['content_en'] ?? '',
                'content_bn' => $data['content_bn'] ?? '',
                'status'     => isset($data['status']) ? 1 : 0,
            ]);
        }

    });

    return redirect()
        ->back()
        ->with('success', 'Policy pages updated successfully.');
}
}