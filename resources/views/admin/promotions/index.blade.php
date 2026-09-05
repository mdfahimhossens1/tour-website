@extends('layouts.admin')

@section('title', 'Promotions')

@section('page')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-bullhorn me-2"></i>
                Promotions
            </h4>

            <p class="text-muted mb-0">
                Manage promotional campaigns and special offers.
            </p>
        </div>

        <a href="{{ route('admin.promotions.create') }}"
           class="btn btn-primary">

            <i class="fas fa-plus me-1"></i>
            Add Promotion

        </a>

    </div>


    {{-- =========================================================
        ALERTS
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <div class="fw-semibold mb-2">
                <i class="fas fa-exclamation-triangle me-1"></i>
                Please fix the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small">
                                Total
                            </div>

                            <h4 class="mb-0 fw-bold">
                                {{ $totalPromotions }}
                            </h4>
                        </div>

                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3">
                            <i class="fas fa-bullhorn"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Active --}}
        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small">
                                Active
                            </div>

                            <h4 class="mb-0 fw-bold text-success">
                                {{ $activePromotions }}
                            </h4>
                        </div>

                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3">
                            <i class="fas fa-check-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Inactive --}}
        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small">
                                Inactive
                            </div>

                            <h4 class="mb-0 fw-bold text-secondary">
                                {{ $inactivePromotions }}
                            </h4>
                        </div>

                        <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary p-3">
                            <i class="fas fa-pause-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Valid --}}
        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small">
                                Currently Valid
                            </div>

                            <h4 class="mb-0 fw-bold text-info">
                                {{ $currentlyValidPromotions }}
                            </h4>
                        </div>

                        <div class="rounded-circle bg-info bg-opacity-10 text-info p-3">
                            <i class="fas fa-calendar-check"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Featured --}}
        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small">
                                Featured
                            </div>

                            <h4 class="mb-0 fw-bold text-warning">
                                {{ $featuredPromotions }}
                            </h4>
                        </div>

                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3">
                            <i class="fas fa-star"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Expired --}}
        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small">
                                Expired
                            </div>

                            <h4 class="mb-0 fw-bold text-danger">
                                {{ $expiredPromotions }}
                            </h4>
                        </div>

                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-3">
                            <i class="fas fa-calendar-times"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTERS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.promotions.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-xl-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>

                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="Name, code or description">

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-xl-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Type --}}
                    <div class="col-xl-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Discount Type
                        </label>

                        <select name="type"
                                class="form-select">

                            <option value="">
                                All Types
                            </option>

                            <option value="percentage"
                                {{ request('type') === 'percentage' ? 'selected' : '' }}>
                                Percentage
                            </option>

                            <option value="fixed"
                                {{ request('type') === 'fixed' ? 'selected' : '' }}>
                                Fixed Amount
                            </option>

                        </select>

                    </div>


                    {{-- Featured --}}
                    <div class="col-xl-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Featured
                        </label>

                        <select name="featured"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="yes"
                                {{ request('featured') === 'yes' ? 'selected' : '' }}>
                                Featured
                            </option>

                            <option value="no"
                                {{ request('featured') === 'no' ? 'selected' : '' }}>
                                Not Featured
                            </option>

                        </select>

                    </div>


                    {{-- Validity --}}
                    <div class="col-xl-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Validity
                        </label>

                        <select name="validity"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="valid"
                                {{ request('validity') === 'valid' ? 'selected' : '' }}>
                                Currently Valid
                            </option>

                            <option value="upcoming"
                                {{ request('validity') === 'upcoming' ? 'selected' : '' }}>
                                Upcoming
                            </option>

                            <option value="expired"
                                {{ request('validity') === 'expired' ? 'selected' : '' }}>
                                Expired
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-xl-1 col-md-6 d-flex align-items-end">

                        <div class="d-flex gap-2 w-100">

                            <button type="submit"
                                    class="btn btn-primary"
                                    title="Filter">

                                <i class="fas fa-filter"></i>

                            </button>

                            <a href="{{ route('admin.promotions.index') }}"
                               class="btn btn-light border"
                               title="Reset">

                                <i class="fas fa-redo"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        PROMOTIONS TABLE
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Promotion List
                </h5>

                <span class="text-muted small">
                    {{ $promotions->total() }} result(s)
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($promotions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3">
                                    #
                                </th>

                                <th>
                                    Promotion
                                </th>

                                <th>
                                    Discount
                                </th>

                                <th>
                                    Validity
                                </th>

                                <th>
                                    Usage
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Featured
                                </th>

                                <th class="text-end px-3">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($promotions as $promotion)

                                <tr>

                                    {{-- Number --}}
                                    <td class="px-3">

                                        {{ $promotions->firstItem() + $loop->index }}

                                    </td>


                                    {{-- Promotion --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $promotion->name }}

                                        </div>

                                        @if($promotion->code)

                                            <div class="mt-1">

                                                <span class="badge bg-light text-dark border">

                                                    <i class="fas fa-ticket-alt me-1"></i>

                                                    {{ $promotion->code }}

                                                </span>

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Discount --}}
                                    <td>

                                        @if($promotion->type === 'percentage')

                                            <div class="fw-bold text-primary">

                                                {{ number_format((float) $promotion->value, 2) }}%

                                            </div>

                                        @else

                                            <div class="fw-bold text-success">

                                                ৳{{ number_format((float) $promotion->value, 2) }}

                                            </div>

                                        @endif

                                        @if($promotion->minimum_amount > 0)

                                            <div class="small text-muted">

                                                Min:
                                                ৳{{ number_format((float) $promotion->minimum_amount, 2) }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Validity --}}
                                    <td>

                                        @if($promotion->isCurrentlyValid())

                                            <span class="badge bg-success-subtle text-success">

                                                <i class="fas fa-check-circle me-1"></i>
                                                Valid

                                            </span>

                                        @elseif($promotion->starts_at && $promotion->starts_at->isFuture())

                                            <span class="badge bg-info-subtle text-info">

                                                <i class="fas fa-clock me-1"></i>
                                                Upcoming

                                            </span>

                                        @elseif($promotion->ends_at && $promotion->ends_at->isPast())

                                            <span class="badge bg-danger-subtle text-danger">

                                                <i class="fas fa-times-circle me-1"></i>
                                                Expired

                                            </span>

                                        @else

                                            <span class="badge bg-secondary-subtle text-secondary">

                                                No Schedule

                                            </span>

                                        @endif

                                        @if($promotion->starts_at)

                                            <div class="small text-muted mt-1">

                                                From:
                                                {{ $promotion->starts_at->format('d M Y') }}

                                            </div>

                                        @endif

                                        @if($promotion->ends_at)

                                            <div class="small text-muted">

                                                To:
                                                {{ $promotion->ends_at->format('d M Y') }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Usage --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $promotion->used_count }}

                                            @if($promotion->usage_limit)
                                                / {{ $promotion->usage_limit }}
                                            @endif

                                        </div>

                                        @if($promotion->usage_limit)

                                            @php
                                                $usagePercentage = min(
                                                    100,
                                                    ($promotion->used_count / $promotion->usage_limit) * 100
                                                );
                                            @endphp

                                            <div class="progress mt-1"
                                                 style="height:5px; width:90px;">

                                                <div class="progress-bar"
                                                     style="width: {{ $usagePercentage }}%;">
                                                </div>

                                            </div>

                                        @else

                                            <div class="small text-muted">
                                                Unlimited
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Active --}}
                                    <td>

                                        <form method="POST"
                                              action="{{ route('admin.promotions.toggle-status', $promotion->id) }}">

                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-sm border-0 p-0"
                                                    title="Toggle status">

                                                @if($promotion->is_active)

                                                    <span class="badge bg-success">

                                                        <i class="fas fa-check me-1"></i>
                                                        Active

                                                    </span>

                                                @else

                                                    <span class="badge bg-secondary">

                                                        <i class="fas fa-pause me-1"></i>
                                                        Inactive

                                                    </span>

                                                @endif

                                            </button>

                                        </form>

                                    </td>


                                    {{-- Featured --}}
                                    <td>

                                        <form method="POST"
                                              action="{{ route('admin.promotions.toggle-featured', $promotion->id) }}">

                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-sm border-0 p-0"
                                                    title="Toggle featured">

                                                @if($promotion->is_featured)

                                                    <span class="badge bg-warning text-dark">

                                                        <i class="fas fa-star me-1"></i>
                                                        Featured

                                                    </span>

                                                @else

                                                    <span class="badge bg-light text-muted border">

                                                        <i class="far fa-star me-1"></i>
                                                        No

                                                    </span>

                                                @endif

                                            </button>

                                        </form>

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end px-3">

                                        <div class="d-flex justify-content-end gap-1">

                                            {{-- View --}}
                                            <a href="{{ route('admin.promotions.show', $promotion->id) }}"
                                               class="btn btn-sm btn-light border"
                                               title="View">

                                                <i class="fas fa-eye"></i>

                                            </a>


                                            {{-- Edit --}}
                                            <a href="{{ route('admin.promotions.edit', $promotion->id) }}"
                                               class="btn btn-sm btn-light border"
                                               title="Edit">

                                                <i class="fas fa-edit"></i>

                                            </a>


                                            {{-- Delete --}}
                                            <form method="POST"
                                                  action="{{ route('admin.promotions.destroy', $promotion->id) }}"
                                                  class="delete-promotion-form d-inline">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-light border text-danger"
                                                        title="Delete">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($promotions->hasPages())

                    <div class="card-footer bg-white">

                        {{ $promotions->links() }}

                    </div>

                @endif

            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="fas fa-bullhorn fa-3x text-muted"></i>

                    </div>

                    <h5 class="fw-bold">
                        No promotions found
                    </h5>

                    <p class="text-muted mb-3">

                        @if(request()->hasAny([
                            'search',
                            'status',
                            'type',
                            'featured',
                            'validity'
                        ]))

                            No promotions match your current filters.

                        @else

                            You haven't created any promotions yet.

                        @endif

                    </p>

                    @if(request()->hasAny([
                        'search',
                        'status',
                        'type',
                        'featured',
                        'validity'
                    ]))

                        <a href="{{ route('admin.promotions.index') }}"
                           class="btn btn-light border">

                            <i class="fas fa-redo me-1"></i>
                            Clear Filters

                        </a>

                    @else

                        <a href="{{ route('admin.promotions.create') }}"
                           class="btn btn-primary">

                            <i class="fas fa-plus me-1"></i>
                            Create First Promotion

                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>


{{-- =============================================================
    DELETE CONFIRMATION
============================================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const deleteForms = document.querySelectorAll(
        '.delete-promotion-form'
    );

    deleteForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            const confirmed = confirm(
                'Are you sure you want to delete this promotion?'
            );

            if (!confirmed) {
                event.preventDefault();
            }

        });

    });

});
</script>

@endsection