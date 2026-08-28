@extends('layouts.vendor')

@section('title', 'Payment Methods')

@section('page')

<style>

    .pm-page {
        padding: 10px 0 30px;
    }

    .pm-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .pm-title h4 {
        margin: 0;
        font-weight: 700;
    }

    .pm-title p {
        margin: 5px 0 0;
        color: #6c757d;
        font-size: 14px;
    }

    .pm-card {
        border: 0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 3px 15px rgba(0, 0, 0, .06);
    }

    .pm-table {
        margin: 0;
        vertical-align: middle;
    }

    .pm-table th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #6c757d;
        background: #f8f9fa;
        padding: 15px;
        white-space: nowrap;
    }

    .pm-table td {
        padding: 15px;
        vertical-align: middle;
    }

    .pm-method-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        background: #f1f3f5;
    }

    .pm-method-name {
        font-weight: 700;
        color: #212529;
    }

    .pm-method-type {
        color: #6c757d;
        font-size: 12px;
        margin-top: 3px;
        text-transform: capitalize;
    }

    .pm-number {
        font-family: monospace;
        font-weight: 600;
        color: #495057;
    }

    .pm-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .pm-status.active {
        background: #e8f7ee;
        color: #198754;
    }

    .pm-status.inactive {
        background: #fff0f0;
        color: #dc3545;
    }

    .pm-service {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .pm-service.transport {
        background: #e8f1ff;
        color: #003580;
    }

    .pm-service.resort {
        background: #fff4e5;
        color: #b45309;
    }

    .pm-service.both {
        background: #f0e8ff;
        color: #6d28d9;
    }

    .pm-action-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: .2s;
    }

    .pm-action-btn:hover {
        transform: translateY(-2px);
    }

    .pm-empty {
        padding: 65px 20px;
        text-align: center;
        color: #6c757d;
    }

    .pm-empty i {
        font-size: 45px;
        margin-bottom: 15px;
        opacity: .5;
    }

    .pm-modal .modal-content {
        border: 0;
        border-radius: 15px;
        overflow: hidden;
    }

    .pm-modal .modal-header {
        border-bottom: 1px solid #eee;
        padding: 18px 22px;
    }

    .pm-modal .modal-body {
        padding: 22px;
    }

    .pm-modal .form-label {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .pm-modal .form-control,
    .pm-modal .form-select {
        border-radius: 9px;
        padding: 10px 13px;
    }

    .pm-service-info {
        border-radius: 10px;
        background: #f8f9fa;
        padding: 12px 14px;
        font-size: 12px;
        color: #6c757d;
    }

    @media (max-width: 768px) {

        .pm-table {
            min-width: 900px;
        }

    }

</style>


<div class="container-fluid pm-page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="pm-header">

        <div class="pm-title">

            <h4>
                <i class="fas fa-credit-card me-2"></i>
                Payment Methods
            </h4>

            <p>
                Manage payment accounts for your transport and resort services.
            </p>

        </div>


        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addPaymentMethodModal"
        >
            <i class="fas fa-plus me-1"></i>
            Add Payment Method
        </button>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

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


    {{-- =========================================================
         PAYMENT METHODS TABLE
    ========================================================== --}}

    <div class="card pm-card">

        <div class="table-responsive">

            <table class="table pm-table">

                <thead>

                    <tr>

                        <th>
                            Payment Method
                        </th>

                        <th>
                            Used For
                        </th>

                        <th>
                            Account / Number
                        </th>

                        <th>
                            Description
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-end">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($methods as $method)

                        <tr>

                            {{-- =================================================
                                 PAYMENT METHOD
                            ================================================== --}}

                            <td>

                                <div class="d-flex align-items-center gap-3">

                                    <div class="pm-method-icon">

                                        @switch($method->type)

                                            @case('bkash')

                                                <i class="fas fa-mobile-alt"></i>

                                                @break

                                            @case('nagad')

                                                <i class="fas fa-wallet"></i>

                                                @break

                                            @case('bank')

                                                <i class="fas fa-university"></i>

                                                @break

                                            @case('paypal')

                                                <i class="fab fa-paypal"></i>

                                                @break

                                            @case('stripe')

                                                <i class="fas fa-credit-card"></i>

                                                @break

                                            @default

                                                <i class="fas fa-money-bill-wave"></i>

                                        @endswitch

                                    </div>


                                    <div>

                                        <div class="pm-method-name">

                                            {{ $method->name }}

                                        </div>

                                        <div class="pm-method-type">

                                            {{ $method->type_label }}

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                 SERVICE TYPE
                            ================================================== --}}

                            <td>

                                @if($method->service_type === 'transport')

                                    <span class="pm-service transport">

                                        <i class="fas fa-car"></i>

                                        Transport

                                    </span>

                                @elseif($method->service_type === 'resort')

                                    <span class="pm-service resort">

                                        <i class="fas fa-hotel"></i>

                                        Resort

                                    </span>

                                @else

                                    <span class="pm-service both">

                                        <i class="fas fa-layer-group"></i>

                                        Transport & Resort

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ACCOUNT NUMBER
                            ================================================== --}}

                            <td>

                                <span class="pm-number">

                                    {{ $method->account_number ?: '—' }}

                                </span>

                            </td>


                            {{-- =================================================
                                 DESCRIPTION
                            ================================================== --}}

                            <td>

                                {{ $method->description
                                    ? \Illuminate\Support\Str::limit(
                                        $method->description,
                                        50
                                    )
                                    : '—'
                                }}

                            </td>


                            {{-- =================================================
                                 STATUS
                            ================================================== --}}

                            <td>

                                @if($method->status)

                                    <span class="pm-status active">

                                        <i class="fas fa-check-circle"></i>

                                        Active

                                    </span>

                                @else

                                    <span class="pm-status inactive">

                                        <i class="fas fa-times-circle"></i>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ACTION
                            ================================================== --}}

                            <td class="text-end">

                                <button
                                    type="button"
                                    class="pm-action-btn text-primary bg-light me-1"
                                    title="Edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editPaymentMethodModal{{ $method->id }}"
                                >

                                    <i class="fas fa-edit"></i>

                                </button>


                                @if($method->status)

                                    <form
                                        action="{{ route(
                                            'vendor.payment-methods.destroy',
                                            $method->id
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to deactivate this payment method?')"
                                    >

                                        @csrf

                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="pm-action-btn text-danger bg-light"
                                            title="Deactivate"
                                        >

                                            <i class="fas fa-ban"></i>

                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>


                        {{-- =====================================================
                             EDIT MODAL
                        ====================================================== --}}

                        <div
                            class="modal fade pm-modal"
                            id="editPaymentMethodModal{{ $method->id }}"
                            tabindex="-1"
                        >

                            <div class="modal-dialog modal-lg modal-dialog-centered">

                                <div class="modal-content">

                                    <form
                                        action="{{ route(
                                            'vendor.payment-methods.update',
                                            $method->id
                                        ) }}"
                                        method="POST"
                                    >

                                        @csrf

                                        @method('PUT')


                                        <div class="modal-header">

                                            <h5 class="modal-title">

                                                <i class="fas fa-edit me-2"></i>

                                                Edit Payment Method

                                            </h5>


                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>


                                        <div class="modal-body">

                                            <div class="row g-3">

                                                {{-- Method Name --}}

                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Method Name *
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="name"
                                                        class="form-control"
                                                        value="{{ $method->name }}"
                                                        required
                                                    >

                                                </div>


                                                {{-- Payment Type --}}

                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Payment Type *
                                                    </label>

                                                    <select
                                                        name="type"
                                                        class="form-select"
                                                        required
                                                    >

                                                        @foreach([
                                                            'bkash' => 'bKash',
                                                            'nagad' => 'Nagad',
                                                            'bank' => 'Bank',
                                                            'stripe' => 'Stripe',
                                                            'paypal' => 'PayPal',
                                                            'manual' => 'Manual',
                                                        ] as $value => $label)

                                                            <option
                                                                value="{{ $value }}"
                                                                @selected(
                                                                    $method->type === $value
                                                                )
                                                            >
                                                                {{ $label }}
                                                            </option>

                                                        @endforeach

                                                    </select>

                                                </div>


                                                {{-- Service Type --}}

                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Payment For *
                                                    </label>

                                                    <select
                                                        name="service_type"
                                                        class="form-select"
                                                        required
                                                    >

                                                        <option
                                                            value="transport"
                                                            @selected(
                                                                $method->service_type === 'transport'
                                                            )
                                                        >
                                                            Transport
                                                        </option>

                                                        <option
                                                            value="resort"
                                                            @selected(
                                                                $method->service_type === 'resort'
                                                            )
                                                        >
                                                            Resort
                                                        </option>

                                                        <option
                                                            value="both"
                                                            @selected(
                                                                $method->service_type === 'both'
                                                            )
                                                        >
                                                            Transport & Resort
                                                        </option>

                                                    </select>

                                                    <div class="pm-service-info mt-2">

                                                        <i class="fas fa-info-circle me-1"></i>

                                                        Select where customers can use
                                                        this payment method.

                                                    </div>

                                                </div>


                                                {{-- Account Number --}}

                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Account / Number
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="account_number"
                                                        class="form-control"
                                                        value="{{ $method->account_number }}"
                                                    >

                                                </div>


                                                {{-- Status --}}

                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Status *
                                                    </label>

                                                    <select
                                                        name="status"
                                                        class="form-select"
                                                        required
                                                    >

                                                        <option
                                                            value="1"
                                                            @selected($method->status)
                                                        >
                                                            Active
                                                        </option>

                                                        <option
                                                            value="0"
                                                            @selected(!$method->status)
                                                        >
                                                            Inactive
                                                        </option>

                                                    </select>

                                                </div>


                                                {{-- API Key --}}

                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        API Key

                                                        <small class="text-muted">
                                                            (Optional)
                                                        </small>

                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="api_key"
                                                        class="form-control"
                                                        value="{{ $method->api_key }}"
                                                    >

                                                </div>


                                                {{-- Secret Key --}}

                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        Secret Key

                                                        <small class="text-muted">
                                                            (Optional)
                                                        </small>

                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="secret_key"
                                                        class="form-control"
                                                        value="{{ $method->secret_key }}"
                                                    >

                                                </div>


                                                {{-- Description --}}

                                                <div class="col-12">

                                                    <label class="form-label">
                                                        Description
                                                    </label>

                                                    <textarea
                                                        name="description"
                                                        class="form-control"
                                                        rows="3"
                                                    >{{ $method->description }}</textarea>

                                                </div>

                                            </div>

                                        </div>


                                        <div class="modal-footer">

                                            <button
                                                type="button"
                                                class="btn btn-light"
                                                data-bs-dismiss="modal"
                                            >
                                                Cancel
                                            </button>


                                            <button
                                                type="submit"
                                                class="btn btn-primary"
                                            >

                                                <i class="fas fa-save me-1"></i>

                                                Update Payment Method

                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                    @empty

                        <tr>

                            <td colspan="6">

                                <div class="pm-empty">

                                    <i class="fas fa-credit-card d-block"></i>

                                    <h5>
                                        No Payment Methods Yet
                                    </h5>

                                    <p class="mb-0">

                                        Add bKash, Nagad, Bank or other
                                        payment accounts for your services.

                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- =========================================================
     ADD PAYMENT METHOD MODAL
