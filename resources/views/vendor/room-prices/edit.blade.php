@extends('layouts.vendor')

@section('title', 'Edit Room Price')

@section('page')

<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Edit Room Price
            </h4>

            <p class="text-muted mb-0">

                Update pricing for

                <strong>
                    {{ $room->name }}
                </strong>

            </p>

        </div>


        <a
            href="{{ route('vendor.room-prices.index', $room) }}"
            class="btn btn-light"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Back to Prices

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

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


        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        Pricing Information
                    </h5>

                </div>


                <div class="card-body">

                    <form
                        action="{{ route('vendor.room-prices.update', [$room, $price]) }}"
                        method="POST"
                    >

                        @csrf

                        @method('PUT')


                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label
                                    for="from_date"
                                    class="form-label fw-semibold"
                                >
                                    From Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="from_date"
                                    id="from_date"
                                    value="{{ old('from_date', $price->from_date->format('Y-m-d')) }}"
                                    class="form-control"
                                    required
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label
                                    for="to_date"
                                    class="form-label fw-semibold"
                                >
                                    To Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="to_date"
                                    id="to_date"
                                    value="{{ old('to_date', $price->to_date->format('Y-m-d')) }}"
                                    class="form-control"
                                    required
                                >

                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label
                                    for="price"
                                    class="form-label fw-semibold"
                                >

                                    Regular Price
                                    <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        ৳
                                    </span>

                                    <input
                                        type="number"
                                        name="price"
                                        id="price"
                                        value="{{ old('price', $price->price) }}"
                                        class="form-control"
                                        min="0"
                                        step="0.01"
                                        required
                                    >

                                </div>

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
                                        value="{{ old('discount_price', $price->discount_price) }}"
                                        class="form-control"
                                        min="0"
                                        step="0.01"
                                    >

                                </div>

                            </div>

                        </div>


                        <div class="mb-4">

                            <label
                                for="type"
                                class="form-label fw-semibold"
                            >

                                Pricing Type

                                <span class="text-danger">*</span>

                            </label>


                            <select
                                name="type"
                                id="type"
                                class="form-select"
                                required
                            >

                                <option
                                    value="normal"
                                    {{ old('type', $price->type) === 'normal' ? 'selected' : '' }}
                                >
                                    Normal
                                </option>

                                <option
                                    value="weekend"
                                    {{ old('type', $price->type) === 'weekend' ? 'selected' : '' }}
                                >
                                    Weekend
                                </option>

                                <option
                                    value="holiday"
                                    {{ old('type', $price->type) === 'holiday' ? 'selected' : '' }}
                                >
                                    Holiday
                                </option>

                                <option
                                    value="festival"
                                    {{ old('type', $price->type) === 'festival' ? 'selected' : '' }}
                                >
                                    Festival
                                </option>

                                <option
                                    value="seasonal"
                                    {{ old('type', $price->type) === 'seasonal' ? 'selected' : '' }}
                                >
                                    Seasonal
                                </option>

                            </select>

                        </div>


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

                                Update Price

                            </button>

                        </div>


                    </form>

                </div>

            </div>

        </div>


        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Room Information
                    </h5>

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

                        <div>

                            <small class="text-muted d-block">
                                Room Type
                            </small>

                            <span class="badge bg-light text-dark">
                                {{ $room->roomType->name }}
                            </span>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection