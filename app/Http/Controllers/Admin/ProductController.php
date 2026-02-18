<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
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

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $base = $slug;
        $i = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function uploadImage(Request $request, ?string $old = null): ?string
    {
    if (!$request->hasFile('image')) return $old;

    // delete old
    if ($old && file_exists(public_path('uploads/products/' . $old))) {
        @unlink(public_path('uploads/products/' . $old));
    }

    $file = $request->file('image');
    $ext  = strtolower($file->getClientOriginalExtension());
    $name = 'p_' . time() . '_' . uniqid() . '.' . $ext;

    $file->move(public_path('uploads/products'), $name);

    return $name;
    }


    public function index()
    {
        $this->abortIfNotStaff();

        $products = Product::with('category')
            ->orderByDesc('id')
            ->get();

        return view('admin.catalog.products.index', compact('products'));
    }

    public function create()
    {
        $this->abortIfNotStaff();

        // show all categories for dropdown (active only optional)
        $categories = Category::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.catalog.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->abortIfNotStaff();

        $request->validate([
            'category_id'          => 'required|exists:categories,id',
            'name'                 => 'required|string|max:255|unique:products,name',
            'sku'                  => 'required|string|max:100|unique:products,sku',
            'price'                => 'required|numeric|min:0',
            'sale_price'           => 'nullable|numeric|min:0',
            'stock'                => 'required|integer|min:0',
            'low_stock_threshold'  => 'nullable|integer|min:0|max:999999',
            'is_active'            => 'required|in:0,1',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',

        ]);

        // sale_price must be <= price
        if ($request->sale_price !== null && (float)$request->sale_price > (float)$request->price) {
            return back()->with('error', 'Sale price cannot be greater than price.')->withInput();
        }

        Product::create([
            'category_id'         => (int)$request->category_id,
            'name'                => $request->name,
            'slug'                => $this->uniqueSlug($request->name),
            'sku'                 => $request->sku,
            'price'               => (float)$request->price,
            'sale_price'          => $request->sale_price !== null ? (float)$request->sale_price : null,
            'stock'               => (int)$request->stock,
            'low_stock_threshold' => (int)($request->low_stock_threshold ?? 5),
            'is_active'           => (int)$request->is_active,
            'image'               => $this->uploadImage($request, null),
        ]);

        return redirect()->route('dashboard.products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $this->abortIfNotStaff();

        $product = Product::findOrFail($id);

        $categories = Category::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.catalog.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $this->abortIfNotStaff();

        $product = Product::findOrFail($id);

        $request->validate([
            'category_id'          => 'required|exists:categories,id',
            'name'                 => 'required|string|max:255|unique:products,name,' . $product->id,
            'sku'                  => 'required|string|max:100|unique:products,sku,' . $product->id,
            'price'                => 'required|numeric|min:0',
            'sale_price'           => 'nullable|numeric|min:0',
            'stock'                => 'required|integer|min:0',
            'low_stock_threshold'  => 'nullable|integer|min:0|max:999999',
            'is_active'            => 'required|in:0,1',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',

        ]);

        if ($request->sale_price !== null && (float)$request->sale_price > (float)$request->price) {
            return back()->with('error', 'Sale price cannot be greater than price.')->withInput();
        }

        // update slug if name changed
        if ($product->name !== $request->name) {
            $product->slug = $this->uniqueSlug($request->name, $product->id);
        }

        $product->category_id         = (int)$request->category_id;
        $product->name                = $request->name;
        $product->sku                 = $request->sku;
        $product->price               = (float)$request->price;
        $product->sale_price          = $request->sale_price !== null ? (float)$request->sale_price : null;
        $product->stock               = (int)$request->stock;
        $product->low_stock_threshold = (int)($request->low_stock_threshold ?? 5);
        $product->is_active           = (int)$request->is_active;
        $product->image               = $this->uploadImage($request, $product->image);
        $product->save();

        return redirect()->route('dashboard.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $this->abortIfNotStaff();

        // optional: manager cannot delete
        if ($this->myRole() === 'manager') {
            return back()->with('error', 'Manager cannot delete products.');
        }

        $product = Product::findOrFail($id);
        
         if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
            @unlink(public_path('uploads/products/' . $product->image));
        }

        $product->delete();

        return redirect()->route('dashboard.products.index')->with('success', 'Product deleted successfully.');
    }
}
