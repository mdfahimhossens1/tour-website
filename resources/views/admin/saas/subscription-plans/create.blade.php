@extends('layouts.admin')

@section('title', 'Create Subscription Plan')

@section('page')

<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-plus-circle me-2"></i>
                Create Subscription Plan
            </h4>

            <p class="text-muted mb-0">
                Create a new SaaS subscription plan.
            </p>
        </div>

        <div class="mt-2 mt-md-0">

            <a href="{{ route('admin.subscription-plans.index') }}"
               class="btn btn-light border">

                <i class="fas fa-arrow-left me-1"></i>
                Back to Plans

            </a>

        </div>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <div class="fw-semibold mb-2">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Please fix the following errors:
            </div>

            <ul class="mb-0 ps-3">

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
         CREATE FORM
    ========================================================== --}}
    <form method="POST"
          action="{{ route('admin.subscription-plans.store') }}">

        @csrf


        <div class="row g-4">

            {{-- =================================================
                 LEFT COLUMN
            ================================================== --}}
            <div class="col-lg-8">

                {{-- =============================================
                     BASIC INFORMATION
                ============================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Basic Information
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Plan Name --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Plan Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="e.g. Professional"
                                       required>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Slug --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Slug
                                </label>

                                <input type="text"
                                       name="slug"
                                       class="form-control @error('slug') is-invalid @enderror"
                                       value="{{ old('slug') }}"
                                       placeholder="e.g. professional">

                                <small class="text-muted">
                                    Leave empty to generate automatically.
                                </small>

                                @error('slug')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Description --}}
                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Description
                                </label>

                                <textarea name="description"
                                          rows="4"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Describe this subscription plan...">{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =============================================
                     PRICING
                ============================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-dollar-sign text-success me-2"></i>
                            Pricing
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Price --}}
                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Price
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        $
                                    </span>

                                    <input type="number"
                                           name="price"
                                           class="form-control @error('price') is-invalid @enderror"
                                           value="{{ old('price', 0) }}"
                                           min="0"
                                           step="0.01"
                                           placeholder="49.00"
                                           required>

                                </div>

                                @error('price')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Currency --}}
                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Currency
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="currency"
                                        class="form-select @error('currency') is-invalid @enderror">

                                    <option value="USD"
                                        {{ old('currency', 'USD') === 'USD' ? 'selected' : '' }}>
                                        USD - US Dollar
                                    </option>

                                    <option value="EUR"
                                        {{ old('currency') === 'EUR' ? 'selected' : '' }}>
                                        EUR - Euro
                                    </option>

                                    <option value="GBP"
                                        {{ old('currency') === 'GBP' ? 'selected' : '' }}>
                                        GBP - British Pound
                                    </option>

                                    <option value="BDT"
                                        {{ old('currency') === 'BDT' ? 'selected' : '' }}>
                                        BDT - Bangladeshi Taka
                                    </option>

                                    <option value="AUD"
                                        {{ old('currency') === 'AUD' ? 'selected' : '' }}>
                                        AUD - Australian Dollar
                                    </option>

                                    <option value="CAD"
                                        {{ old('currency') === 'CAD' ? 'selected' : '' }}>
                                        CAD - Canadian Dollar
                                    </option>

                                </select>

                                @error('currency')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Billing Cycle --}}
                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Billing Cycle
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="billing_cycle"
                                        id="billing_cycle"
                                        class="form-select @error('billing_cycle') is-invalid @enderror"
                                        required>

                                    <option value="monthly"
                                        {{ old('billing_cycle', 'monthly') === 'monthly' ? 'selected' : '' }}>
                                        Monthly
                                    </option>

                                    <option value="yearly"
                                        {{ old('billing_cycle') === 'yearly' ? 'selected' : '' }}>
                                        Yearly
                                    </option>

                                    <option value="lifetime"
                                        {{ old('billing_cycle') === 'lifetime' ? 'selected' : '' }}>
                                        Lifetime
                                    </option>

                                </select>

                                @error('billing_cycle')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        {{-- Pricing Note --}}
                        <div class="alert alert-info mt-4 mb-0">

                            <i class="fas fa-lightbulb me-2"></i>

                            <strong>Tip:</strong>

                            Use <strong>Lifetime</strong> for one-time
                            purchase plans and <strong>Monthly/Yearly</strong>
                            for recurring subscriptions.

                        </div>

                    </div>

                </div>


                {{-- =============================================
                     USAGE LIMITS
                ============================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">

                            <i class="fas fa-chart-bar text-warning me-2"></i>

                            Usage Limits

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="alert alert-warning">

                            <i class="fas fa-info-circle me-2"></i>

                            Leave a limit empty to allow
                            <strong>Unlimited</strong> usage.

                        </div>


                        <div class="row g-3">

                            {{-- Vendors --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Maximum Vendors
                                </label>

                                <input type="number"
                                       name="max_vendors"
                                       class="form-control @error('max_vendors') is-invalid @enderror"
                                       value="{{ old('max_vendors') }}"
                                       min="0"
                                       placeholder="e.g. 5">

                                <small class="text-muted">
                                    Leave empty for unlimited vendors.
                                </small>

                                @error('max_vendors')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Tours --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Maximum Tours
                                </label>

                                <input type="number"
                                       name="max_tours"
                                       class="form-control @error('max_tours') is-invalid @enderror"
                                       value="{{ old('max_tours') }}"
                                       min="0"
                                       placeholder="e.g. 50">

                                <small class="text-muted">
                                    Leave empty for unlimited tours.
                                </small>

                                @error('max_tours')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Bookings --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Maximum Bookings
                                </label>

                                <input type="number"
                                       name="max_bookings"
                                       class="form-control @error('max_bookings') is-invalid @enderror"
                                       value="{{ old('max_bookings') }}"
                                       min="0"
                                       placeholder="e.g. 500">

                                <small class="text-muted">
                                    Leave empty for unlimited bookings.
                                </small>

                                @error('max_bookings')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Storage --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Maximum Storage
                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           name="max_storage_mb"
                                           class="form-control @error('max_storage_mb') is-invalid @enderror"
                                           value="{{ old('max_storage_mb') }}"
                                           min="0"
                                           placeholder="e.g. 2048">

                                    <span class="input-group-text">
                                        MB
                                    </span>

                                </div>

                                <small class="text-muted">
                                    Leave empty for unlimited storage.
                                </small>

                                @error('max_storage_mb')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 RIGHT COLUMN
            ================================================== --}}
            <div class="col-lg-4">

                {{-- =============================================
                     PLAN STATUS
                ============================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">

                            <i class="fas fa-cog text-secondary me-2"></i>

                            Plan Settings

                        </h5>

                    </div>


                    <div class="card-body">

                        {{-- Active --}}
                        <div class="form-check form-switch mb-4">

                            <input class="form-check-input"
                                   type="checkbox"
                                   role="switch"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}>

                            <label class="form-check-label"
                                   for="is_active">

                                <strong>Active Plan</strong>

                                <div class="text-muted small">
                                    Customers can subscribe to this plan.
                                </div>

                            </label>

                        </div>


                        {{-- Featured --}}
                        <div class="form-check form-switch mb-4">

                            <input class="form-check-input"
                                   type="checkbox"
                                   role="switch"
                                   id="is_featured"
                                   name="is_featured"
                                   value="1"
                                   {{ old('is_featured') ? 'checked' : '' }}>

                            <label class="form-check-label"
                                   for="is_featured">

                                <strong>Featured Plan</strong>

                                <div class="text-muted small">
                                    Highlight this plan on pricing pages.
                                </div>

                            </label>

                        </div>


                        {{-- Sort Order --}}
                        <div>

                            <label class="form-label fw-semibold">
                                Sort Order
                            </label>

                            <input type="number"
                                   name="sort_order"
                                   class="form-control @error('sort_order') is-invalid @enderror"
                                   value="{{ old('sort_order', 0) }}"
                                   min="0"
                                   placeholder="0">

                            <small class="text-muted">
                                Lower numbers appear first.
                            </small>

                            @error('sort_order')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =============================================
                     PLAN PREVIEW
                ============================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">

                            <i class="fas fa-eye text-info me-2"></i>

                            Quick Preview

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="text-center">

                            <div class="rounded-circle
                                        bg-primary
                                        bg-opacity-10
                                        text-primary
                                        d-inline-flex
                                        align-items-center
                                        justify-content-center
                                        mb-3"
                                 style="width:65px;height:65px;">

                                <i class="fas fa-crown fa-lg"></i>

                            </div>


                            <h5 id="preview-plan-name"
                                class="fw-bold mb-2">

                                {{ old('name', 'Plan Name') }}

                            </h5>


                            <div class="mb-3">

                                <span class="display-6 fw-bold"
                                      id="preview-price">

                                    {{ old('price', '0.00') }}

                                </span>

                                <span class="text-muted"
                                      id="preview-currency">

                                    USD

                                </span>

                            </div>


                            <span class="badge bg-primary"
                                  id="preview-cycle">

                                Monthly

                            </span>

                        </div>

                    </div>

                </div>


                {{-- =============================================
                     ACTIONS
                ============================================== --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <button type="submit"
                                class="btn btn-primary w-100 mb-2">

                            <i class="fas fa-save me-1"></i>

                            Create Subscription Plan

                        </button>


                        <a href="{{ route('admin.subscription-plans.index') }}"
                           class="btn btn-light border w-100">

                            <i class="fas fa-times me-1"></i>

                            Cancel

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection


{{-- =============================================================
     LIVE PREVIEW
============================================================== --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const nameInput = document.querySelector('input[name="name"]');
    const priceInput = document.querySelector('input[name="price"]');
    const currencyInput = document.querySelector('select[name="currency"]');
    const cycleInput = document.querySelector('select[name="billing_cycle"]');

    const previewName = document.getElementById('preview-plan-name');
    const previewPrice = document.getElementById('preview-price');
    const previewCurrency = document.getElementById('preview-currency');
    const previewCycle = document.getElementById('preview-cycle');


    function updatePreview() {

        /*
        |--------------------------------------------------------------------------
        | Plan Name
        |--------------------------------------------------------------------------
        */

        previewName.textContent =
            nameInput.value.trim() || 'Plan Name';


        /*
        |--------------------------------------------------------------------------
        | Price
        |--------------------------------------------------------------------------
        */

        previewPrice.textContent =
            priceInput.value || '0.00';


        /*
        |--------------------------------------------------------------------------
        | Currency
        |--------------------------------------------------------------------------
        */

        previewCurrency.textContent =
            currencyInput.value || 'USD';


        /*
        |--------------------------------------------------------------------------
        | Billing Cycle
        |--------------------------------------------------------------------------
        */

        const selectedOption =
            cycleInput.options[cycleInput.selectedIndex];

        previewCycle.textContent =
            selectedOption.text;

    }


    nameInput.addEventListener(
        'input',
        updatePreview
    );

    priceInput.addEventListener(
        'input',
        updatePreview
    );

    currencyInput.addEventListener(
        'change',
        updatePreview
    );

    cycleInput.addEventListener(
        'change',
        updatePreview
    );


    updatePreview();

});

</script>

@endpush