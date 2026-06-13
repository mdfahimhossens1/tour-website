@extends('layouts.admin')
@section('page')

<div class="card">

    <div class="card-header">
        <h4>Vendor Profile</h4>
    </div>

    <div class="card-body">

        <form method="POST"
              action="{{ route('vendor.profile.update') }}">

            @csrf

            <div class="mb-3">
                <label>Business Name</label>

                <input type="text"
                       name="business_name"
                       class="form-control"
                       value="{{ old('business_name', $vendor->business_name) }}">
            </div>

            <div class="mb-3">
                <label>Phone</label>

                <input type="text"
                       name="phone"
                       class="form-control"
                       value="{{ old('phone', $vendor->phone) }}">
            </div>

            <div class="mb-3">
                <label>Address</label>

                <textarea name="address"
                          class="form-control">{{ old('address', $vendor->address) }}</textarea>
            </div>

            <button class="btn btn-primary">
                Update Profile
            </button>

        </form>

    </div>

</div>

@endsection