@extends('layouts.admin')

@section('title', 'Team Member Details')

@section('page')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-user me-2"></i>
                Team Member Details
            </h4>

            <p class="text-muted mb-0">
                View complete information about this team member.
            </p>
        </div>

        <div class="d-flex gap-2 mt-3 mt-md-0">

            <a href="{{ route('admin.team-members.edit', $teamMember->id) }}"
               class="btn btn-primary">

                <i class="fas fa-edit me-1"></i>
                Edit

            </a>

            <a href="{{ route('admin.team-members.index') }}"
               class="btn btn-light border">

                <i class="fas fa-arrow-left me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- =========================================================
        MAIN PROFILE
    ========================================================== --}}
    <div class="row g-4">

        {{-- =====================================================
            LEFT PROFILE CARD
        ====================================================== --}}
        <div class="col-xl-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center p-4">

                    {{-- Profile Image --}}
                    <div class="mb-3">

                        @if($teamMember->image)

                            <img src="{{ $teamMember->image_url }}"
                                 alt="{{ $teamMember->name }}"
                                 class="rounded-circle border shadow-sm"
                                 width="160"
                                 height="160"
                                 style="object-fit: cover;">

                        @else

                            <div class="rounded-circle bg-light border d-inline-flex align-items-center justify-content-center"
                                 style="width:160px;height:160px;">

                                <i class="fas fa-user fa-4x text-muted"></i>

                            </div>

                        @endif

                    </div>


                    {{-- Name --}}
                    <h4 class="fw-bold mb-1">
                        {{ $teamMember->name }}
                    </h4>


                    {{-- Designation --}}
                    @if($teamMember->designation)

                        <div class="text-muted mb-3">
                            {{ $teamMember->designation }}
                        </div>

                    @else

                        <div class="text-muted mb-3">
                            No designation
                        </div>

                    @endif


                    {{-- Status --}}
                    <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">

                        @if($teamMember->is_active)

                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Active
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                <i class="fas fa-times-circle me-1"></i>
                                Inactive
                            </span>

                        @endif


                        @if($teamMember->is_featured)

                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star me-1"></i>
                                Featured
                            </span>

                        @endif

                    </div>


                    {{-- Social Links --}}
                    @if(
                        $teamMember->facebook_url ||
                        $teamMember->instagram_url ||
                        $teamMember->linkedin_url ||
                        $teamMember->twitter_url
                    )

                        <div class="border-top pt-3">

                            <div class="small text-muted mb-2">
                                Social Profiles
                            </div>

                            <div class="d-flex justify-content-center gap-2">

                                @if($teamMember->facebook_url)

                                    <a href="{{ $teamMember->facebook_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Facebook">

                                        <i class="fab fa-facebook-f"></i>

                                    </a>

                                @endif


                                @if($teamMember->instagram_url)

                                    <a href="{{ $teamMember->instagram_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="btn btn-sm btn-outline-danger"
                                       title="Instagram">

                                        <i class="fab fa-instagram"></i>

                                    </a>

                                @endif


                                @if($teamMember->linkedin_url)

                                    <a href="{{ $teamMember->linkedin_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="btn btn-sm btn-outline-primary"
                                       title="LinkedIn">

                                        <i class="fab fa-linkedin-in"></i>

                                    </a>

                                @endif


                                @if($teamMember->twitter_url)

                                    <a href="{{ $teamMember->twitter_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="btn btn-sm btn-outline-dark"
                                       title="Twitter / X">

                                        <i class="fab fa-x-twitter"></i>

                                    </a>

                                @endif

                            </div>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                DISPLAY INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mt-4">

                <div class="card-header bg-white py-3">

                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-sliders-h me-2 text-primary"></i>
                        Display Information
                    </h6>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Sort Order
                        </span>

                        <span class="badge bg-light text-dark border">
                            {{ $teamMember->sort_order }}
                        </span>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Featured
                        </span>

                        @if($teamMember->is_featured)

                            <span class="text-warning fw-semibold">
                                <i class="fas fa-star me-1"></i>
                                Yes
                            </span>

                        @else

                            <span class="text-muted">
                                No
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            RIGHT CONTENT
        ====================================================== --}}
        <div class="col-xl-8">

            {{-- =================================================
                BASIC INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-circle me-2 text-primary"></i>
                        Basic Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        {{-- Name --}}
                        <div class="col-md-6">

                            <div class="small text-muted mb-1">
                                Name
                            </div>

                            <div class="fw-semibold">
                                {{ $teamMember->name }}
                            </div>

                        </div>


                        {{-- Designation --}}
                        <div class="col-md-6">

                            <div class="small text-muted mb-1">
                                Designation
                            </div>

                            <div class="fw-semibold">

                                {{ $teamMember->designation ?: 'Not provided' }}

                            </div>

                        </div>


                        {{-- Bio --}}
                        <div class="col-12">

                            <div class="small text-muted mb-1">
                                Biography
                            </div>

                            @if($teamMember->bio)

                                <div class="text-secondary"
                                     style="line-height:1.8;">

                                    {!! nl2br(e($teamMember->bio)) !!}

                                </div>

                            @else

                                <div class="text-muted">
                                    No biography provided.
                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                CONTACT INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-address-card me-2 text-primary"></i>
                        Contact Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        {{-- Email --}}
                        <div class="col-md-6">

                            <div class="small text-muted mb-1">
                                Email
                            </div>

                            @if($teamMember->email)

                                <a href="mailto:{{ $teamMember->email }}"
                                   class="text-decoration-none">

                                    <i class="fas fa-envelope me-1"></i>
                                    {{ $teamMember->email }}

                                </a>

                            @else

                                <span class="text-muted">
                                    Not provided
                                </span>

                            @endif

                        </div>


                        {{-- Phone --}}
                        <div class="col-md-6">

                            <div class="small text-muted mb-1">
                                Phone
                            </div>

                            @if($teamMember->phone)

                                <a href="tel:{{ $teamMember->phone }}"
                                   class="text-decoration-none">

                                    <i class="fas fa-phone me-1"></i>
                                    {{ $teamMember->phone }}

                                </a>

                            @else

                                <span class="text-muted">
                                    Not provided
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                SOCIAL MEDIA DETAILS
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-share-alt me-2 text-primary"></i>
                        Social Media
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        {{-- Facebook --}}
                        <div class="col-md-6">

                            <div class="border rounded p-3 h-100">

                                <div class="small text-muted mb-1">
                                    <i class="fab fa-facebook text-primary me-1"></i>
                                    Facebook
                                </div>

                                @if($teamMember->facebook_url)

                                    <a href="{{ $teamMember->facebook_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="text-break">

                                        {{ $teamMember->facebook_url }}

                                    </a>

                                @else

                                    <span class="text-muted">
                                        Not provided
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Instagram --}}
                        <div class="col-md-6">

                            <div class="border rounded p-3 h-100">

                                <div class="small text-muted mb-1">
                                    <i class="fab fa-instagram text-danger me-1"></i>
                                    Instagram
                                </div>

                                @if($teamMember->instagram_url)

                                    <a href="{{ $teamMember->instagram_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="text-break">

                                        {{ $teamMember->instagram_url }}

                                    </a>

                                @else

                                    <span class="text-muted">
                                        Not provided
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- LinkedIn --}}
                        <div class="col-md-6">

                            <div class="border rounded p-3 h-100">

                                <div class="small text-muted mb-1">
                                    <i class="fab fa-linkedin text-primary me-1"></i>
                                    LinkedIn
                                </div>

                                @if($teamMember->linkedin_url)

                                    <a href="{{ $teamMember->linkedin_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="text-break">

                                        {{ $teamMember->linkedin_url }}

                                    </a>

                                @else

                                    <span class="text-muted">
                                        Not provided
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Twitter / X --}}
                        <div class="col-md-6">

                            <div class="border rounded p-3 h-100">

                                <div class="small text-muted mb-1">
                                    <i class="fab fa-x-twitter me-1"></i>
                                    Twitter / X
                                </div>

                                @if($teamMember->twitter_url)

                                    <a href="{{ $teamMember->twitter_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="text-break">

                                        {{ $teamMember->twitter_url }}

                                    </a>

                                @else

                                    <span class="text-muted">
                                        Not provided
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                RECORD INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Record Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        {{-- ID --}}
                        <div class="col-md-4">

                            <div class="small text-muted mb-1">
                                Member ID
                            </div>

                            <div class="fw-semibold">
                                #{{ $teamMember->id }}
                            </div>

                        </div>


                        {{-- Created --}}
                        <div class="col-md-4">

                            <div class="small text-muted mb-1">
                                Created At
                            </div>

                            <div class="fw-semibold">

                                {{ $teamMember->created_at?->format('d M Y, h:i A') ?? 'N/A' }}

                            </div>

                        </div>


                        {{-- Updated --}}
                        <div class="col-md-4">

                            <div class="small text-muted mb-1">
                                Last Updated
                            </div>

                            <div class="fw-semibold">

                                {{ $teamMember->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection