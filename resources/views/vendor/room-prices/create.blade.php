@extends('layouts.vendor')

@section('title', 'Add Room Price')

@section('page')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Add Room Price
            </h4>

            <p class="text-muted mb-0">

                Add a pricing rule for

                <strong>
                    {{ $room->name }}
                </strong>

            </p>

        </div>


        <a
            href="{{ route('vendor.room-prices.index', $room) }}"
            class="btn btn-light mt-3 mt-md-0"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Back to Prices

        </a>

    </div>


    {{-- Errors --}}
    @if($errors->any())

        <div class="alert alert-danger border-0 shadow-sm">

            <div class="fw-bold mb-2">

                <i class="fas fa-exclamation-triangle me-1"></i>

                Please fix the following errors:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row g-4">


        {{-- Form --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">
                        Pricing Information
                    </h5>

                    <small class="text-muted">
                        Set the price and date range for this room.
                    </small>

                </div>


                <div class="card-body">

                    <form
                        action="{{ route('vendor.room-prices.store', $room) }}"
                        method="POST"
                    >

                        @csrf


                        {{-- Date --}}
                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label
                                    for="from_date"
                                    class="form-label fw-semibold"
                                >

                                    From Date

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <input
                                    type="date"
                                    name="from_date"
                                    id="from_date"
                                    value="{{ old('from_date') }}"
                                    class="form-control @error('from_date') is-invalid @enderror"
                                    required
                                >

                                @error('from_date')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6 mb-3">

                                <label
                                    for="to_date"
                                    class="form-label fw-semibold"
                                >

                                    To Date

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <input
                                    type="date"
                                    name="to_date"
                                    id="to_date"
                                    value="{{ old('to_date') }}"
                                    class="form-control @error('to_date') is-invalid @enderror"
                                    required
                                >

                                @error('to_date')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- Price --}}
                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label
                                    for="price"
                                    class="form-label fw-semibold"
                                >

                                    Regular Price

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        ৳
                                    </span>

                                    <input
                                        type="number"
                                        name="price"
                                        id="price"
                                        value="{{ old('price') }}"
                                        class="form-control @error('price') is-invalid @enderror"
                                        min="0"
                                        step="0.01"
                                        placeholder="5000"
                                        required
                                    >

                                </div>

                                @error('price')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Original room price per night.
                                </small>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label
                                    for="discount_price"
                                    class="form-label fw-semibold"
                                >

                                    Discount Price

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        ৳
                                    </span>

                                    <input
                                        type="number"
                                        name="discount_price"
                                        id="discount_price"
                                        value="{{ old('discount_price') }}"
                                        class="form-control @error('discount_price') is-invalid @enderror"
                                        min="0"
                                        step="0.01"
                                        placeholder="4500"
                                    >

                                </div>

                                @error('discount_price')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Leave empty if there is no discount.
                                </small>

                            </div>

                        </div>


                        {{-- Type --}}
                        <div class="mb-4">

                            <label
                                for="type"
                                class="form-label fw-semibold"
                            >

                                Pricing Type

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <select
                                name="type"
                                id="type"
                                class="form-select @error('type') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select Pricing Type
                                </option>

                                <option
                                    value="normal"
                                    {{ old('type') === 'normal' ? 'selected' : '' }}
                                >
                                    Normal
                                </option>

                                <option
                                    value="weekend"
                                    {{ old('type') === 'weekend' ? 'selected' : '' }}
                                >
                                    Weekend
                                </option>

                                <option
                                    value="holiday"
                                    {{ old('type') === 'holiday' ? 'selected' : '' }}
                                >
                                    Holiday
                                </option>

                                <option
                                    value="festival"
                                    {{ old('type') === 'festival' ? 'selected' : '' }}
                                >
                                    Festival
                                </option>

                                <option
                                    value="seasonal"
                                    {{ old('type') === 'seasonal' ? 'selected' : '' }}
                                >
                                    Seasonal
                                </option>

                            </select>


                            @error('type')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Actions --}}
                        <div class="border-top pt-3 d-flex justify-content-end gap-2">

                            <a
                                href="{{ route('vendor.room-prices.index', $room) }}"
                                class="btn btn-light"
                            >

                                Cancel

                            </a>


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="fas fa-save me-1"></i>

                                Save Price

                            </button>

                        </div>


                    </form>

                </div>

            </div>

        </div>


        {{-- Information --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        Room Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Room
                        </small>

                        <strong>
                            {{ $room->name }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Resort
                        </small>

                        <strong>
                            {{ $room->resort->name }}
                        </strong>

                    </div>


                    @if($room->roomType)

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Room Type
                            </small>

                            <span class="badge bg-light text-dark">
                                {{ $room->roomType->name }}
                            </span>

                        </div>

                    @endif


                    <hr>


                    <p class="text-muted small mb-0">

                        <i class="fas fa-info-circle me-1"></i>

                        The price you enter can be applied to the selected
                        date range. You can create separate pricing rules
                        for weekends, holidays, festivals and seasons.

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection