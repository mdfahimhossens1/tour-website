@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

{{-- =========================================================
    HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            Vendor Profile
        </h4>

        <p class="text-muted mb-0">
            View and manage your vendor account information.
        </p>
    </div>

    <a
        href="{{ route('vendor.profile.edit') }}"
        class="btn btn-primary"
    >
        <i class="bi bi-pencil-square me-1"></i>
        Edit Profile
    </a>

</div>


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
        LEFT COLUMN
    ====================================================== --}}
    <div class="col-xl-4">

        {{-- Profile Card --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body text-center py-4">

                {{-- Logo --}}
                <div class="mb-3">

                    @if($vendor->logo)

                        <img
                            src="{{ asset('storage/' . $vendor->logo) }}"
                            alt="{{ $vendor->business_name }}"
                            class="rounded-circle border"
                            style="
                                width:120px;
                                height:120px;
                                object-fit:cover;
                            "
                        >

                    @else

                        <div
                            class="rounded-circle bg-light border d-inline-flex align-items-center justify-content-center"
                            style="
                                width:120px;
                                height:120px;
                            "
                        >
                            <i
                                class="bi bi-building text-muted"
                                style="font-size:45px;"
                            ></i>
                        </div>

                    @endif

                </div>


                <h5 class="fw-bold mb-1">
                    {{ $vendor->business_name }}
                </h5>

                <p class="text-muted mb-3">
                    {{ $user->name }}
                </p>


                {{-- Status --}}
                @if($vendor->status === 'approved')

                    <span class="badge bg-success-subtle text-success px-3 py-2">
                        <i class="bi bi-check-circle me-1"></i>
                        Approved Vendor
                    </span>

                @elseif($vendor->status === 'pending')

                    <span class="badge bg-warning-subtle text-warning px-3 py-2">
                        <i class="bi bi-clock me-1"></i>
                        Pending Approval
                    </span>

                @else

                    <span class="badge bg-danger-subtle text-danger px-3 py-2">
                        <i class="bi bi-x-circle me-1"></i>
                        Rejected
                    </span>

                @endif

            </div>

        </div>


        {{-- Account Information --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <h6 class="fw-bold mb-0">
                    Account Information
                </h6>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <small class="text-muted d-block">
                        Account Name
                    </small>

                    <span class="fw-semibold">
                        {{ $user->name }}
                    </span>

                </div>


                <div class="mb-3">

                    <small class="text-muted d-block">
                        Email
                    </small>

                    <span class="fw-semibold">
                        {{ $user->email }}
                    </span>

                </div>


                <div>

                    <small class="text-muted d-block">
                        Member Since
                    </small>

                    <span class="fw-semibold">
                        {{ $vendor->created_at?->format('d M Y') ?? 'N/A' }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        RIGHT COLUMN
    ====================================================== --}}
    <div class="col-xl-8">

        {{-- Business Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-1">
                    Business Information
                </h5>

                <small class="text-muted">
                    Your registered vendor and business information.
                </small>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- Business Name --}}
                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">
                            Business Name
                        </small>

                        <div class="fw-semibold">
                            {{ $vendor->business_name ?: 'Not provided' }}
                        </div>

                    </div>


                    {{-- Phone --}}
                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">
                            Phone
                        </small>

                        <div class="fw-semibold">
                            {{ $vendor->phone ?: 'Not provided' }}
                        </div>

                    </div>


                    {{-- Trade License --}}
                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">
                            Trade License
                        </small>

                        <div class="fw-semibold">
                            {{ $vendor->trade_license ?: 'Not provided' }}
                        </div>

                    </div>


                    {{-- Website --}}
                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">
                            Website
                        </small>

                        @if($vendor->website)

                            <a
                                href="{{ $vendor->website }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="fw-semibold text-decoration-none"
                            >
                                {{ $vendor->website }}
                                <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>

                        @else

                            <span class="fw-semibold">
                                Not provided
                            </span>

                        @endif

                    </div>


                    {{-- Address --}}
                    <div class="col-12">

                        <small class="text-muted d-block mb-1">
                            Address
                        </small>

                        <div class="fw-semibold">
                            {{ $vendor->address ?: 'Not provided' }}
                        </div>

                    </div>


                    {{-- Description --}}
                    <div class="col-12">

                        <small class="text-muted d-block mb-1">
                            Business Description
                        </small>

                        <div>
                            {{ $vendor->description ?: 'No description provided.' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Payment Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-1">
                    Payment Information
                </h5>

                <small class="text-muted">
                    Payment accounts associated with your vendor profile.
                </small>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- bKash --}}
                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <small class="text-muted d-block mb-1">
                                bKash
                            </small>

                            <div class="fw-semibold">
                                {{ $vendor->bkash ?: 'Not provided' }}
                            </div>

                        </div>

                    </div>


                    {{-- Nagad --}}
                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <small class="text-muted d-block mb-1">
                                Nagad
                            </small>

                            <div class="fw-semibold">
                                {{ $vendor->nagad ?: 'Not provided' }}
                            </div>

                        </div>

                    </div>


                    {{-- Bank Name --}}
                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <small class="text-muted d-block mb-1">
                                Bank Name
                            </small>

                            <div class="fw-semibold">
                                {{ $vendor->bank_name ?: 'Not provided' }}
                            </div>

                        </div>

                    </div>


                    {{-- Bank Account --}}
                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <small class="text-muted d-block mb-1">
                                Bank Account
                            </small>

                            <div class="fw-semibold">
                                {{ $vendor->bank_account ?: 'Not provided' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Vendor Settings --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-1">
                    Vendor Settings
                </h5>

                <small class="text-muted">
                    Current account and platform settings.
                </small>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- Status --}}
                    <div class="col-md-4">

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


                    {{-- Commission --}}
                    <div class="col-md-4">

                        <small class="text-muted d-block mb-1">
                            Commission Rate
                        </small>

                        <span class="fw-semibold">
                            {{ number_format($vendor->commission_rate ?? 0, 2) }}%
                        </span>

                    </div>


                    {{-- Updated --}}
                    <div class="col-md-4">

                        <small class="text-muted d-block mb-1">
                            Last Updated
                        </small>

                        <span class="fw-semibold">
                            {{ $vendor->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
</div>

@endsection