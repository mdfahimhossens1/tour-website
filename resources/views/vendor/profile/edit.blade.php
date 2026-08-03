@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

{{-- =========================================================
    HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            Edit Vendor Profile
        </h4>

        <p class="text-muted mb-0">
            Update your account, business and payment information.
        </p>
    </div>

    <a
        href="{{ route('vendor.profile.index') }}"
        class="btn btn-light border"
    >
        <i class="bi bi-arrow-left me-1"></i>
        Back to Profile
    </a>

</div>


{{-- =========================================================
    VALIDATION ERRORS
========================================================== --}}
@if($errors->any())

    <div class="alert alert-danger border-0 shadow-sm">

        <div class="fw-bold mb-2">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Please fix the following errors:
        </div>

        <ul class="mb-0">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


{{-- =========================================================
    SUCCESS MESSAGE
========================================================== --}}
@if(session('success'))

    <div class="alert alert-success border-0 shadow-sm">

        <i class="bi bi-check-circle me-1"></i>

        {{ session('success') }}

    </div>

@endif


<div class="row g-4">

    {{-- =====================================================
        PROFILE / BUSINESS FORM
    ====================================================== --}}
    <div class="col-xl-8">

        <form
            action="{{ route('vendor.profile.update') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')


            {{-- =================================================
                ACCOUNT INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">
                        Account Information
                    </h5>

                    <small class="text-muted">
                        Update your personal account information.
                    </small>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        {{-- Name --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Name
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                required
                            >

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Email --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Email
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                            >

                            @error('email')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                BUSINESS INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">
                        Business Information
                    </h5>

                    <small class="text-muted">
                        Update your business details.
                    </small>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        {{-- Business Name --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Business Name
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="business_name"
                                value="{{ old('business_name', $vendor->business_name) }}"
                                class="form-control @error('business_name') is-invalid @enderror"
                                required
                            >

                            @error('business_name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Phone --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Phone
                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $vendor->phone) }}"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="01XXXXXXXXX"
                            >

                            @error('phone')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Trade License --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Trade License
                            </label>

                            <input
                                type="text"
                                name="trade_license"
                                value="{{ old('trade_license', $vendor->trade_license) }}"
                                class="form-control @error('trade_license') is-invalid @enderror"
                            >

                            @error('trade_license')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Website --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Website
                            </label>

                            <input
                                type="url"
                                name="website"
                                value="{{ old('website', $vendor->website) }}"
                                class="form-control @error('website') is-invalid @enderror"
                                placeholder="https://example.com"
                            >

                            @error('website')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Address --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Business Address
                            </label>

                            <textarea
                                name="address"
                                rows="4"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Enter your business address..."
                            >{{ old('address', $vendor->address) }}</textarea>

                            @error('address')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Business Description
                            </label>

                            <textarea
                                name="description"
                                rows="6"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Tell customers about your business..."
                            >{{ old('description', $vendor->description) }}</textarea>

                            @error('description')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                PAYMENT INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">
                        Payment Information
                    </h5>

                    <small class="text-muted">
                        Add your preferred payment and bank details.
                    </small>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        {{-- bKash --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                bKash Number
                            </label>

                            <input
                                type="text"
                                name="bkash"
                                value="{{ old('bkash', $vendor->bkash) }}"
                                class="form-control @error('bkash') is-invalid @enderror"
                                placeholder="01XXXXXXXXX"
                            >

                            @error('bkash')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Nagad --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Nagad Number
                            </label>

                            <input
                                type="text"
                                name="nagad"
                                value="{{ old('nagad', $vendor->nagad) }}"
                                class="form-control @error('nagad') is-invalid @enderror"
                                placeholder="01XXXXXXXXX"
                            >

                            @error('nagad')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Bank Name --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Bank Name
                            </label>

                            <input
                                type="text"
                                name="bank_name"
                                value="{{ old('bank_name', $vendor->bank_name) }}"
                                class="form-control @error('bank_name') is-invalid @enderror"
                            >

                            @error('bank_name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Bank Account --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Bank Account
                            </label>

                            <input
                                type="text"
                                name="bank_account"
                                value="{{ old('bank_account', $vendor->bank_account) }}"
                                class="form-control @error('bank_account') is-invalid @enderror"
                            >

                            @error('bank_account')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                LOGO
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">
                        Business Logo
                    </h5>

                    <small class="text-muted">
                        Upload a new logo for your business.
                    </small>

                </div>


                <div class="card-body">

                    @if($vendor->logo)

                        <div class="mb-3">

                            <img
                                src="{{ asset('storage/' . $vendor->logo) }}"
                                alt="{{ $vendor->business_name }}"
                                class="rounded border"
                                style="
                                    width:120px;
                                    height:120px;
                                    object-fit:cover;
                                "
                            >

                        </div>

                    @endif


                    <input
                        type="file"
                        name="logo"
                        accept="image/*"
                        class="form-control @error('logo') is-invalid @enderror"
                    >

                    <div class="form-text">
                        JPG, JPEG, PNG or WEBP. Maximum 2MB.
                    </div>

                    @error('logo')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            {{-- =================================================
                ACTIONS
            ================================================== --}}
            <div class="d-flex justify-content-end gap-2 mb-4">

                <a
                    href="{{ route('vendor.profile.index') }}"
                    class="btn btn-light border px-4"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary px-4"
                >
                    <i class="bi bi-check-lg me-1"></i>
                    Save Changes
                </button>

            </div>

        </form>

    </div>


    {{-- =====================================================
        RIGHT COLUMN
    ====================================================== --}}
    <div class="col-xl-4">

        {{-- Account Status --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h6 class="fw-bold mb-0">
                    Account Status
                </h6>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <small class="text-muted d-block mb-1">
                        Vendor Status
                    </small>

                    @if($vendor->status === 'approved')

                        <span class="badge bg-success">
                            Approved
                        </span>

                    @elseif($vendor->status === 'pending')

                        <span class="badge bg-warning text-dark">
                            Pending
                        </span>

                    @else

                        <span class="badge bg-danger">
                            Rejected
                        </span>

                    @endif

                </div>


                <div>

                    <small class="text-muted d-block mb-1">
                        Commission Rate
                    </small>

                    <span class="fw-semibold">
                        {{ number_format($vendor->commission_rate ?? 0, 2) }}%
                    </span>

                </div>

            </div>

        </div>


        {{-- =================================================
            PASSWORD CHANGE
        ================================================== --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <h6 class="fw-bold mb-1">
                    Change Password
                </h6>

                <small class="text-muted">
                    Keep your account secure.
                </small>

            </div>


            <div class="card-body">

                <form
                    action="{{ route('vendor.profile.password') }}"
                    method="POST"
                >

                    @csrf
                    @method('PUT')


                    {{-- Current Password --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Current Password
                        </label>

                        <input
                            type="password"
                            name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            required
                        >

                        @error('current_password')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- New Password --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            New Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            minlength="8"
                            required
                        >

                        @error('password')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Confirm Password --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Confirm New Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            minlength="8"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="btn btn-dark w-100"
                    >
                        <i class="bi bi-shield-lock me-1"></i>
                        Change Password
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>
</div>

@endsection
