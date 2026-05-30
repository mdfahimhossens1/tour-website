@extends('layouts.admin')
@section('title', 'Edit Coupon')

@section('page')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">Edit Coupon</h5>

        <a href="{{ route('admin.coupons.index') }}"
           class="btn btn-dark btn-sm">
            All Coupons
        </a>

    </div>

    <div class="card-body">

        <form method="POST"
              action="{{ route('admin.coupons.update', $coupon->id) }}">
            @csrf

            <div class="row">

                {{-- Coupon Code --}}
                <div class="col-md-6 mb-3">
                    <label>Coupon Code *</label>

                    <input type="text"
                           name="code"
                           class="form-control"
                           value="{{ old('code', $coupon->code) }}">

                    @error('code')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Type --}}
                <div class="col-md-6 mb-3">
                    <label>Discount Type *</label>

                    <select name="type" class="form-control">

                        <option value="fixed"
                            {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>
                            Fixed (৳)
                        </option>

                        <option value="percent"
                            {{ old('type', $coupon->type) == 'percent' ? 'selected' : '' }}>
                            Percent (%)
                        </option>

                    </select>

                </div>

                {{-- Value --}}
                <div class="col-md-6 mb-3">
                    <label>Discount Value *</label>

                    <input type="number"
                           name="value"
                           class="form-control"
                           value="{{ old('value', $coupon->value) }}">

                </div>

                {{-- Max Usage --}}
                <div class="col-md-6 mb-3">
                    <label>Max Usage</label>

                    <input type="number"
                           name="max_usage"
                           class="form-control"
                           value="{{ old('max_usage', $coupon->max_usage) }}">
                </div>

                {{-- Start Date --}}
                <div class="col-md-6 mb-3">
                    <label>Start Date</label>

                    <input type="date"
                           name="start_date"
                           class="form-control"
                           value="{{ old('start_date', $coupon->start_date) }}">
                </div>

                {{-- End Date --}}
                <div class="col-md-6 mb-3">
                    <label>End Date</label>

                    <input type="date"
                           name="end_date"
                           class="form-control"
                           value="{{ old('end_date', $coupon->end_date) }}">
                </div>

                {{-- Status --}}
                <div class="col-md-6 mb-3">
                    <label>Status</label>

                    <select name="status" class="form-control">

                        <option value="1"
                            {{ old('status', $coupon->status) == 1 ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0"
                            {{ old('status', $coupon->status) == 0 ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                </div>

            </div>

            <button type="submit" class="btn btn-dark">
                Update Coupon
            </button>

        </form>

    </div>

</div>

@endsection