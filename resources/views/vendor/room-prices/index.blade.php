@extends('layouts.vendor')

@section('title', 'Room Prices')

@section('page')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Room Prices
            </h4>

            <div class="text-muted">
                {{ $room->name }}

                @if($room->roomType)
                    <span class="ms-1">
                        ({{ $room->roomType->name }})
                    </span>
                @endif
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('vendor.rooms.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Back to Rooms
            </a>

            <a href="{{ route('vendor.room-prices.create', ['room' => $room->slug]) }}"
               class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Add Price
            </a>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>
    @endif


    {{-- Error Message --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- Room Information --}}
    <div class="card mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">
                    <small class="text-muted d-block">
                        Room
                    </small>

                    <strong>
                        {{ $room->name }}
                    </strong>
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block">
                        Room Type
                    </small>

                    <strong>
                        {{ $room->roomType->name ?? 'N/A' }}
                    </strong>
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block">
                        Resort
                    </small>

                    <strong>
                        {{ $room->resort->name ?? 'N/A' }}
                    </strong>
                </div>

            </div>

        </div>

    </div>


    {{-- Prices Table --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Pricing List
            </h5>

            <span class="badge bg-primary">
                {{ $prices->count() }} Price{{ $prices->count() != 1 ? 's' : '' }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($prices->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>#</th>
                                <th>Date Range</th>
                                <th>Type</th>
                                <th>Regular Price</th>
                                <th>Discount</th>
                                <th>Final Price</th>
                                <th class="text-end">Action</th>
                            </tr>

                        </thead>

                        <tbody>

                        @foreach($prices as $price)

                            @php

                                $regularPrice = (float) $price->price;

                                $discountValue = (float) ($price->discount_value ?? 0);

                                $finalPrice = $regularPrice;

                                if (
                                    $price->discount_type === 'percentage'
                                    && $discountValue > 0
                                ) {
                                    $finalPrice =
                                        $regularPrice -
                                        ($regularPrice * $discountValue / 100);
                                }

                                elseif (
                                    $price->discount_type === 'amount'
                                    && $discountValue > 0
                                ) {
                                    $finalPrice =
                                        $regularPrice - $discountValue;
                                }

                                $finalPrice = max(0, $finalPrice);

                            @endphp


                            <tr>

                                {{-- Number --}}
                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                {{-- Date --}}
                                <td>

                                    <div>
                                        <strong>
                                            {{ $price->from_date->format('d M Y') }}
                                        </strong>
                                    </div>

                                    <div class="text-muted small">
                                        to
                                        {{ $price->to_date->format('d M Y') }}
                                    </div>

                                </td>


                                {{-- Type --}}
                                <td>

                                    @php
                                        $typeClass = match($price->type) {
                                            'normal' => 'bg-secondary',
                                            'weekend' => 'bg-primary',
                                            'holiday' => 'bg-warning text-dark',
                                            'festival' => 'bg-danger',
                                            'seasonal' => 'bg-success',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $typeClass }}">
                                        {{ ucfirst($price->type) }}
                                    </span>

                                </td>


                                {{-- Regular Price --}}
                                <td>

                                    <strong>
                                        ৳{{ number_format($regularPrice, 2) }}
                                    </strong>

                                </td>


                                {{-- Discount --}}
                                <td>

                                    @if(
                                        $price->discount_type
                                        && $discountValue > 0
                                    )

                                        @if($price->discount_type === 'percentage')

                                            <span class="badge bg-danger">
                                                -{{ number_format($discountValue, 2) }}%
                                            </span>

                                            <div class="small text-muted mt-1">
                                                Percentage
                                            </div>

                                        @elseif($price->discount_type === 'amount')

                                            <span class="badge bg-danger">
                                                -৳{{ number_format($discountValue, 2) }}
                                            </span>

                                            <div class="small text-muted mt-1">
                                                Fixed Amount
                                            </div>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            No Discount
                                        </span>

                                    @endif

                                </td>


                                {{-- Final Price --}}
                                <td>

                                    @if($finalPrice < $regularPrice)

                                        <div>
                                            <strong class="text-success">
                                                ৳{{ number_format($finalPrice, 2) }}
                                            </strong>
                                        </div>

                                        <div class="small text-muted">
                                            Regular:
                                            <del>
                                                ৳{{ number_format($regularPrice, 2) }}
                                            </del>
                                        </div>

                                    @else

                                        <strong>
                                            ৳{{ number_format($finalPrice, 2) }}
                                        </strong>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="text-end">

                                    <div class="d-inline-flex gap-1">

                                        {{-- Edit --}}
                                        <a href="{{ route('vendor.room-prices.edit', [
                                            'room' => $room->slug,
                                            'price' => $price->id,
                                        ]) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        {{-- Delete --}}
                                        <form action="{{ route('vendor.room-prices.destroy', [
                                            'room' => $room->slug,
                                            'price' => $price->id,
                                        ]) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this price?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="bi bi-cash-stack"
                           style="font-size: 50px; color: #adb5bd;">
                        </i>

                    </div>

                    <h5>
                        No Pricing Found
                    </h5>

                    <p class="text-muted mb-4">
                        This room doesn't have any pricing yet.
                    </p>

                    <a href="{{ route('vendor.room-prices.create', ['room' => $room->slug]) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg"></i>
                        Add First Price

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection