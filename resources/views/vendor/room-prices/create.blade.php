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
                <strong>{{ $room->name }}</strong>
            </p>
        </div>

        <a
            href="{{ route('vendor.room-prices.index', ['room' => $room->slug]) }}"
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
                    <li>{{ $error }}</li>
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
                        action="{{ route('vendor.room-prices.store', ['room' => $room->slug]) }}"
                        method="POST"
                        id="roomPriceForm"
                    >

                        @csrf


                        {{-- Date --}}
                        <div class="row">

                            {{-- From Date --}}
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


                            {{-- To Date --}}
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


                        {{-- Price Row --}}
                        <div class="row">

                            {{-- Regular Price --}}
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


                            {{-- Discount Type --}}
                            <div class="col-md-6 mb-3">

                                <label
                                    for="discount_type"
                                    class="form-label fw-semibold"
                                >
                                    Discount Type
                                </label>

                                <select
                                    name="discount_type"
                                    id="discount_type"
                                    class="form-select @error('discount_type') is-invalid @enderror"
                                >

                                    <option value="">
                                        No Discount
                                    </option>

                                    <option
                                        value="percentage"
                                        {{ old('discount_type') === 'percentage' ? 'selected' : '' }}
                                    >
                                        Percentage (%)
                                    </option>

                                    <option
                                        value="amount"
                                        {{ old('discount_type') === 'amount' ? 'selected' : '' }}
                                    >
                                        Fixed Amount (৳)
                                    </option>

                                </select>

                                @error('discount_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Discount Value --}}
                            <div class="col-md-6 mb-3">

                                <label
                                    for="discount_value"
                                    class="form-label fw-semibold"
                                >
                                    Discount
                                </label>

                                <div class="input-group">

                                    <span
                                        class="input-group-text"
                                        id="discount-symbol"
                                    >
                                        %
                                    </span>

                                    <input
                                        type="number"
                                        name="discount_value"
                                        id="discount_value"
                                        value="{{ old('discount_value') }}"
                                        class="form-control @error('discount_value') is-invalid @enderror"
                                        min="0"
                                        step="0.01"
                                        placeholder="10"
                                    >

                                </div>

                                @error('discount_value')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <small
                                    class="text-muted"
                                    id="discount-help"
                                >
                                    Select discount type first.
                                </small>

                            </div>


                            {{-- Price Preview --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-semibold">
                                    Final Price
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        ৳
                                    </span>

                                    <input
                                        type="text"
                                        id="final_price"
                                        class="form-control"
                                        value="0.00"
                                        readonly
                                    >

                                </div>

                                <small class="text-muted">
                                    Automatically calculated price after discount.
                                </small>

                            </div>

                        </div>


                        {{-- Pricing Type --}}
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
                                href="{{ route('vendor.room-prices.index', ['room' => $room->slug]) }}"
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


{{-- Discount JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const discountType = document.getElementById('discount_type');
    const discountValue = document.getElementById('discount_value');
    const discountSymbol = document.getElementById('discount-symbol');
    const discountHelp = document.getElementById('discount-help');

    const priceInput = document.getElementById('price');
    const finalPriceInput = document.getElementById('final_price');


    function updateDiscountUI() {

        const type = discountType.value;

        // Reset
        discountValue.removeAttribute('max');

        if (type === 'percentage') {

            discountSymbol.textContent = '%';

            discountValue.placeholder = '10';

            discountValue.min = '0';

            discountValue.max = '100';

            discountHelp.textContent =
                'Enter discount percentage. Maximum 100%.';

        }

        else if (type === 'amount') {

            discountSymbol.textContent = '৳';

            discountValue.placeholder = '500';

            discountValue.min = '0';

            discountValue.removeAttribute('max');

            discountHelp.textContent =
                'Enter a fixed discount amount in Bangladeshi Taka.';

        }

        else {

            discountSymbol.textContent = '%';

            discountValue.placeholder = '10';

            discountValue.removeAttribute('max');

            discountHelp.textContent =
                'Select discount type first.';

        }

        calculateFinalPrice();
    }


    function calculateFinalPrice() {

        const price = parseFloat(priceInput.value) || 0;
        const discount = parseFloat(discountValue.value) || 0;
        const type = discountType.value;

        let finalPrice = price;

        if (type === 'percentage') {

            finalPrice = price - (price * discount / 100);

        }

        else if (type === 'amount') {

            finalPrice = price - discount;

        }

        // Never allow negative price
        if (finalPrice < 0) {
            finalPrice = 0;
        }

        finalPriceInput.value = finalPrice.toFixed(2);
    }


    // Discount type change
    discountType.addEventListener('change', function () {

        updateDiscountUI();

    });


    // Discount value change
    discountValue.addEventListener('input', function () {

        const type = discountType.value;
        const price = parseFloat(priceInput.value) || 0;
        let value = parseFloat(discountValue.value) || 0;


        // Percentage max 100
        if (type === 'percentage' && value > 100) {

            value = 100;

            discountValue.value = 100;
        }


        // Fixed amount cannot exceed regular price
        if (type === 'amount' && price > 0 && value > price) {

            value = price;

            discountValue.value = price;
        }


        calculateFinalPrice();

    });


    // Regular price change
    priceInput.addEventListener('input', function () {

        const type = discountType.value;

        let discount = parseFloat(discountValue.value) || 0;
        const price = parseFloat(priceInput.value) || 0;


        if (type === 'amount' && price > 0 && discount > price) {

            discountValue.value = price;
        }


        calculateFinalPrice();

    });


    // Initialize on page load
    updateDiscountUI();

});
</script>

@endsection