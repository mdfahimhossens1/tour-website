@extends('layouts.admin')
@section('page')

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div><i class="fas fa-plus-circle me-2"></i> Add Coupon</div>
    <a href="{{ route('dashboard.coupons.index') }}" class="btn btn-sm btn-dark">All Coupons</a>
  </div>

  <div class="card-body">
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <form method="POST" action="{{ route('dashboard.coupons.store') }}">
      @csrf

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Code <span class="text-danger">*</span></label>
          <input name="code" value="{{ old('code') }}" class="form-control @error('code') is-invalid @enderror" placeholder="SAVE10">
          @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Type <span class="text-danger">*</span></label>
          <select name="type" class="form-select @error('type') is-invalid @enderror">
            <option value="percent" {{ old('type','percent')==='percent'?'selected':'' }}>Percent</option>
            <option value="fixed" {{ old('type')==='fixed'?'selected':'' }}>Fixed</option>
          </select>
          @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Value <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="value" value="{{ old('value') }}" class="form-control @error('value') is-invalid @enderror">
          @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Min Order</label>
          <input type="number" step="0.01" name="min_order" value="{{ old('min_order') }}" class="form-control @error('min_order') is-invalid @enderror">
          @error('min_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Max Discount (for percent)</label>
          <input type="number" step="0.01" name="max_discount" value="{{ old('max_discount') }}" class="form-control @error('max_discount') is-invalid @enderror">
          @error('max_discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Usage Limit</label>
          <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" class="form-control @error('usage_limit') is-invalid @enderror">
          @error('usage_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Start Date</label>
          <input type="date" name="starts_at" value="{{ old('starts_at') }}" class="form-control @error('starts_at') is-invalid @enderror">
          @error('starts_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">End Date</label>
          <input type="date" name="ends_at" value="{{ old('ends_at') }}" class="form-control @error('ends_at') is-invalid @enderror">
          @error('ends_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Status <span class="text-danger">*</span></label>
          <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
            <option value="1" {{ old('is_active','1')=='1'?'selected':'' }}>Active</option>
            <option value="0" {{ old('is_active')=='0'?'selected':'' }}>Inactive</option>
          </select>
          @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="mt-3">
        <button class="btn btn-success">Save Coupon</button>
      </div>
    </form>
  </div>
</div>

@endsection
