<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShippingController extends Controller
{
    private function myRole(): string
    {
        return strtolower(optional(auth()->user()->role)->role_name ?? 'user');
    }

    private function abortIfNotStaff(): void
    {
        if (!in_array($this->myRole(), ['super admin','admin','manager'])) {
            abort(403, 'Unauthorized');
        }
    }

    private function uniqueSlug(string $name, ?int $ignoreId=null): string
    {
        $slug = Str::slug($name);
        $base = $slug;
        $i = 1;

        while (
            ShippingMethod::where('slug', $slug)
              ->when($ignoreId, fn($q)=>$q->where('id','!=',$ignoreId))
              ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }

    public function index()
    {
        $this->abortIfNotStaff();

        $methods = ShippingMethod::orderBy('sort_order')->orderByDesc('id')->get();
        return view('admin.shipping.index', compact('methods'));
    }

    public function create()
    {
        $this->abortIfNotStaff();
        return view('admin.shipping.create');
    }

    public function store(Request $request)
    {
        $this->abortIfNotStaff();

        $request->validate([
            'name' => 'required|string|max:255|unique:shipping_methods,name',
            'zone' => 'required|in:inside_city,outside_city,nationwide,international,pickup',
            'cost' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'is_active' => 'required|in:0,1',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            
        ]);

        ShippingMethod::create([
            'name' => $request->name,
            'slug' => $this->uniqueSlug($request->name),
            'zone' => $request->zone,
            'cost' => (float)$request->cost,
            'min_order' => $request->min_order !== null ? (float)$request->min_order : null,
            'is_active' => (int)$request->is_active,
            'sort_order' => (int)($request->sort_order ?? 0),
        ]);

        return redirect()->route('dashboard.shipping.index')->with('success','Shipping method created.');
    }

    public function edit($id)
    {
        $this->abortIfNotStaff();
        $method = ShippingMethod::findOrFail($id);
        return view('admin.shipping.edit', compact('method'));
    }

    public function update(Request $request, $id)
    {
        $this->abortIfNotStaff();

        $method = ShippingMethod::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:shipping_methods,name,' . $method->id,
            'cost' => 'required|numeric|min:0',
            'zone' => 'required|in:inside_city,outside_city,nationwide,international,pickup',
            'min_order' => 'nullable|numeric|min:0',
            'is_active' => 'required|in:0,1',
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ]);

        if ($method->name !== $request->name) {
            $method->slug = $this->uniqueSlug($request->name, $method->id);
        }

        $method->name = $request->name;
        $method->zone = $request->zone;
        $method->cost = (float)$request->cost;
        $method->min_order = $request->min_order !== null ? (float)$request->min_order : null;
        $method->is_active = (int)$request->is_active;
        $method->sort_order = (int)($request->sort_order ?? 0);
        $method->save();

        return redirect()->route('dashboard.shipping.index')->with('success','Shipping method updated.');
    }

    public function toggle($id)
    {
        $this->abortIfNotStaff();

        $method = ShippingMethod::findOrFail($id);
        $method->is_active = $method->is_active ? 0 : 1;
        $method->save();

        return back()->with('success','Shipping status updated.');
    }

    public function destroy($id)
    {
        $this->abortIfNotStaff();

        // optional: manager cannot delete
        if ($this->myRole() === 'manager') {
            return back()->with('error','Manager cannot delete shipping methods.');
        }

        ShippingMethod::findOrFail($id)->delete();
        return redirect()->route('dashboard.shipping.index')->with('success','Shipping method deleted.');
    }
}
