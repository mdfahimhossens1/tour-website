@extends('layouts.admin')
@section('page')

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div><i class="fas fa-edit me-2"></i> Edit Shipping Method</div>
    <a href="{{ route('dashboard.shipping.index') }}" class="btn btn-sm btn-dark">All Shipping</a>
  </div>

  <div class="card-body">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <form method="POST" action="{{ route('dashboard.shipping.update', $method->id) }}">
      @csrf

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Name <span class="text-danger">*</span></label>
          <input name="name"
                 value="{{ old('name', $method->name) }}"
                 class="form-control @error('name') is-invalid @enderror"
                 placeholder="Inside Dhaka">
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="text-muted small mt-1">Slug: {{ $method->slug }}</div>
        </div>

        <div class="col-md-6">
        <label class="form-label">Location Type (Zone) <span class="text-danger">*</span></label>
        <select name="zone" class="form-select @error('zone') is-invalid @enderror">
            <option value="inside_city" {{ old('zone', $method->zone)=='inside_city'?'selected':'' }}>Inside City</option>
            <option value="outside_city" {{ old('zone', $method->zone)=='outside_city'?'selected':'' }}>Outside City</option>
            <option value="nationwide" {{ old('zone', $method->zone)=='nationwide'?'selected':'' }}>Nationwide</option>
            <option value="international" {{ old('zone', $method->zone)=='international'?'selected':'' }}>International</option>
            <option value="pickup" {{ old('zone', $method->zone)=='pickup'?'selected':'' }}>Pickup</option>
        </select>
        @error('zone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>


        <div class="col-md-6">
          <label class="form-label">Cost <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="cost"
                 value="{{ old('cost', $method->cost) }}"
                 class="form-control @error('cost') is-invalid @enderror">
          @error('cost') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Min Order (optional)</label>
          <input type="number" step="0.01" name="min_order"
                 value="{{ old('min_order', $method->min_order) }}"
                 class="form-control @error('min_order') is-invalid @enderror">
          @error('min_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="text-muted small mt-1">Example: Free shipping above ৳2000 (cost can be 0).</div>
        </div>

        <div class="col-md-3">
          <label class="form-label">Sort Order</label>
          <input type="number" name="sort_order"
                 value="{{ old('sort_order', $method->sort_order ?? 0) }}"
                 class="form-control @error('sort_order') is-invalid @enderror">
          @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
          <label class="form-label">Status <span class="text-danger">*</span></label>
          <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
            <option value="1" {{ old('is_active', (string)$method->is_active)==='1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active', (string)$method->is_active)==='0' ? 'selected' : '' }}>Inactive</option>
          </select>
          @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-primary">Update Shipping</button>
        <a href="{{ route('dashboard.shipping.index') }}" class="btn btn-outline-dark">Cancel</a>
      </div>
    </form>
  </div>
</div>

@endsection
