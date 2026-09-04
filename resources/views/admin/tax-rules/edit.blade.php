@extends('layouts.admin')

@section('title', 'Edit Tax Rule')

@section('page')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-edit text-primary me-2"></i>
                Edit Tax Rule
            </h4>

            <p class="text-muted mb-0">
                Update tax configuration and effective period.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.tax-rules.show', $taxRule->id) }}"
               class="btn btn-light border">

                <i class="fas fa-eye me-1"></i>
                View

            </a>

            <a href="{{ route('admin.tax-rules.index') }}"
               class="btn btn-light border">

                <i class="fas fa-arrow-left me-1"></i>
                Back

            </a>

        </div>

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
          action="{{ route('admin.tax-rules.update', $taxRule->id) }}">

        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- Main Content --}}
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
                                       id="taxName"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $taxRule->name) }}"
                                       placeholder="e.g. VAT">

                                @error('name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Name displayed to administrators.
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
                                       maxlength="100"
                                       class="form-control text-uppercase @error('code') is-invalid @enderror"
                                       value="{{ old('code', $taxRule->code) }}"
                                       placeholder="e.g. VAT_15">

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
                                          placeholder="Describe this tax rule...">{{ old('description', $taxRule->description) }}</textarea>

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

                            {{-- Type --}}
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
                                        {{ old('type', $taxRule->type) === 'percentage' ? 'selected' : '' }}>

                                        Percentage (%)

                                    </option>

                                    <option value="fixed"
                                        {{ old('type', $taxRule->type) === 'fixed' ? 'selected' : '' }}>

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
                                           value="{{ old('rate', $taxRule->rate) }}"
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
                                    Enter the applicable tax rate.
                                </small>

                            </div>


                            {{-- Applies To --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Applies To

                                    <span class="text-danger">*</span>

                                </label>

                                <select name="applies_to"
                                        id="appliesTo"
                                        class="form-select @error('applies_to') is-invalid @enderror">

                                    <option value="">
                                        Select Application
                                    </option>

                                    <option value="booking"
                                        {{ old('applies_to', $taxRule->applies_to) === 'booking' ? 'selected' : '' }}>

                                        Booking

                                    </option>

                                    <option value="vendor_payout"
                                        {{ old('applies_to', $taxRule->applies_to) === 'vendor_payout' ? 'selected' : '' }}>

                                        Vendor Payout

                                    </option>

                                    <option value="both"
                                        {{ old('applies_to', $taxRule->applies_to) === 'both' ? 'selected' : '' }}>

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
                                       value="{{ old('priority', $taxRule->priority) }}"
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
                                       id="startsAt"
                                       class="form-control @error('starts_at') is-invalid @enderror"
                                       value="{{ old(
                                           'starts_at',
                                           optional($taxRule->starts_at)->format('Y-m-d')
                                       ) }}">

                                @error('starts_at')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Leave empty for immediate effectiveness.
                                </small>

                            </div>


                            {{-- End Date --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    End Date
                                </label>

                                <input type="date"
                                       name="ends_at"
                                       id="endsAt"
                                       class="form-control @error('ends_at') is-invalid @enderror"
                                       value="{{ old(
                                           'ends_at',
                                           optional($taxRule->ends_at)->format('Y-m-d')
                                       ) }}">

                                @error('ends_at')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Leave empty for no expiry.
                                </small>

                            </div>

                        </div>


                        <div class="alert alert-info mt-3 mb-0">

                            <i class="fas fa-info-circle me-2"></i>

                            The tax calculation system will only use this
                            rule when it is active and within its effective
                            date range.

                        </div>

                    </div>

                </div>


                {{-- Update Button --}}
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

                                Update Tax Rule

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Right Sidebar --}}
            <div class="col-xl-4">

                {{-- Current Status --}}
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
                                   {{ old('is_active', $taxRule->is_active) ? 'checked' : '' }}>

                            <label class="form-check-label fw-semibold"
                                   for="isActive">

                                Active Tax Rule

                            </label>

                        </div>


                        <div class="mt-3">

                            @if($taxRule->is_active)

                                <span class="badge bg-success">

                                    <i class="fas fa-check-circle me-1"></i>

                                    Currently Active

                                </span>

                            @else

                                <span class="badge bg-secondary">

                                    <i class="fas fa-ban me-1"></i>

                                    Currently Inactive

                                </span>

                            @endif

                        </div>


                        <p class="text-muted small mt-2 mb-0">

                            Inactive tax rules will not be considered by
                            future tax calculations.

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
                                    {{ $taxRule->name }}
                                </strong>

                            </div>


                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Code
                                </span>

                                <strong id="previewCode">
                                    {{ $taxRule->code }}
                                </strong>

                            </div>


                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Type
                                </span>

                                <span id="previewType"
                                      class="badge">

                                    {{ ucfirst($taxRule->type) }}

                                </span>

                            </div>


                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Rate
                                </span>

                                <strong id="previewRate">
                                    {{ number_format((float) $taxRule->rate, 2) }}
                                </strong>

                            </div>


                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Applies To
                                </span>

                                <span id="previewApplies"
                                      class="text-end">

                                    {{ ucwords(str_replace('_', ' ', $taxRule->applies_to)) }}

                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Rule Information --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">

                            <i class="fas fa-info-circle text-primary me-2"></i>

                            Rule Information

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="small">

                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">
                                    Rule ID
                                </span>

                                <strong>
                                    #{{ $taxRule->id }}
                                </strong>

                            </div>


                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">
                                    Created
                                </span>

                                <strong>
                                    {{ $taxRule->created_at?->format('d M Y, h:i A') }}
                                </strong>

                            </div>


                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Last Updated
                                </span>

                                <strong>
                                    {{ $taxRule->updated_at?->format('d M Y, h:i A') }}
                                </strong>

                            </div>

                        </div>

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
    const appliesInput = document.getElementById('appliesTo');

    const previewName = document.getElementById('previewName');
    const previewCode = document.getElementById('previewCode');
    const previewType = document.getElementById('previewType');
    const previewRate = document.getElementById('previewRate');
    const previewApplies = document.getElementById('previewApplies');

    const rateSuffix = document.getElementById('rateSuffix');
    const rateHelp = document.getElementById('rateHelp');


    /*
    |--------------------------------------------------------------------------
    | Name Preview
    |--------------------------------------------------------------------------
    */

    nameInput.addEventListener('input', function () {

        previewName.textContent =
            this.value.trim() || '—';

    });


    /*
    |--------------------------------------------------------------------------
    | Code Uppercase + Preview
    |--------------------------------------------------------------------------
    */

    codeInput.addEventListener('input', function () {

        this.value = this.value.toUpperCase();

        previewCode.textContent =
            this.value.trim() || '—';

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

        }

        else if (type === 'fixed') {

            rateSuffix.textContent = 'Amount';

            rateHelp.textContent =
                'Enter the fixed tax amount.';

            rateInput.removeAttribute('max');

            previewType.textContent =
                'Fixed';

            previewType.className =
                'badge bg-warning text-dark';

        }

        else {

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

        const formatted =
            parseFloat(rate).toFixed(2);

        if (typeInput.value === 'percentage') {

            previewRate.textContent =
                formatted + '%';

        }

        else {

            previewRate.textContent =
                formatted;

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

        else if (value === 'vendor_payout') {
            text = 'Vendor Payout';
        }

        else if (value === 'both') {
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
    | Initial Load
    |--------------------------------------------------------------------------
    */

    updateTaxType();
    updateRatePreview();
    updateAppliesPreview();

});

</script>

@endpush