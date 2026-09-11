@extends('layouts.vendor')

@section('title', 'Edit Room Price')

@section('page')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Room Price
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


        <a href="{{ route('vendor.room-prices.index', ['room' => $room->slug]) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>
            Back to Prices

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row">

        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Update Pricing Information
                    </h5>

                </div>


                <div class="card-body">

                    <form action="{{ route('vendor.room-prices.update', [
                        'room' => $room->slug,
                        'price' => $price->id,
                    ]) }}"
                          method="POST">

                        @csrf

                        @method('PUT')


                        {{-- Date Range --}}
                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label for="from_date"
                                       class="form-label">

                                    From Date
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="date"
                                       name="from_date"
                                       id="from_date"
                                       class="form-control @error('from_date') is-invalid @enderror"
                                       value="{{ old('from_date', $price->from_date?->format('Y-m-d')) }}"
                                       required>

                                @error('from_date')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6 mb-3">

                                <label for="to_date"
                                       class="form-label">

                                    To Date
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="date"
                                       name="to_date"
                                       id="to_date"
                                       class="form-control @error('to_date') is-invalid @enderror"
                                       value="{{ old('to_date', $price->to_date?->format('Y-m-d')) }}"
                                       required>

                                @error('to_date')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- Regular Price --}}
                        <div class="mb-3">

                            <label for="price"
                                   class="form-label">

                                Regular Price
                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ৳
                                </span>

                                <input type="number"
                                       name="price"
                                       id="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', $price->price) }}"
                                       min="0"
                                       step="0.01"
                                       placeholder="Enter regular price"
                                       required>

                            </div>

                            @error('price')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Discount --}}
                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label for="discount_type"
                                       class="form-label">

                                    Discount Type

                                </label>

                                @php
                                    $selectedDiscountType = old(
                                        'discount_type',
                                        $price->discount_type
                                    );
                                @endphp

                                <select name="discount_type"
                                        id="discount_type"
                                        class="form-select @error('discount_type') is-invalid @enderror">

                                    <option value="">
                                        No Discount
                                    </option>

                                    <option value="percentage"
                                        {{ $selectedDiscountType === 'percentage' ? 'selected' : '' }}>
                                        Percentage (%)
                                    </option>

                                    <option value="amount"
                                        {{ $selectedDiscountType === 'amount' ? 'selected' : '' }}>
                                        Fixed Amount (৳)
                                    </option>

                                </select>

                                @error('discount_type')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6 mb-3">

                                <label for="discount_value"
                                       class="form-label">

                                    Discount Value

                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           name="discount_value"
                                           id="discount_value"
                                           class="form-control @error('discount_value') is-invalid @enderror"
                                           value="{{ old('discount_value', $price->discount_value) }}"
                                           min="0"
                                           step="0.01"
                                           placeholder="Enter discount">

                                    <span class="input-group-text"
                                          id="discount-symbol">

                                        %

                                    </span>

                                </div>

                                @error('discount_value')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <div class="form-text"
                                     id="discount-help">

                                    Select a discount type first.

                                </div>

                            </div>

                        </div>


                        {{-- Price Preview --}}
                        <div class="alert alert-light border mb-3"
                             id="price-preview">

                            <div class="d-flex justify-content-between">

                                <span>
                                    Regular Price
                                </span>

                                <strong id="preview-regular">
                                    ৳0.00
                                </strong>

                            </div>


                            <div class="d-flex justify-content-between text-danger">

                                <span>
                                    Discount
                                </span>

                                <strong id="preview-discount">
                                    -৳0.00
                                </strong>

                            </div>


                            <hr>


                            <div class="d-flex justify-content-between">

                                <strong>
                                    Final Price
                                </strong>

                                <strong class="text-success"
                                        id="preview-final">

                                    ৳0.00

                                </strong>

                            </div>

                        </div>


                        {{-- Pricing Type --}}
                        <div class="mb-4">

                            <label for="type"
                                   class="form-label">

                                Pricing Type
                                <span class="text-danger">*</span>

                            </label>

                            <select name="type"
                                    id="type"
                                    class="form-select @error('type') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Pricing Type
                                </option>

                                <option value="normal"
                                    {{ old('type', $price->type) === 'normal' ? 'selected' : '' }}>
                                    Normal
                                </option>

                                <option value="weekend"
                                    {{ old('type', $price->type) === 'weekend' ? 'selected' : '' }}>
                                    Weekend
                                </option>

                                <option value="holiday"
                                    {{ old('type', $price->type) === 'holiday' ? 'selected' : '' }}>
                                    Holiday
                                </option>

                                <option value="festival"
                                    {{ old('type', $price->type) === 'festival' ? 'selected' : '' }}>
                                    Festival
                                </option>

                                <option value="seasonal"
                                    {{ old('type', $price->type) === 'seasonal' ? 'selected' : '' }}>
                                    Seasonal
                                </option>

                            </select>

                            @error('type')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('vendor.room-prices.index', ['room' => $room->slug]) }}"
                               class="btn btn-outline-secondary">

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-check-lg"></i>
                                Update Price

                            </button>

                        </div>


                    </form>

                </div>

            </div>

        </div>


        {{-- Room Information --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
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
                            Room Type
                        </small>

                        <strong>
                            {{ $room->roomType->name ?? 'N/A' }}
                        </strong>

                    </div>


                    <div>

                        <small class="text-muted d-block">
                            Resort
                        </small>

                        <strong>
                            {{ $room->resort->name ?? 'N/A' }}
                        </strong>

                    </div>

                </div>

            </div>


            <div class="card mt-3">

                <div class="card-body">

                    <h6>
                        Discount Guide
                    </h6>

                    <ul class="small text-muted mb-0">

                        <li class="mb-2">
                            <strong>Percentage:</strong>
                            Example: 10% discount on ৳5,000 = ৳4,500
                        </li>

                        <li class="mb-2">
                            <strong>Fixed Amount:</strong>
                            Example: ৳500 discount on ৳5,000 = ৳4,500
                        </li>

                        <li>
                            Discount cannot make the final price negative.
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const discountType =
        document.getElementById('discount_type');

    const discountValue =
        document.getElementById('discount_value');

    const discountSymbol =
        document.getElementById('discount-symbol');

    const discountHelp =
        document.getElementById('discount-help');

    const priceInput =
        document.getElementById('price');

    const preview =
        document.getElementById('price-preview');

    const previewRegular =
        document.getElementById('preview-regular');

    const previewDiscount =
        document.getElementById('preview-discount');

    const previewFinal =
        document.getElementById('preview-final');


    function updateDiscountUI() {

        const type = discountType.value;


        if (type === 'percentage') {

            discountSymbol.textContent = '%';

            discountValue.placeholder =
                'Example: 10';

            discountValue.max =
                '100';

            discountHelp.textContent =
                'Enter a percentage between 0% and 100%.';

        }

        else if (type === 'amount') {

            discountSymbol.textContent = '৳';

            discountValue.placeholder =
                'Example: 500';

            discountValue.removeAttribute('max');

            discountHelp.textContent =
                'Enter a fixed discount amount.';

        }

        else {

            discountSymbol.textContent = '%';

            discountValue.placeholder =
                'Enter discount';

            discountValue.removeAttribute('max');

            discountHelp.textContent =
                'Select a discount type first.';

        }


        updatePreview();

    }


    function updatePreview() {

        const price =
            parseFloat(priceInput.value) || 0;

        const type =
            discountType.value;

        const discount =
            parseFloat(discountValue.value) || 0;


        let discountAmount = 0;

        let finalPrice = price;


        if (type === 'percentage') {

            discountAmount =
                price * discount / 100;

        }

        else if (type === 'amount') {

            discountAmount =
                discount;

        }


        discountAmount =
            Math.min(discountAmount, price);


        finalPrice =
            Math.max(0, price - discountAmount);


        previewRegular.textContent =
            '৳' + price.toFixed(2);

        previewDiscount.textContent =
            '-৳' + discountAmount.toFixed(2);

        previewFinal.textContent =
            '৳' + finalPrice.toFixed(2);


        preview.style.display =
            price > 0 ? 'block' : 'none';

    }


    discountType.addEventListener(
        'change',
        updateDiscountUI
    );


    discountValue.addEventListener(
        'input',
        updatePreview
    );


    priceInput.addEventListener(
        'input',
        updatePreview
    );


    updateDiscountUI();

});

</script>

@endsection