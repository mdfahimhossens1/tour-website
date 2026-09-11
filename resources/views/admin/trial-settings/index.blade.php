@extends('layouts.admin')

@section('title', 'Trial Settings')

@section('page')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-hourglass-half me-2 text-primary"></i>
                Trial Settings
            </h4>

            <p class="text-muted mb-0">
                Configure the default trial experience for your SaaS customers.
            </p>
        </div>

        {{-- Current Status --}}
        <div class="mt-3 mt-md-0">

            @if($trialSetting->isEnabled())

                <span class="badge bg-success px-3 py-2">
                    <i class="fas fa-check-circle me-1"></i>
                    Trial Enabled
                </span>

            @else

                <span class="badge bg-danger px-3 py-2">
                    <i class="fas fa-times-circle me-1"></i>
                    Trial Disabled
                </span>

            @endif

        </div>

    </div>


    {{-- =========================================================
        SUCCESS ALERT
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <h6 class="fw-bold mb-2">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Please fix the following errors:
            </h6>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        TRIAL OVERVIEW
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Trial Duration --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Trial Duration
                            </div>

                            <h3 class="fw-bold mb-0">
                                {{ $trialSetting->trial_days }}
                            </h3>

                            <small class="text-muted">
                                {{ $trialSetting->trial_days == 1 ? 'Day' : 'Days' }}
                            </small>

                        </div>

                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">

                            <i class="fas fa-calendar-day fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Payment Method --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Payment Method Required
                            </div>

                            <h5 class="fw-bold mb-0">

                                @if($trialSetting->requiresPaymentMethod())

                                    <span class="text-warning">
                                        Required
                                    </span>

                                @else

                                    <span class="text-success">
                                        Not Required
                                    </span>

                                @endif

                            </h5>

                        </div>

                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">

                            <i class="fas fa-credit-card fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Grace Period --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Grace Period
                            </div>

                            <h5 class="fw-bold mb-0">

                                {{ $trialSetting->grace_period_days }}

                                <small class="text-muted">
                                    {{ $trialSetting->grace_period_days == 1 ? 'Day' : 'Days' }}
                                </small>

                            </h5>

                        </div>

                        <div class="bg-info bg-opacity-10 text-info rounded-circle p-3">

                            <i class="fas fa-clock fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Expiration --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Expiration Action
                            </div>

                            <h6 class="fw-bold mb-0">
                                {{ $trialSetting->getExpirationActionLabel() }}
                            </h6>

                        </div>

                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3">

                            <i class="fas fa-exclamation-circle fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        MAIN SETTINGS FORM
    ========================================================== --}}
    <form
        method="POST"
        action="{{ route('admin.trial-settings.update') }}"
    >

        @csrf
        @method('PUT')


        {{-- =====================================================
            GENERAL TRIAL SETTINGS
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h6 class="mb-1 fw-bold">

                    <i class="fas fa-sliders-h me-2 text-primary"></i>

                    General Trial Settings

                </h6>

                <small class="text-muted">
                    Configure how the trial period works for new customers.
                </small>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- Enable Trial --}}
                    <div class="col-12">

                        <div class="border rounded p-3">

                            <div class="form-check form-switch">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="is_enabled"
                                    name="is_enabled"
                                    value="1"
                                    @checked(old('is_enabled', $trialSetting->is_enabled))
                                >

                                <label
                                    class="form-check-label fw-bold"
                                    for="is_enabled"
                                >
                                    Enable Free Trial
                                </label>

                            </div>

                            <small class="text-muted ms-5">
                                Allow new customers to start a trial subscription.
                            </small>

                        </div>

                    </div>


                    {{-- Trial Days --}}
                    <div class="col-md-6">

                        <label
                            for="trial_days"
                            class="form-label fw-semibold"
                        >
                            Trial Duration
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="trial_days"
                                id="trial_days"
                                class="form-control @error('trial_days') is-invalid @enderror"
                                value="{{ old('trial_days', $trialSetting->trial_days) }}"
                                min="1"
                                max="3650"
                                required
                            >

                            <span class="input-group-text">
                                Days
                            </span>

                        </div>

                        <small class="text-muted">
                            Maximum allowed duration: 3650 days.
                        </small>

                    </div>


                    {{-- Auto Start --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold d-block">
                            Trial Activation
                        </label>

                        <div class="border rounded p-3">

                            <div class="form-check form-switch">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="auto_start"
                                    name="auto_start"
                                    value="1"
                                    @checked(old('auto_start', $trialSetting->auto_start))
                                >

                                <label
                                    class="form-check-label fw-semibold"
                                    for="auto_start"
                                >
                                    Automatically Start Trial
                                </label>

                            </div>

                            <small class="text-muted">
                                Automatically activate the trial when a customer subscribes.
                            </small>

                        </div>

                    </div>


                    {{-- Payment Method --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold d-block">
                            Payment Method Requirement
                        </label>

                        <div class="border rounded p-3">

                            <div class="form-check form-switch">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="requires_payment_method"
                                    name="requires_payment_method"
                                    value="1"
                                    @checked(old('requires_payment_method', $trialSetting->requires_payment_method))
                                >

                                <label
                                    class="form-check-label fw-semibold"
                                    for="requires_payment_method"
                                >
                                    Require Payment Method
                                </label>

                            </div>

                            <small class="text-muted">
                                Customers must provide a payment method before starting the trial.
                            </small>

                        </div>

                    </div>


                    {{-- Expiration Action --}}
                    <div class="col-md-6">

                        <label
                            for="expiration_action"
                            class="form-label fw-semibold"
                        >
                            Trial Expiration Action
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="expiration_action"
                            id="expiration_action"
                            class="form-select @error('expiration_action') is-invalid @enderror"
                            required
                        >

                            <option value="suspend"
                                @selected(old('expiration_action', $trialSetting->expiration_action) === 'suspend')>
                                Suspend Subscription
                            </option>

                            <option value="cancel"
                                @selected(old('expiration_action', $trialSetting->expiration_action) === 'cancel')>
                                Cancel Subscription
                            </option>

                            <option value="downgrade"
                                @selected(old('expiration_action', $trialSetting->expiration_action) === 'downgrade')>
                                Downgrade Subscription
                            </option>

                        </select>

                        <small class="text-muted">
                            What should happen when a customer's trial expires?
                        </small>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            GRACE PERIOD & REMINDER
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h6 class="mb-1 fw-bold">

                    <i class="fas fa-stopwatch me-2 text-primary"></i>

                    Grace Period & Reminders

                </h6>

                <small class="text-muted">
                    Configure post-trial grace time and expiry reminders.
                </small>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- Grace Period --}}
                    <div class="col-md-6">

                        <label
                            for="grace_period_days"
                            class="form-label fw-semibold"
                        >
                            Grace Period
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="grace_period_days"
                                id="grace_period_days"
                                class="form-control @error('grace_period_days') is-invalid @enderror"
                                value="{{ old('grace_period_days', $trialSetting->grace_period_days) }}"
                                min="0"
                                max="365"
                                required
                            >

                            <span class="input-group-text">
                                Days
                            </span>

                        </div>

                        <small class="text-muted">
                            Set to 0 to disable the grace period.
                        </small>

                    </div>


                    {{-- Reminder Days --}}
                    <div class="col-md-6">

                        <label
                            for="reminder_days_before"
                            class="form-label fw-semibold"
                        >
                            Reminder Before Expiry
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="reminder_days_before"
                                id="reminder_days_before"
                                class="form-control @error('reminder_days_before') is-invalid @enderror"
                                value="{{ old('reminder_days_before', $trialSetting->reminder_days_before) }}"
                                min="0"
                                max="365"
                                required
                            >

                            <span class="input-group-text">
                                Days
                            </span>

                        </div>

                        <small class="text-muted">
                            Number of days before expiry to send the reminder.
                        </small>

                    </div>


                    {{-- Expiry Reminder --}}
                    <div class="col-12">

                        <div class="border rounded p-3">

                            <div class="form-check form-switch">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="send_expiry_reminder"
                                    name="send_expiry_reminder"
                                    value="1"
                                    @checked(old('send_expiry_reminder', $trialSetting->send_expiry_reminder))
                                >

                                <label
                                    class="form-check-label fw-semibold"
                                    for="send_expiry_reminder"
                                >
                                    Send Trial Expiry Reminder
                                </label>

                            </div>

                            <small class="text-muted ms-5">
                                Send customers a reminder before their trial expires.
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            NOTIFICATION SETTINGS
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h6 class="mb-1 fw-bold">

                    <i class="fas fa-bell me-2 text-primary"></i>

                    Trial Notifications

                </h6>

                <small class="text-muted">
                    Control automatic notifications related to trial subscriptions.
                </small>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Started --}}
                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="form-check form-switch">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="send_trial_started_notification"
                                    name="send_trial_started_notification"
                                    value="1"
                                    @checked(old('send_trial_started_notification', $trialSetting->send_trial_started_notification))
                                >

                                <label
                                    class="form-check-label fw-semibold"
                                    for="send_trial_started_notification"
                                >
                                    Trial Started
                                </label>

                            </div>

                            <small class="text-muted d-block mt-2">
                                Notify the customer when their trial starts.
                            </small>

                        </div>

                    </div>


                    {{-- Expiring --}}
                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="form-check form-switch">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="send_trial_expiring_notification"
                                    name="send_trial_expiring_notification"
                                    value="1"
                                    @checked(old('send_trial_expiring_notification', $trialSetting->send_trial_expiring_notification))
                                >

                                <label
                                    class="form-check-label fw-semibold"
                                    for="send_trial_expiring_notification"
                                >
                                    Trial Expiring
                                </label>

                            </div>

                            <small class="text-muted d-block mt-2">
                                Notify the customer when their trial is approaching expiry.
                            </small>

                        </div>

                    </div>


                    {{-- Expired --}}
                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <div class="form-check form-switch">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="send_trial_expired_notification"
                                    name="send_trial_expired_notification"
                                    value="1"
                                    @checked(old('send_trial_expired_notification', $trialSetting->send_trial_expired_notification))
                                >

                                <label
                                    class="form-check-label fw-semibold"
                                    for="send_trial_expired_notification"
                                >
                                    Trial Expired
                                </label>

                            </div>

                            <small class="text-muted d-block mt-2">
                                Notify the customer after the trial has expired.
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            DESCRIPTION
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h6 class="mb-1 fw-bold">

                    <i class="fas fa-align-left me-2 text-primary"></i>

                    Internal Notes

                </h6>

                <small class="text-muted">
                    Add optional notes about the current trial configuration.
                </small>

            </div>


            <div class="card-body">

                <textarea
                    name="description"
                    id="description"
                    rows="4"
                    class="form-control @error('description') is-invalid @enderror"
                    placeholder="Enter internal notes about the trial settings..."
                >{{ old('description', $trialSetting->description) }}</textarea>

            </div>

        </div>


        {{-- =====================================================
            SAVE BUTTON
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div>

                        <h6 class="fw-bold mb-1">
                            Save Trial Configuration
                        </h6>

                        <small class="text-muted">
                            These settings will apply to new trial subscriptions.
                        </small>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary px-4"
                    >

                        <i class="fas fa-save me-2"></i>

                        Save Trial Settings

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection