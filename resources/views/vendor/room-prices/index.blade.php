@extends('layouts.vendor')

@section('title', 'Room Prices')

@section('page')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <h4 class="fw-bold mb-0">
                    Room Prices
                </h4>

            </div>

            <p class="text-muted mb-0">

                Manage pricing for

                <strong>
                    {{ $room->name }}
                </strong>

                at

                <strong>
                    {{ $room->resort->name }}
                </strong>

            </p>

        </div>


        <div class="d-flex gap-2 mt-3 mt-md-0">

            <a
                href="{{ route('vendor.rooms.index') }}"
                class="btn btn-light"
            >

                <i class="fas fa-arrow-left me-1"></i>

                Back to Rooms

            </a>


            <a
                href="{{ route('vendor.room-prices.create', $room) }}"
                class="btn btn-primary"
            >

                <i class="fas fa-plus me-1"></i>

                Add Price

            </a>

        </div>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- Room Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-3 align-items-center">

                <div class="col-md-8">

                    <div class="d-flex align-items-center gap-3">

                        @if($room->featured_image)

                            <img
                                src="{{ asset('storage/' . $room->featured_image) }}"
                                alt="{{ $room->name }}"
                                style="
                                    width:75px;
                                    height:60px;
                                    object-fit:cover;
                                    border-radius:10px;
                                "
                            >

                        @else

                            <div
                                class="bg-light d-flex align-items-center justify-content-center"
                                style="
                                    width:75px;
                                    height:60px;
                                    border-radius:10px;
                                "
                            >

                                <i class="fas fa-bed text-muted fs-4"></i>

                            </div>

                        @endif


                        <div>

                            <h5 class="fw-bold mb-1">
                                {{ $room->name }}
                            </h5>

                            <div class="text-muted small">

                                {{ $room->resort->name }}

                                @if($room->roomType)

                                    <span class="mx-1">
                                        •
                                    </span>

                                    {{ $room->roomType->name }}

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-md-4 text-md-end">

                    <span class="badge bg-primary">
                        {{ $prices->count() }} Price Rules
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- Price Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div>

                <h5 class="fw-bold mb-1">
                    Pricing Schedule
                </h5>

                <small class="text-muted">
                    Set different prices for different dates and occasions.
                </small>

            </div>

        </div>


        <div class="card-body p-0">

            @if($prices->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-4">
                                    Date Range
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Regular Price
                                </th>

                                <th>
                                    Discount Price
                                </th>

                                <th>
                                    Final Price
                                </th>

                                <th class="text-end pe-4">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($prices as $price)

                                @php

                                    $finalPrice =
                                        $price->discount_price
                                            ?? $price->price;

                                @endphp

                                <tr>

                                    {{-- Date --}}
                                    <td class="ps-4">

                                        <div class="fw-semibold">

                                            {{ $price->from_date->format('d M Y') }}

                                            <span class="text-muted mx-1">
                                                →
                                            </span>

                                            {{ $price->to_date->format('d M Y') }}

                                        </div>

                                    </td>


                                    {{-- Type --}}
                                    <td>

                                        @switch($price->type)

                                            @case('normal')

                                                <span class="badge bg-primary">
                                                    Normal
                                                </span>

                                                @break

                                            @case('weekend')

                                                <span class="badge bg-info">
                                                    Weekend
                                                </span>

                                                @break

                                            @case('holiday')

                                                <span class="badge bg-warning text-dark">
                                                    Holiday
                                                </span>

                                                @break

                                            @case('festival')

                                                <span class="badge bg-danger">
                                                    Festival
                                                </span>

                                                @break

                                            @case('seasonal')

                                                <span class="badge bg-success">
                                                    Seasonal
                                                </span>

                                                @break

                                        @endswitch

                                    </td>


                                    {{-- Price --}}
                                    <td>

                                        <span class="fw-semibold">

                                            ৳{{ number_format($price->price, 2) }}

                                        </span>

                                    </td>


                                    {{-- Discount --}}
                                    <td>

                                        @if($price->discount_price)

                                            <span class="text-success fw-semibold">

                                                ৳{{ number_format($price->discount_price, 2) }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Final --}}
                                    <td>

                                        <span class="fw-bold">

                                            ৳{{ number_format($finalPrice, 2) }}

                                        </span>

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end pe-4">

                                        <div class="dropdown">

                                            <button
                                                class="btn btn-sm btn-light"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                            >

                                                <i class="fas fa-ellipsis-v"></i>

                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end">

                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route('vendor.room-prices.edit', [$room, $price]) }}"
                                                    >

                                                        <i class="fas fa-edit me-2"></i>

                                                        Edit

                                                    </a>

                                                </li>


                                                <li>

                                                    <hr class="dropdown-divider">

                                                </li>


                                                <li>

                                                    <form
                                                        method="POST"
                                                        action="{{ route('vendor.room-prices.destroy', [$room, $price]) }}"
                                                        onsubmit="return confirm('Are you sure you want to delete this price rule?')"
                                                    >

                                                        @csrf

                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item text-danger"
                                                        >

                                                            <i class="fas fa-trash me-2"></i>

                                                            Delete

                                                        </button>

                                                    </form>

                                                </li>

                                            </ul>

                                        </div>

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
                        class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width:75px;height:75px;"
                    >

                        <i class="fas fa-tags fs-2 text-muted"></i>

                    </div>


                    <h5 class="fw-bold">
                        No pricing rules found
                    </h5>


                    <p class="text-muted mb-3">

                        No prices have been added for this room yet.

                    </p>


                    <a
                        href="{{ route('vendor.room-prices.create', $room) }}"
                        class="btn btn-primary"
                    >

                        <i class="fas fa-plus me-1"></i>

                        Add First Price

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection