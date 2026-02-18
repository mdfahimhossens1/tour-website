@extends('layouts.admin')
@section('page')

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div><i class="fas fa-edit me-2"></i> Edit Product</div>
    <a href="{{ route('dashboard.products.index') }}" class="btn btn-sm btn-dark">All Products</a>
  </div>

  <div class="card-body">
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <form method="POST" action="{{ route('dashboard.products.update', $product->id) }}" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="form-label">Category <span class="text-danger">*</span></label>
        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
          <option value="">— Select Category —</option>
          @foreach($categories as $c)
            <option value="{{ $c->id }}" {{ old('category_id', $product->category_id)==$c->id?'selected':'' }}>
              {{ $c->name }}
            </option>
          @endforeach
        </select>
        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Product Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="text-muted small mt-1">Slug: {{ $product->slug }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">SKU <span class="text-danger">*</span></label>
        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-control @error('sku') is-invalid @enderror">
        @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Product Image</label>
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

        @if($product->image)
            <div class="mt-2">
            <img src="{{ asset('uploads/products/'.$product->image) }}"
                style="width:90px;height:90px;object-fit:cover;border-radius:14px;">
            </div>
        @endif
        </div>


      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Price <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="form-control @error('price') is-invalid @enderror">
          @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Sale Price</label>
          <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="form-control @error('sale_price') is-invalid @enderror">
          @error('sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Stock <span class="text-danger">*</span></label>
          <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control @error('stock') is-invalid @enderror">
          @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Low Stock Threshold</label>
          <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 5) }}" class="form-control @error('low_stock_threshold') is-invalid @enderror">
          @error('low_stock_threshold') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Status <span class="text-danger">*</span></label>
          <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
            <option value="1" {{ old('is_active', (string)$product->is_active)=='1'?'selected':'' }}>Active</option>
            <option value="0" {{ old('is_active', (string)$product->is_active)=='0'?'selected':'' }}>Inactive</option>
          </select>
          @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <button class="btn btn-primary">Update Product</button>
    </form>
  </div>
</div>

@endsection
