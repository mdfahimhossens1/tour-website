@extends('layouts.admin')

@section('title', 'Add Tax Rule')

@section('page')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-plus-circle text-primary me-2"></i>
                Add Tax Rule
            </h4>

            <p class="text-muted mb-0">
                Create a new tax rule for bookings or vendor payouts.
            </p>
        </div>

        <a href="{{ route('admin.tax-rules.index') }}"
           class="btn btn-light border">

            <i class="fas fa-arrow-left me-1"></i>
            Back to Tax Rules

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">

                <i class="fas fa-exclamation-triangle me-2"></i>

                Please fix the following errors:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.tax-rules.store') }}">

        @csrf

        <div class="row g-4">

            {{-- Main Form --}}
            <div class="col-xl-8">

                {{-- Basic Information --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">

                            <i class="fas fa-info-circle text-primary me-2"></i>

                            Basic Information

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Name --}}
                            <div class="col-md-7">

                                <label class="form-label fw-semibold">

                                    Tax Name

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="e.g. VAT">

                                @error('name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    A clear name for this tax rule.
                                </small>

                            </div>


                            {{-- Code --}}
                            <div class="col-md-5">

                                <label class="form-label fw-semibold">

                                    Tax Code

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text"
                                       name="code"
                                       id="taxCode"
                                       class="form-control text-uppercase @error('code') is-invalid @enderror"
                                       value="{{ old('code') }}"
                                       placeholder="e.g. VAT_15"
                                       maxlength="100">

                                @error('code')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Unique internal identifier.
                                </small>

                            </div>


                            {{-- Description --}}
                            <div class="col-12">

                                <label class="form-label fw-semibold">

                                    Description

                                </label>

                                <textarea name="description"
                                          rows="4"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Describe where and why this tax applies...">{{ old('description') }}</textarea>

                                @error('description')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Tax Configuration --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">

                            <i class="fas fa-calculator text-primary me-2"></i>

                            Tax Configuration

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Tax Type --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Tax Type

                                    <span class="text-danger">*</span>

                                </label>

                                <select name="type"
                                        id="taxType"
                                        class="form-select @error('type') is-invalid @enderror">

                                    <option value="">
                                        Select Tax Type
                                    </option>

                                    <option value="percentage"
                                        {{ old('type', 'percentage') === 'percentage' ? 'selected' : '' }}>

                                        Percentage (%)

                                    </option>

                                    <option value="fixed"
                                        {{ old('type') === 'fixed' ? 'selected' : '' }}>

                                        Fixed Amount

                                    </option>

                                </select>

                                @error('type')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Rate --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Tax Rate

                                    <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           name="rate"
                                           id="taxRate"
                                           step="0.01"
                                           min="0"
                                           class="form-control @error('rate') is-invalid @enderror"
                                           value="{{ old('rate') }}"
                                           placeholder="0.00">

                                    <span class="input-group-text"
                                          id="rateSuffix">

                                        %

                                    </span>

                                </div>

                                @error('rate')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted"
                                       id="rateHelp">

                                    Example: 15 means 15%.

                                </small>

                            </div>


                            {{-- Applies To --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Applies To

                                    <span class="text-danger">*</span>

                                </label>

                                <select name="applies_to"
                                        class="form-select @error('applies_to') is-invalid @enderror">

                                    <option value="">
                                        Select Application
                                    </option>

                                    <option value="booking"
                                        {{ old('applies_to') === 'booking' ? 'selected' : '' }}>

                                        Booking

                                    </option>

                                    <option value="vendor_payout"
                                        {{ old('applies_to') === 'vendor_payout' ? 'selected' : '' }}>

                                        Vendor Payout

                                    </option>

                                    <option value="both"
                                        {{ old('applies_to') === 'both' ? 'selected' : '' }}>

                                        Booking & Vendor Payout

                                    </option>

                                </select>

                                @error('applies_to')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Priority --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Priority

                                </label>

                                <input type="number"
                                       name="priority"
                                       min="0"
                                       step="1"
                                       class="form-control @error('priority') is-invalid @enderror"
                                       value="{{ old('priority', 0) }}"
                                       placeholder="0">

                                @error('priority')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Lower number means higher priority.
                                </small>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Effective Period --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">

                            <i class="fas fa-calendar-alt text-primary me-2"></i>

                            Effective Period

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Start Date --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Start Date

                                </label>

                                <input type="date"
                                       name="starts_at"
                                       class="form-control @error('starts_at') is-invalid @enderror"
                                       value="{{ old('starts_at') }}">

                                @error('starts_at')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Leave empty to make it effective immediately.
                                </small>

                            </div>


                            {{-- End Date --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    End Date

                                </label>

                                <input type="date"
                                       name="ends_at"
                                       class="form-control @error('ends_at') is-invalid @enderror"
                                       value="{{ old('ends_at') }}">

                                @error('ends_at')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Leave empty for no expiry date.
                                </small>

                            </div>

                        </div>


                        <div class="alert alert-info mt-3 mb-0">

                            <i class="fas fa-info-circle me-2"></i>

                            If both dates are empty, this tax rule will have
                            no date restriction.

                        </div>

                    </div>

                </div>


                {{-- Form Buttons --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('admin.tax-rules.index') }}"
                               class="btn btn-light border">

                                Cancel

                            </a>

                            <button type="submit"
                                    class="btn btn-primary px-4">

                                <i class="fas fa-save me-1"></i>

                                Create Tax Rule

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Right Sidebar --}}
            <div class="col-xl-4">

                {{-- Status --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">

                            <i class="fas fa-toggle-on text-primary me-2"></i>

                            Rule Status

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="form-check form-switch">

                            <input class="form-check-input"
                                   type="checkbox"
                                   role="switch"
                                   name="is_active"
                                   value="1"
                                   id="isActive"
                                   {{ old('is_active', true) ? 'checked' : '' }}>

                            <label class="form-check-label fw-semibold"
                                   for="isActive">

                                Active Tax Rule

                            </label>

                        </div>

                        <p class="text-muted small mt-2 mb-0">

                            Active rules can be used by the booking and
                            payout calculation system.

                        </p>

                    </div>

                </div>


                {{-- Tax Preview --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">

                            <i class="fas fa-eye text-primary me-2"></i>

                            Tax Preview

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="preview-box">

                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Tax Name
                                </span>

                                <strong id="previewName">
                                    —
                                </strong>

                            </div>


                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Type
                                </span>

                                <span id="previewType"
                                      class="badge bg-secondary">

                                    —

                                </span>

                            </div>


                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Rate
                                </span>

                                <strong id="previewRate">
                                    —
                                </strong>

                            </div>


                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Applies To
                                </span>

                                <span id="previewApplies"
                                      class="text-end">

                                    —

                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Important Information --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">

                            <i class="fas fa-lightbulb text-warning me-2"></i>

                            Important

                        </h5>

                    </div>


                    <div class="card-body">

                        <ul class="small text-muted mb-0 ps-3">

                            <li class="mb-2">
                                Percentage tax should be between 0% and 100%.
                            </li>

                            <li class="mb-2">
                                Fixed tax is a fixed amount per applicable
                                calculation.
                            </li>

                            <li class="mb-2">
                                Priority controls the order when multiple
                                tax rules are applicable.
                            </li>

                            <li>
                                Inactive rules will not be used in future
                                calculations.
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection


@push('styles')

<style>

    .card {
        border-radius: 12px;
    }

    .card-header {
        border-bottom: 1px solid #f0f0f0;
    }

    .form-label {
        font-size: 14px;
    }

    .form-control,
    .form-select {
        min-height: 44px;
        border-radius: 8px;
    }

    textarea.form-control {
        min-height: auto;
    }

    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .08);
    }

    .form-check-input {
        width: 2.5em;
        height: 1.3em;
        cursor: pointer;
    }

    .form-check-label {
        cursor: pointer;
        margin-left: 5px;
    }

    .preview-box {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 18px;
    }

    .preview-box strong {
        max-width: 180px;
        text-align: right;
        word-break: break-word;
    }

</style>

@endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const nameInput = document.getElementById('taxName');
    const codeInput = document.getElementById('taxCode');
    const typeInput = document.getElementById('taxType');
    const rateInput = document.getElementById('taxRate');
    const appliesInput = document.querySelector('[name="applies_to"]');

    const previewName = document.getElementById('previewName');
    const previewType = document.getElementById('previewType');
    const previewRate = document.getElementById('previewRate');
    const previewApplies = document.getElementById('previewApplies');

    const rateSuffix = document.getElementById('rateSuffix');
    const rateHelp = document.getElementById('rateHelp');


    /*
    |--------------------------------------------------------------------------
    | Tax Name Preview
    |--------------------------------------------------------------------------
    */

    const nameField = document.querySelector('[name="name"]');

    nameField.addEventListener('input', function () {

        previewName.textContent =
            this.value.trim() || '—';

    });


    /*
    |--------------------------------------------------------------------------
    | Tax Code Uppercase
    |--------------------------------------------------------------------------
    */

    codeInput.addEventListener('input', function () {

        this.value = this.value.toUpperCase();

    });


    /*
    |--------------------------------------------------------------------------
    | Tax Type
    |--------------------------------------------------------------------------
    */

    function updateTaxType()
    {
        const type = typeInput.value;

        if (type === 'percentage') {

            rateSuffix.textContent = '%';

            rateHelp.textContent =
                'Example: 15 means 15%.';

            rateInput.max = '100';

            previewType.textContent =
                'Percentage';

            previewType.className =
                'badge bg-info text-dark';

        } else if (type === 'fixed') {

            rateSuffix.textContent = 'Amount';

            rateHelp.textContent =
                'Enter the fixed tax amount.';

            rateInput.removeAttribute('max');

            previewType.textContent =
                'Fixed';

            previewType.className =
                'badge bg-warning text-dark';

        } else {

            rateSuffix.textContent = '%';

            rateHelp.textContent =
                'Select a tax type first.';

            previewType.textContent =
                '—';

            previewType.className =
                'badge bg-secondary';

        }

        updateRatePreview();
    }


    /*
    |--------------------------------------------------------------------------
    | Rate Preview
    |--------------------------------------------------------------------------
    */

    function updateRatePreview()
    {
        const rate = rateInput.value;

        if (!rate) {

            previewRate.textContent = '—';

            return;

        }

        if (typeInput.value === 'percentage') {

            previewRate.textContent =
                parseFloat(rate).toFixed(2) + '%';

        } else if (typeInput.value === 'fixed') {

            previewRate.textContent =
                parseFloat(rate).toFixed(2);

        } else {

            previewRate.textContent =
                parseFloat(rate).toFixed(2);

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Applies To Preview
    |--------------------------------------------------------------------------
    */

    function updateAppliesPreview()
    {
        const value = appliesInput.value;

        let text = '—';

        if (value === 'booking') {
            text = 'Booking';
        }

        if (value === 'vendor_payout') {
            text = 'Vendor Payout';
        }

        if (value === 'both') {
            text = 'Booking & Vendor Payout';
        }

        previewApplies.textContent = text;
    }


    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    typeInput.addEventListener(
        'change',
        updateTaxType
    );

    rateInput.addEventListener(
        'input',
        updateRatePreview
    );

    appliesInput.addEventListener(
        'change',
        updateAppliesPreview
    );


    /*
    |--------------------------------------------------------------------------
    | Initial State
    |--------------------------------------------------------------------------
    */

    updateTaxType();
    updateRatePreview();
    updateAppliesPreview();

    const initialName = nameField.value.trim();

    if (initialName) {
        previewName.textContent = initialName;
    }

});

</script>

@endpush