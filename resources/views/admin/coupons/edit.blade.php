@extends('layouts.admin')
@section('page')

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div><i class="fas fa-edit me-2"></i> Edit Coupon</div>
    <a href="{{ route('dashboard.coupons.index') }}" class="btn btn-sm btn-dark">All Coupons</a>
  </div>

  <div class="card-body">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <form method="POST" action="{{ route('dashboard.coupons.update', $coupon->id) }}">
      @csrf

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Code <span class="text-danger">*</span></label>
          <input name="code"
                 value="{{ old('code', $coupon->code) }}"
                 class="form-control @error('code') is-invalid @enderror"
                 placeholder="SAVE10">
          @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="text-muted small mt-1">Code will be stored as UPPERCASE.</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Type <span class="text-danger">*</span></label>
          <select name="type" class="form-select @error('type') is-invalid @enderror">
            <option value="percent" {{ old('type', $coupon->type)==='percent'?'selected':'' }}>Percent</option>
            <option value="fixed" {{ old('type', $coupon->type)==='fixed'?'selected':'' }}>Fixed</option>
          </select>
          @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Value <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="value"
                 value="{{ old('value', $coupon->value) }}"
                 class="form-control @error('value') is-invalid @enderror">
          @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="text-muted small mt-1">Percent: 10 means 10%. Fixed: 200 means ৳200 off.</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Min Order</label>
          <input type="number" step="0.01" name="min_order"
                 value="{{ old('min_order', $coupon->min_order) }}"
                 class="form-control @error('min_order') is-invalid @enderror">
          @error('min_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Max Discount (for percent)</label>
          <input type="number" step="0.01" name="max_discount"
                 value="{{ old('max_discount', $coupon->max_discount) }}"
                 class="form-control @error('max_discount') is-invalid @enderror">
          @error('max_discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="text-muted small mt-1">Optional. Example: max ৳500 discount.</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Usage Limit</label>
          <input type="number" name="usage_limit"
                 value="{{ old('usage_limit', $coupon->usage_limit) }}"
                 class="form-control @error('usage_limit') is-invalid @enderror">
          @error('usage_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="text-muted small mt-1">
            Used: <b>{{ $coupon->used_count ?? 0 }}</b>
            @if($coupon->usage_limit) / <b>{{ $coupon->usage_limit }}</b> @else / <b>∞</b> @endif
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Start Date</label>
          <input type="date" name="starts_at"
                 value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d')) }}"
                 class="form-control @error('starts_at') is-invalid @enderror">
          @error('starts_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">End Date</label>
          <input type="date" name="ends_at"
                 value="{{ old('ends_at', optional($coupon->ends_at)->format('Y-m-d')) }}"
                 class="form-control @error('ends_at') is-invalid @enderror">
          @error('ends_at') <div class="invalid-feedback">{{ $message }}</div> @enderror

          @php
            $expired = $coupon->ends_at && $coupon->ends_at->lt(now());
          @endphp
          @if($expired)
            <div class="mt-2">
              <span class="badge bg-danger">Expired</span>
            </div>
          @endif
        </div>

        <div class="col-md-6">
          <label class="form-label">Status <span class="text-danger">*</span></label>
          <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
            <option value="1" {{ old('is_active', (string)$coupon->is_active)==='1'?'selected':'' }}>Active</option>
            <option value="0" {{ old('is_active', (string)$coupon->is_active)==='0'?'selected':'' }}>Inactive</option>
          </select>
          @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-primary">Update Coupon</button>
        <a href="{{ route('dashboard.coupons.index') }}" class="btn btn-outline-dark">Cancel</a>
      </div>
    </form>
  </div>
</div>

@endsection
