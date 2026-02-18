<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private function myRole(): string
    {
        return strtolower(optional(auth()->user()->role)->role_name ?? 'user');
    }

    private function abortIfNotStaff(): void
    {
        if (!in_array($this->myRole(), ['super admin', 'admin', 'manager'])) {
            abort(403, 'Unauthorized');
        }
    }

    private function uploadImage(Request $request, ?string $old = null): ?string
    {
        if (!$request->hasFile('image')) return $old;

        // delete old
        if ($old && file_exists(public_path('uploads/categories/' . $old))) {
            @unlink(public_path('uploads/categories/' . $old));
        }

        $file = $request->file('image');
        $ext = strtolower($file->getClientOriginalExtension());
        $name = 'cat_' . time() . '_' . uniqid() . '.' . $ext;
        $file->move(public_path('uploads/categories'), $name);

        return $name;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $base = $slug;
        $i = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    public function index()
    {
        $this->abortIfNotStaff();

        $categories = Category::orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('admin.catalog.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->abortIfNotStaff();

        // parent dropdown (only active parents, optional)
        $parents = Category::whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.catalog.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $this->abortIfNotStaff();

        $request->validate([
            'name'       => 'required|string|max:255|unique:categories,name',
            'parent_id'  => 'nullable|exists:categories,id',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'is_active'  => 'required|in:0,1',
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ]);

        $imageName = $this->uploadImage($request, null);
        $slug = $this->uniqueSlug($request->name);

        Category::create([
            'name'       => $request->name,
            'slug'       => $slug,
            'parent_id'  => $request->parent_id ?: null,
            'image'      => $imageName,
            'is_active'  => (int) $request->is_active,
            'sort_order' => (int) ($request->sort_order ?? 0),
        ]);

        return redirect()->route('dashboard.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $this->abortIfNotStaff();

        $category = Category::findOrFail($id);

        // parent dropdown: exclude self
        $parents = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.catalog.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $this->abortIfNotStaff();

        $category = Category::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:255|unique:categories,name,' . $category->id,
            'parent_id'  => 'nullable|exists:categories,id',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'is_active'  => 'required|in:0,1',
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ]);

        // prevent self as parent
        if ($request->parent_id && (int)$request->parent_id === (int)$category->id) {
            return back()->with('error', 'Category cannot be its own parent.')->withInput();
        }

        // slug update if name changed
        if ($category->name !== $request->name) {
            $category->slug = $this->uniqueSlug($request->name, $category->id);
        }

        $category->image      = $this->uploadImage($request, $category->image);
        $category->name       = $request->name;
        $category->parent_id  = $request->parent_id ?: null;
        $category->is_active  = (int) $request->is_active;
        $category->sort_order = (int) ($request->sort_order ?? 0);
        $category->save();

        return redirect()->route('dashboard.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $this->abortIfNotStaff();

        // optional: manager cannot delete
        if ($this->myRole() === 'manager') {
            return back()->with('error', 'Manager cannot delete categories.');
        }

        $category = Category::findOrFail($id);

        // optional: block delete if has children
        $hasChild = Category::where('parent_id', $category->id)->exists();
        if ($hasChild) {
            return back()->with('error', 'This category has sub-categories. Delete/move them first.');
        }

        // delete image
        if ($category->image && file_exists(public_path('uploads/categories/' . $category->image))) {
            @unlink(public_path('uploads/categories/' . $category->image));
        }

        $category->delete();

        return redirect()->route('dashboard.categories.index')->with('success', 'Category deleted successfully.');
    }
}
