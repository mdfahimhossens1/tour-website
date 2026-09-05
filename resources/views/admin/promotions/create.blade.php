@extends('layouts.admin')

@section('title', 'Create Promotion')

@section('page')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-plus-circle me-2"></i>
                Create Promotion
            </h4>

            <p class="text-muted mb-0">
                Create a new promotional campaign or special offer.
            </p>
        </div>

        <a href="{{ route('admin.promotions.index') }}"
           class="btn btn-light border">

            <i class="fas fa-arrow-left me-1"></i>
            Back to Promotions

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <div class="fw-semibold mb-2">
                <i class="fas fa-exclamation-triangle me-1"></i>
                Please fix the following errors:
            </div>

            <ul class="mb-0">
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


    <form method="POST"
          action="{{ route('admin.promotions.store') }}">

        @csrf


        <div class="row g-4">

            {{-- ========================= --}}
            {{-- LEFT COLUMN --}}
            {{-- ========================= --}}
            <div class="col-lg-8">


                {{-- Basic Information --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Basic Information
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Promotion Name --}}
                            <div class="col-md-8">

                                <label for="name"
                                       class="form-label fw-semibold">

                                    Promotion Name
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="e.g. Summer Holiday Special"
                                       maxlength="255"
                                       required>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Promotion Code --}}
                            <div class="col-md-4">

                                <label for="code"
                                       class="form-label fw-semibold">

                                    Promotion Code

                                </label>

                                <input type="text"
                                       id="code"
                                       name="code"
                                       value="{{ old('code') }}"
                                       class="form-control text-uppercase @error('code') is-invalid @enderror"
                                       placeholder="e.g. SUMMER25"
                                       maxlength="100">

                                @error('code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Leave empty if no coupon code is required.
                                </div>

                            </div>


                            {{-- Description --}}
                            <div class="col-12">

                                <label for="description"
                                       class="form-label fw-semibold">

                                    Description

                                </label>

                                <textarea id="description"
                                          name="description"
                                          rows="4"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Describe this promotion or special offer...">{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>



                {{-- Discount Settings --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-percent text-success me-2"></i>
                            Discount Settings
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Discount Type --}}
                            <div class="col-md-5">

                                <label for="type"
                                       class="form-label fw-semibold">

                                    Discount Type
                                    <span class="text-danger">*</span>

                                </label>

                                <select id="type"
                                        name="type"
                                        class="form-select @error('type') is-invalid @enderror"
                                        required>

                                    <option value="percentage"
                                        {{ old('type', 'percentage') === 'percentage' ? 'selected' : '' }}>
                                        Percentage (%)
                                    </option>

                                    <option value="fixed"
                                        {{ old('type') === 'fixed' ? 'selected' : '' }}>
                                        Fixed Amount (৳)
                                    </option>

                                </select>

                                @error('type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Discount Value --}}
                            <div class="col-md-7">

                                <label for="value"
                                       class="form-label fw-semibold">

                                    Discount Value
                                    <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text"
                                          id="discountPrefix">

                                        %

                                    </span>

                                    <input type="number"
                                           id="value"
                                           name="value"
                                           value="{{ old('value', 0) }}"
                                           class="form-control @error('value') is-invalid @enderror"
                                           min="0"
                                           step="0.01"
                                           placeholder="10"
                                           required>

                                </div>

                                @error('value')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div id="discountHint"
                                     class="form-text">

                                    Example: 10 means 10% discount.

                                </div>

                            </div>


                            {{-- Minimum Amount --}}
                            <div class="col-md-6">

                                <label for="minimum_amount"
                                       class="form-label fw-semibold">

                                    Minimum Booking Amount

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        ৳
                                    </span>

                                    <input type="number"
                                           id="minimum_amount"
                                           name="minimum_amount"
                                           value="{{ old('minimum_amount', 0) }}"
                                           class="form-control @error('minimum_amount') is-invalid @enderror"
                                           min="0"
                                           step="0.01"
                                           placeholder="0">

                                </div>

                                @error('minimum_amount')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Minimum booking amount required.
                                </div>

                            </div>


                            {{-- Maximum Discount --}}
                            <div class="col-md-6">

                                <label for="maximum_discount"
                                       class="form-label fw-semibold">

                                    Maximum Discount

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        ৳
                                    </span>

                                    <input type="number"
                                           id="maximum_discount"
                                           name="maximum_discount"
                                           value="{{ old('maximum_discount') }}"
                                           class="form-control @error('maximum_discount') is-invalid @enderror"
                                           min="0"
                                           step="0.01"
                                           placeholder="Optional">

                                </div>

                                @error('maximum_discount')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Mainly useful for percentage discounts.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- Validity --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-calendar-alt text-info me-2"></i>
                            Promotion Validity
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Start Date --}}
                            <div class="col-md-6">

                                <label for="starts_at"
                                       class="form-label fw-semibold">

                                    Start Date & Time

                                </label>

                                <input type="datetime-local"
                                       id="starts_at"
                                       name="starts_at"
                                       value="{{ old('starts_at') }}"
                                       class="form-control @error('starts_at') is-invalid @enderror">

                                @error('starts_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Leave empty to start immediately.
                                </div>

                            </div>


                            {{-- End Date --}}
                            <div class="col-md-6">

                                <label for="ends_at"
                                       class="form-label fw-semibold">

                                    End Date & Time

                                </label>

                                <input type="datetime-local"
                                       id="ends_at"
                                       name="ends_at"
                                       value="{{ old('ends_at') }}"
                                       class="form-control @error('ends_at') is-invalid @enderror">

                                @error('ends_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Leave empty for no expiration.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- Usage Control --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-users text-warning me-2"></i>
                            Usage Control
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Usage Limit --}}
                            <div class="col-md-6">

                                <label for="usage_limit"
                                       class="form-label fw-semibold">

                                    Total Usage Limit

                                </label>

                                <input type="number"
                                       id="usage_limit"
                                       name="usage_limit"
                                       value="{{ old('usage_limit') }}"
                                       class="form-control @error('usage_limit') is-invalid @enderror"
                                       min="1"
                                       step="1"
                                       placeholder="Unlimited">

                                @error('usage_limit')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Maximum number of times this promotion can be used.
                                </div>

                            </div>


                            {{-- Usage Per User --}}
                            <div class="col-md-6">

                                <label for="usage_per_user"
                                       class="form-label fw-semibold">

                                    Usage Per User

                                </label>

                                <input type="number"
                                       id="usage_per_user"
                                       name="usage_per_user"
                                       value="{{ old('usage_per_user') }}"
                                       class="form-control @error('usage_per_user') is-invalid @enderror"
                                       min="1"
                                       step="1"
                                       placeholder="Unlimited">

                                @error('usage_per_user')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Maximum uses allowed for a single user.
                                </div>

                            </div>


                            {{-- Usage Info --}}
                            <div class="col-12">

                                <div class="alert alert-light border mb-0">

                                    <div class="d-flex">

                                        <i class="fas fa-info-circle text-primary mt-1 me-2"></i>

                                        <div>

                                            <div class="fw-semibold">
                                                Usage Tracking
                                            </div>

                                            <div class="small text-muted">
                                                Usage starts from 0 automatically.
                                                The system can increase the usage count
                                                when the promotion is successfully applied.
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- ========================= --}}
            {{-- RIGHT COLUMN --}}
            {{-- ========================= --}}
            <div class="col-lg-4">


                {{-- Status Settings --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-toggle-on text-primary me-2"></i>
                            Status & Display
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

                                <span class="fw-semibold d-block">
                                    Active Promotion
                                </span>

                                <span class="small text-muted">
                                    Allow this promotion to be used.
                                </span>

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

                                <span class="fw-semibold d-block">
                                    Featured Promotion
                                </span>

                                <span class="small text-muted">
                                    Highlight this promotion in promotional sections.
                                </span>

                            </label>

                        </div>


                        {{-- Sort Order --}}
                        <div>

                            <label for="sort_order"
                                   class="form-label fw-semibold">

                                Sort Order

                            </label>

                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', 0) }}"
                                   class="form-control @error('sort_order') is-invalid @enderror"
                                   min="0"
                                   max="999999"
                                   step="1">

                            @error('sort_order')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Lower numbers appear first.
                            </div>

                        </div>

                    </div>

                </div>



                {{-- Promotion Preview --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-eye text-success me-2"></i>
                            Quick Preview
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="border rounded p-3">

                            <div class="d-flex justify-content-between align-items-start mb-2">

                                <div>

                                    <div id="previewName"
                                         class="fw-bold">

                                        Promotion Name

                                    </div>

                                    <div id="previewCode"
                                         class="small text-muted">

                                        No code

                                    </div>

                                </div>

                                <span id="previewDiscount"
                                      class="badge bg-primary">

                                    10%

                                </span>

                            </div>


                            <div class="small text-muted"
                                 id="previewDescription">

                                Promotion description will appear here.

                            </div>

                        </div>

                    </div>

                </div>



                {{-- Actions --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <button type="submit"
                                class="btn btn-primary w-100 mb-2">

                            <i class="fas fa-save me-1"></i>
                            Create Promotion

                        </button>


                        <a href="{{ route('admin.promotions.index') }}"
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



{{-- ========================= --}}
{{-- Dynamic Form JavaScript --}}
{{-- ========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const typeSelect = document.getElementById('type');
    const valueInput = document.getElementById('value');
    const prefix = document.getElementById('discountPrefix');
    const hint = document.getElementById('discountHint');

    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    const descriptionInput = document.getElementById('description');

    const previewName = document.getElementById('previewName');
    const previewCode = document.getElementById('previewCode');
    const previewDescription = document.getElementById('previewDescription');
    const previewDiscount = document.getElementById('previewDiscount');


    function updateDiscountType() {

        if (typeSelect.value === 'percentage') {

            prefix.textContent = '%';

            valueInput.placeholder = '10';

            hint.textContent =
                'Example: 10 means 10% discount.';

        } else {

            prefix.textContent = '৳';

            valueInput.placeholder = '500';

            hint.textContent =
                'Example: 500 means ৳500 discount.';

        }

        updatePreview();
    }


    function updatePreview() {

        const name = nameInput.value.trim();
        const code = codeInput.value.trim();
        const description = descriptionInput.value.trim();
        const value = valueInput.value || 0;


        previewName.textContent =
            name || 'Promotion Name';


        previewCode.textContent =
            code ? 'Code: ' + code.toUpperCase() : 'No code';


        previewDescription.textContent =
            description ||
            'Promotion description will appear here.';


        if (typeSelect.value === 'percentage') {

            previewDiscount.textContent =
                value + '%';

        } else {

            previewDiscount.textContent =
                '৳' + value;

        }

    }


    typeSelect.addEventListener(
        'change',
        updateDiscountType
    );


    valueInput.addEventListener(
        'input',
        updatePreview
    );


    nameInput.addEventListener(
        'input',
        updatePreview
    );


    codeInput.addEventListener(
        'input',
        updatePreview
    );


    descriptionInput.addEventListener(
        'input',
        updatePreview
    );


    updateDiscountType();

});
</script>

@endsection