========================================================= --}}

<div
    class="modal fade pm-modal"
    id="addPaymentMethodModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form
                action="{{ route('vendor.payment-methods.store') }}"
                method="POST"
            >

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="fas fa-plus-circle me-2"></i>

                        Add Payment Method

                    </h5>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    {{-- Basic Information --}}

                    <h6 class="mb-3">
                        Basic Information
                    </h6>


                    <div class="row g-3">

                        {{-- Name --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Method Name *
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="e.g. Personal bKash"
                                required
                            >

                        </div>


                        {{-- Payment Type --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Payment Type *
                            </label>

                            <select
                                name="type"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Payment Type
                                </option>

                                <option value="bkash">
                                    bKash
                                </option>

                                <option value="nagad">
                                    Nagad
                                </option>

                                <option value="bank">
                                    Bank
                                </option>

                                <option value="stripe">
                                    Stripe
                                </option>

                                <option value="paypal">
                                    PayPal
                                </option>

                                <option value="manual">
                                    Manual
                                </option>

                            </select>

                        </div>


                        {{-- SERVICE TYPE --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Payment For *
                            </label>

                            <select
                                name="service_type"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Service
                                </option>

                                <option value="transport">
                                    🚗 Transport
                                </option>

                                <option value="resort">
                                    🏨 Resort
                                </option>

                                <option value="both">
                                    🔄 Transport & Resort
                                </option>

                            </select>


                            <div class="pm-service-info mt-2">

                                <i class="fas fa-info-circle me-1"></i>

                                Choose whether this payment method is
                                for Transport, Resort, or both.

                            </div>

                        </div>


                        {{-- Account Number --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Account / Number
                            </label>

                            <input
                                type="text"
                                name="account_number"
                                class="form-control"
                                placeholder="017XXXXXXXX"
                            >

                        </div>


                        {{-- Status --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Status *
                            </label>

                            <select
                                name="status"
                                class="form-select"
                                required
                            >

                                <option
                                    value="1"
                                    selected
                                >
                                    Active
                                </option>

                                <option value="0">
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- API Credentials --}}

                    <div class="border-top mt-4 pt-4">

                        <h6 class="mb-3">

                            API Credentials

                            <small class="text-muted">
                                (Optional)
                            </small>

                        </h6>


                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    API Key
                                </label>

                                <input
                                    type="text"
                                    name="api_key"
                                    class="form-control"
                                    placeholder="Optional API Key"
                                >

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Secret Key
                                </label>

                                <input
                                    type="text"
                                    name="secret_key"
                                    class="form-control"
                                    placeholder="Optional Secret Key"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- Description --}}

                    <div class="border-top mt-4 pt-4">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="3"
                            placeholder="Optional payment instructions for customers..."
                        ></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="fas fa-save me-1"></i>

                        Save Payment Method

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection