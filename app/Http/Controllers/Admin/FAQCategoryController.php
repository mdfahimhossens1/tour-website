<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FAQCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FAQCategoryController extends Controller
{
    public function index()
    {
        $categories = FAQCategory::latest()->paginate(20);

        return view('admin.faq_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $slug = Str::slug($request->name);

        if (FAQCategory::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        FAQCategory::create([
            'name'   => $request->name,
            'slug'   => $slug,
            'status' => $request->status ?? 1,
            'serial' => $request->serial ?? 0,
        ]);

        return redirect()
            ->route('admin.faq.categories.index')
            ->with('success', 'FAQ Category Created Successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $category = FAQCategory::findOrFail($id);

        $slug = Str::slug($request->name);

        $exists = FAQCategory::where('slug', $slug)
            ->where('id', '!=', $category->id)
            ->exists();

        if ($exists) {
            $slug .= '-' . time();
        }

        $category->update([
            'name'   => $request->name,
            'slug'   => $slug,
            'status' => $request->status ?? 1,
            'serial' => $request->serial ?? 0,
        ]);

        return redirect()
            ->route('admin.faq.categories.index')
            ->with('success', 'FAQ Category Updated Successfully.');
    }

    public function destroy($id)
    {
        $category = FAQCategory::findOrFail($id);

        // Prevent delete if FAQs exist
        if ($category->faqs()->count() > 0) {
            return redirect()
                ->route('admin.faq.categories.index')
                ->with('error', 'This category contains FAQs. Delete FAQs first.');
        }

        $category->delete();

        return redirect()
            ->route('admin.faq.categories.index')
            ->with('success', 'FAQ Category Deleted Successfully.');
    }
}