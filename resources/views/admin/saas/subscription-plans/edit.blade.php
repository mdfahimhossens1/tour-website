@extends('layouts.admin')

@section('title', 'Edit Subscription Plan')

@section('page')

<div class="container-fluid">

    {{-- ============================================================
         PAGE HEADER
    ============================================================ --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-edit me-2"></i>
                Edit Subscription Plan
            </h4>

            <p class="text-muted mb-0">
                Update subscription plan information and usage limits.
            </p>
        </div>

        <a href="{{ route('admin.subscription-plans.index') }}"
           class="btn btn-light border">
            <i class="fas fa-arrow-left me-1"></i>
            Back to Plans
        </a>

    </div>


    {{-- ============================================================
         VALIDATION ERRORS
    ============================================================ --}}
    @if($errors->any())

        <div class="alert alert-danger shadow-sm">

            <div class="fw-bold mb-2">
                <i class="fas fa-exclamation-triangle me-1"></i>
                Please fix the following errors:
            </div>

            <ul class="mb-0 ps-3">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- ============================================================
         EDIT FORM
    ============================================================ --}}
    <form action="{{ route('admin.subscription-plans.update', $subscriptionPlan) }}"
          method="POST">

        @csrf
        @method('PUT')


        <div class="row">

            {{-- ====================================================
                 LEFT COLUMN
            ==================================================== --}}
            <div class="col-lg-8">

                {{-- =================================================
                     BASIC INFORMATION
                ================================================= --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Basic Information
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- PLAN NAME --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Plan Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $subscriptionPlan->name) }}"
                                    placeholder="e.g. Professional"
                                    required
                                >

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- SLUG --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Slug
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="slug"
                                    id="slug"
                                    class="form-control @error('slug') is-invalid @enderror"
                                    value="{{ old('slug', $subscriptionPlan->slug) }}"
                                    placeholder="professional"
                                    required
                                >

                                <small class="text-muted">
                                    URL-friendly unique identifier.
                                </small>

                                @error('slug')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- DESCRIPTION --}}
                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Description
                                </label>

                                <textarea
                                    name="description"
                                    rows="4"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Describe what this plan offers..."
                                >{{ old('description', $subscriptionPlan->description) }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     PRICING
                ================================================= --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="mb-0">
                            <i class="fas fa-dollar-sign text-success me-2"></i>
                            Pricing
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- PRICE --}}
                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Price
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    name="price"
                                    id="price"
                                    class="form-control @error('price') is-invalid @enderror"
                                    value="{{ old('price', $subscriptionPlan->price) }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="49.00"
                                    required
                                >

                                @error('price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- CURRENCY --}}
                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Currency
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="currency"
                                    id="currency"
                                    class="form-select @error('currency') is-invalid @enderror"
                                    required
                                >

                                    @foreach([
                                        'USD' => 'US Dollar (USD)',
                                        'EUR' => 'Euro (EUR)',
                                        'GBP' => 'British Pound (GBP)',
                                        'BDT' => 'Bangladeshi Taka (BDT)',
                                        'AUD' => 'Australian Dollar (AUD)',
                                        'CAD' => 'Canadian Dollar (CAD)',
                                    ] as $code => $label)

                                        <option
                                            value="{{ $code }}"
                                            {{ old('currency', $subscriptionPlan->currency) === $code ? 'selected' : '' }}
                                        >
                                            {{ $label }}
                                        </option>

                                    @endforeach

                                </select>

                                @error('currency')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- BILLING CYCLE --}}
                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Billing Cycle
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="billing_cycle"
                                    id="billing_cycle"
                                    class="form-select @error('billing_cycle') is-invalid @enderror"
                                    required
                                >

                                    <option
                                        value="monthly"
                                        {{ old('billing_cycle', $subscriptionPlan->billing_cycle) === 'monthly' ? 'selected' : '' }}
                                    >
                                        Monthly
                                    </option>

                                    <option
                                        value="yearly"
                                        {{ old('billing_cycle', $subscriptionPlan->billing_cycle) === 'yearly' ? 'selected' : '' }}
                                    >
                                        Yearly
                                    </option>

                                    <option
                                        value="lifetime"
                                        {{ old('billing_cycle', $subscriptionPlan->billing_cycle) === 'lifetime' ? 'selected' : '' }}
                                    >
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

                    </div>

                </div>


                {{-- =================================================
                     USAGE LIMITS
                ================================================= --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="mb-0">
                            <i class="fas fa-sliders-h text-info me-2"></i>
                            Usage Limits
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="alert alert-info">

                            <i class="fas fa-info-circle me-1"></i>

                            Leave a limit empty for
                            <strong>Unlimited</strong> usage.

                        </div>


                        <div class="row g-3">

                            {{-- MAX VENDORS --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Maximum Vendors
                                </label>

                                <input
                                    type="number"
                                    name="max_vendors"
                                    class="form-control @error('max_vendors') is-invalid @enderror"
                                    value="{{ old('max_vendors', $subscriptionPlan->max_vendors) }}"
                                    min="1"
                                    placeholder="Leave empty for unlimited"
                                >

                                @error('max_vendors')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- MAX TOURS --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Maximum Tours
                                </label>

                                <input
                                    type="number"
                                    name="max_tours"
                                    class="form-control @error('max_tours') is-invalid @enderror"
                                    value="{{ old('max_tours', $subscriptionPlan->max_tours) }}"
                                    min="1"
                                    placeholder="Leave empty for unlimited"
                                >

                                @error('max_tours')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- MAX BOOKINGS --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Maximum Bookings
                                </label>

                                <input
                                    type="number"
                                    name="max_bookings"
                                    class="form-control @error('max_bookings') is-invalid @enderror"
                                    value="{{ old('max_bookings', $subscriptionPlan->max_bookings) }}"
                                    min="1"
                                    placeholder="Leave empty for unlimited"
                                >

                                @error('max_bookings')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- STORAGE --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Maximum Storage (MB)
                                </label>

                                <input
                                    type="number"
                                    name="max_storage_mb"
                                    class="form-control @error('max_storage_mb') is-invalid @enderror"
                                    value="{{ old('max_storage_mb', $subscriptionPlan->max_storage_mb) }}"
                                    min="1"
                                    placeholder="e.g. 10240"
                                >

                                <small class="text-muted">
                                    Example: 10240 MB = 10 GB
                                </small>

                                @error('max_storage_mb')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                 RIGHT COLUMN
            ==================================================== --}}
            <div class="col-lg-4">

                {{-- =================================================
                     PLAN SETTINGS
                ================================================= --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="mb-0">
                            <i class="fas fa-cog text-secondary me-2"></i>
                            Plan Settings
                        </h5>

                    </div>


                    <div class="card-body">

                        {{-- ACTIVE --}}
                        <div class="form-check form-switch mb-4">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                role="switch"
                                id="is_active"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $subscriptionPlan->is_active) ? 'checked' : '' }}
                            >

                            <label class="form-check-label fw-semibold"
                                   for="is_active">

                                Active Plan

                            </label>

                            <div class="small text-muted mt-1">
                                Customers can subscribe to active plans.
                            </div>

                        </div>


                        {{-- FEATURED --}}
                        <div class="form-check form-switch mb-4">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                role="switch"
                                id="is_featured"
                                name="is_featured"
                                value="1"
                                {{ old('is_featured', $subscriptionPlan->is_featured) ? 'checked' : '' }}
                            >

                            <label class="form-check-label fw-semibold"
                                   for="is_featured">

                                Featured Plan

                            </label>

                            <div class="small text-muted mt-1">
                                Highlight this plan on the pricing page.
                            </div>

                        </div>


                        {{-- SORT ORDER --}}
                        <div>

                            <label class="form-label fw-semibold">
                                Sort Order
                            </label>

                            <input
                                type="number"
                                name="sort_order"
                                class="form-control @error('sort_order') is-invalid @enderror"
                                value="{{ old('sort_order', $subscriptionPlan->sort_order) }}"
                                min="0"
                            >

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


                {{-- =================================================
                     CURRENT PLAN INFO
                ================================================= --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="mb-0">
                            <i class="fas fa-history text-primary me-2"></i>
                            Current Plan
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="mb-3">

                            <div class="text-muted small">
                                Plan ID
                            </div>

                            <div class="fw-bold">
                                #{{ $subscriptionPlan->id }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Created
                            </div>

                            <div class="fw-semibold">
                                {{ $subscriptionPlan->created_at?->format('d M Y, h:i A') ?? '-' }}
                            </div>

                        </div>


                        <div>

                            <div class="text-muted small">
                                Last Updated
                            </div>

                            <div class="fw-semibold">
                                {{ $subscriptionPlan->updated_at?->format('d M Y, h:i A') ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     QUICK PREVIEW
                ================================================= --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="mb-0">
                            <i class="fas fa-eye text-success me-2"></i>
                            Live Preview
                        </h5>

                    </div>


                    <div class="card-body text-center">

                        <h5 id="previewName" class="fw-bold mb-2">
                            {{ old('name', $subscriptionPlan->name) }}
                        </h5>

                        <div class="display-6 fw-bold mb-1">

                            <span id="previewCurrency">
                                {{ old('currency', $subscriptionPlan->currency) }}
                            </span>

                            <span id="previewPrice">
                                {{ number_format((float) old('price', $subscriptionPlan->price), 2) }}
                            </span>

                        </div>

                        <div class="text-muted"
                             id="previewCycle">

                            {{ ucfirst(old('billing_cycle', $subscriptionPlan->billing_cycle)) }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
             FORM ACTIONS
        ========================================================= --}}
        <div class="d-flex justify-content-between align-items-center mb-5">

            <a href="{{ route('admin.subscription-plans.index') }}"
               class="btn btn-light border">

                <i class="fas fa-times me-1"></i>
                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary px-4">

                <i class="fas fa-save me-1"></i>
                Update Subscription Plan

            </button>

        </div>

    </form>

</div>

@endsection


{{-- ================================================================
     LIVE PREVIEW SCRIPT
================================================================ --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const nameInput = document.getElementById('name');
    const priceInput = document.getElementById('price');
    const currencyInput = document.getElementById('currency');
    const cycleInput = document.getElementById('billing_cycle');

    const previewName = document.getElementById('previewName');
    const previewPrice = document.getElementById('previewPrice');
    const previewCurrency = document.getElementById('previewCurrency');
    const previewCycle = document.getElementById('previewCycle');


    function updatePreview() {

        if (nameInput.value.trim() !== '') {
            previewName.textContent = nameInput.value;
        } else {
            previewName.textContent = 'Plan Name';
        }


        const price = parseFloat(priceInput.value);

        if (!isNaN(price)) {

            previewPrice.textContent = price.toFixed(2);

        } else {

            previewPrice.textContent = '0.00';

        }


        previewCurrency.textContent = currencyInput.value;


        const cycle = cycleInput.value;

        if (cycle === 'monthly') {

            previewCycle.textContent = 'Monthly';

        } else if (cycle === 'yearly') {

            previewCycle.textContent = 'Yearly';

        } else if (cycle === 'lifetime') {

            previewCycle.textContent = 'Lifetime';

        } else {

            previewCycle.textContent = '';

        }

    }


    nameInput.addEventListener('input', updatePreview);

    priceInput.addEventListener('input', updatePreview);

    currencyInput.addEventListener('change', updatePreview);

    cycleInput.addEventListener('change', updatePreview);


    updatePreview();

});

</script>

@endpush