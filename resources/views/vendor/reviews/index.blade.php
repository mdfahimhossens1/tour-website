@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="mb-4">

        <h4 class="fw-bold mb-1">
            Customer Reviews
        </h4>

        <p class="text-muted mb-0">
            View and monitor reviews submitted by customers for your tours.
        </p>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
        </div>

    @endif


    {{-- Error --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm">
            <i class="bi bi-exclamation-circle me-1"></i>
            {{ session('error') }}
        </div>

    @endif


    {{-- =====================================================
        STATISTICS
    ====================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Total Reviews
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $totalReviews }}
                            </h3>

                        </div>

                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">

                            <i class="bi bi-chat-left-text text-primary fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Average --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Average Rating
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($averageRating, 1) }}
                                <small class="text-muted fs-6">
                                    / 5
                                </small>
                            </h3>

                            <div class="text-warning">

                                @for($i = 1; $i <= 5; $i++)

                                    @if($i <= round($averageRating))

                                        <i class="bi bi-star-fill"></i>

                                    @else

                                        <i class="bi bi-star"></i>

                                    @endif

                                @endfor

                            </div>

                        </div>

                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">

                            <i class="bi bi-star-fill text-warning fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Approved --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Approved
                            </small>

                            <h3 class="fw-bold mb-0 text-success">
                                {{ $approvedReviews }}
                            </h3>

                        </div>

                        <div class="bg-success bg-opacity-10 rounded-circle p-3">

                            <i class="bi bi-check-circle text-success fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Pending --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Pending
                            </small>

                            <h3 class="fw-bold mb-0 text-warning">
                                {{ $pendingReviews }}
                            </h3>

                        </div>

                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">

                            <i class="bi bi-clock text-warning fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        RATING OVERVIEW
    ====================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="fw-bold mb-1">
                Rating Overview
            </h5>

            <small class="text-muted">
                Customer rating distribution.
            </small>

        </div>


        <div class="card-body">

            @for($rating = 5; $rating >= 1; $rating--)

                @php

                    $count = $ratingCounts[$rating] ?? 0;

                    $percentage = $totalReviews > 0
                        ? round(($count / $totalReviews) * 100)
                        : 0;

                @endphp

                <div class="row align-items-center mb-3">

                    <div class="col-md-1">

                        <span class="fw-semibold">

                            {{ $rating }}

                            <i class="bi bi-star-fill text-warning"></i>

                        </span>

                    </div>


                    <div class="col-md-9">

                        <div
                            class="progress"
                            style="height:8px;"
                        >

                            <div
                                class="progress-bar bg-warning"
                                style="width: {{ $percentage }}%;"
                            ></div>

                        </div>

                    </div>


                    <div class="col-md-2 text-muted small">

                        {{ $count }} reviews

                    </div>

                </div>

            @endfor

        </div>

    </div>


    {{-- =====================================================
        FILTER
    ====================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('vendor.reviews.index') }}"
            >

                <div class="row g-3 align-items-end">


                    {{-- Search --}}
                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Customer, tour or review..."
                        >

                    </div>


                    {{-- Rating --}}
                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            Rating
                        </label>

                        <select
                            name="rating"
                            class="form-select"
                        >

                            <option value="">
                                All Ratings
                            </option>

                            @for($rating = 5; $rating >= 1; $rating--)

                                <option
                                    value="{{ $rating }}"
                                    @selected(request('rating') == $rating)
                                >
                                    {{ $rating }} Star
                                </option>

                            @endfor

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All
                            </option>

                            <option
                                value="approved"
                                @selected(request('status') === 'approved')
                            >
                                Approved
                            </option>

                            <option
                                value="pending"
                                @selected(request('status') === 'pending')
                            >
                                Pending
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-3">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary flex-grow-1"
                            >

                                <i class="bi bi-search me-1"></i>

                                Filter

                            </button>


                            <a
                                href="{{ route('vendor.reviews.index') }}"
                                class="btn btn-light border"
                                title="Reset"
                            >

                                <i class="bi bi-arrow-counterclockwise"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
        REVIEWS
    ====================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        Customer Reviews
                    </h5>

                    <small class="text-muted">
                        {{ $reviews->count() }} reviews found
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($reviews->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3">
                                    Customer
                                </th>

                                <th>
                                    Tour
                                </th>

                                <th>
                                    Rating
                                </th>

                                <th>
                                    Review
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Date
                                </th>

                                <th class="text-end px-3">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($reviews as $review)

                                <tr>

                                    {{-- Customer --}}
                                    <td class="px-3">

                                        <div class="d-flex align-items-center gap-2">

                                            <div
                                                class="rounded-circle bg-primary bg-opacity-10
                                                       text-primary d-flex align-items-center
                                                       justify-content-center"
                                                style="width:40px;height:40px;"
                                            >

                                                <i class="bi bi-person"></i>

                                            </div>


                                            <div>

                                                <div class="fw-semibold">

                                                    {{ $review->user?->name ?? 'Unknown User' }}

                                                </div>

                                                @if($review->user?->email)

                                                    <small class="text-muted">

                                                        {{ $review->user->email }}

                                                    </small>

                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Tour --}}
                                    <td>

                                        @if($review->tour)

                                            <div class="fw-semibold">

                                                {{ $review->tour->title }}

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                Tour unavailable
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Rating --}}
                                    <td>

                                        <div class="text-warning">

                                            @for($i = 1; $i <= 5; $i++)

                                                @if($i <= (int) $review->rating)

                                                    <i class="bi bi-star-fill"></i>

                                                @else

                                                    <i class="bi bi-star"></i>

                                                @endif

                                            @endfor

                                        </div>

                                        <small class="text-muted">

                                            {{ $review->rating }}/5

                                        </small>

                                    </td>


                                    {{-- Review --}}
                                    <td style="max-width:320px;">

                                        <div
                                            class="text-truncate"
                                            style="max-width:300px;"
                                            title="{{ $review->review }}"
                                        >

                                            {{ $review->review }}

                                        </div>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($review->is_approved)

                                            <span class="badge bg-success">

                                                <i class="bi bi-check-circle me-1"></i>

                                                Approved

                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">

                                                <i class="bi bi-clock me-1"></i>

                                                Pending

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        <small class="text-muted">

                                            {{ $review->created_at?->format('d M Y') }}

                                        </small>

                                    </td>


                                    {{-- Action --}}
                                    <td class="text-end px-3">

                                        <a
                                            href="{{ route('vendor.reviews.show', $review->id) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >

                                            <i class="bi bi-eye"></i>

                                            View

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- Empty --}}
                <div class="text-center py-5">

                    <div
                        class="rounded-circle bg-light d-flex align-items-center
                               justify-content-center mx-auto mb-3"
                        style="width:80px;height:80px;"
                    >

                        <i class="bi bi-chat-square-text fs-2 text-muted"></i>

                    </div>


                    <h5 class="fw-bold">
                        No Reviews Found
                    </h5>


                    <p class="text-muted mb-0">

                        There are currently no customer reviews for your tours.

                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection