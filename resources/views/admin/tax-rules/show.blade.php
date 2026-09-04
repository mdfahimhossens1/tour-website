@extends('layouts.admin')

@section('title', 'Tax Rule Details')

@section('page')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <h4 class="mb-0">
                    <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                    Tax Rule Details
                </h4>

                @if($taxRule->is_active)

                    <span class="badge bg-success">
                        <i class="fas fa-check-circle me-1"></i>
                        Active
                    </span>

                @else

                    <span class="badge bg-secondary">
                        <i class="fas fa-ban me-1"></i>
                        Inactive
                    </span>

                @endif

            </div>

            <p class="text-muted mb-0">
                View complete information about this tax rule.
            </p>

        </div>


        {{-- Header Actions --}}
        <div class="d-flex gap-2">

            <a href="{{ route('admin.tax-rules.edit', $taxRule->id) }}"
               class="btn btn-primary">

                <i class="fas fa-edit me-1"></i>
                Edit

            </a>

            <a href="{{ route('admin.tax-rules.index') }}"
               class="btn btn-light border">

                <i class="fas fa-arrow-left me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- Flash Messages --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="row g-4">

        {{-- Main Content --}}
        <div class="col-xl-8">

            {{-- Tax Overview --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">

                            <i class="fas fa-info-circle text-primary me-2"></i>

                            Tax Rule Overview

                        </h5>

                        <span class="text-muted small">
                            ID #{{ $taxRule->id }}
                        </span>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        {{-- Tax Name --}}
                        <div class="col-md-6">

                            <div class="detail-item">

                                <div class="detail-label">
                                    Tax Name
                                </div>

                                <div class="detail-value">

                                    <i class="fas fa-tag text-primary me-2"></i>

                                    {{ $taxRule->name }}

                                </div>

                            </div>

                        </div>


                        {{-- Tax Code --}}
                        <div class="col-md-6">

                            <div class="detail-item">

                                <div class="detail-label">
                                    Tax Code
                                </div>

                                <div class="detail-value">

                                    <span class="code-badge">
                                        {{ $taxRule->code }}
                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- Type --}}
                        <div class="col-md-6">

                            <div class="detail-item">

                                <div class="detail-label">
                                    Tax Type
                                </div>

                                <div class="detail-value">

                                    @if($taxRule->type === 'percentage')

                                        <span class="badge bg-info text-dark">

                                            <i class="fas fa-percentage me-1"></i>

                                            Percentage

                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">

                                            <i class="fas fa-money-bill-wave me-1"></i>

                                            Fixed Amount

                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- Rate --}}
                        <div class="col-md-6">

                            <div class="detail-item">

                                <div class="detail-label">
                                    Tax Rate
                                </div>

                                <div class="detail-value rate-value">

                                    {{ number_format((float) $taxRule->rate, 2) }}

                                    @if($taxRule->type === 'percentage')
                                        %
                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- Applies To --}}
                        <div class="col-md-6">

                            <div class="detail-item">

                                <div class="detail-label">
                                    Applies To
                                </div>

                                <div class="detail-value">

                                    @switch($taxRule->applies_to)

                                        @case('booking')

                                            <span class="badge bg-primary">

                                                <i class="fas fa-ticket-alt me-1"></i>

                                                Booking

                                            </span>

                                            @break

                                        @case('vendor_payout')

                                            <span class="badge bg-dark">

                                                <i class="fas fa-store me-1"></i>

                                                Vendor Payout

                                            </span>

                                            @break

                                        @case('both')

                                            <span class="badge bg-purple text-dark">

                                                <i class="fas fa-layer-group me-1"></i>

                                                Booking & Vendor Payout

                                            </span>

                                            @break

                                    @endswitch

                                </div>

                            </div>

                        </div>


                        {{-- Priority --}}
                        <div class="col-md-6">

                            <div class="detail-item">

                                <div class="detail-label">
                                    Priority
                                </div>

                                <div class="detail-value">

                                    <span class="priority-badge">
                                        {{ $taxRule->priority }}
                                    </span>

                                    <small class="text-muted ms-2">
                                        Lower number = higher priority
                                    </small>

                                </div>

                            </div>

                        </div>


                        {{-- Description --}}
                        <div class="col-12">

                            <div class="detail-item">

                                <div class="detail-label">
                                    Description
                                </div>

                                <div class="description-box">

                                    @if($taxRule->description)

                                        {!! nl2br(e($taxRule->description)) !!}

                                    @else

                                        <span class="text-muted">
                                            No description provided.
                                        </span>

                                    @endif

                                </div>

                            </div>

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

                    <div class="row g-4">

                        {{-- Start --}}
                        <div class="col-md-6">

                            <div class="date-box">

                                <div class="date-icon">

                                    <i class="fas fa-play"></i>

                                </div>

                                <div>

                                    <div class="detail-label">
                                        Starts At
                                    </div>

                                    @if($taxRule->starts_at)

                                        <div class="date-value">

                                            {{ $taxRule->starts_at->format('d M Y') }}

                                        </div>

                                    @else

                                        <div class="date-value text-muted">
                                            No start date
                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- End --}}
                        <div class="col-md-6">

                            <div class="date-box">

                                <div class="date-icon">

                                    <i class="fas fa-stop"></i>

                                </div>

                                <div>

                                    <div class="detail-label">
                                        Ends At
                                    </div>

                                    @if($taxRule->ends_at)

                                        <div class="date-value">

                                            {{ $taxRule->ends_at->format('d M Y') }}

                                        </div>

                                    @else

                                        <div class="date-value text-muted">
                                            No expiry date
                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Effective Status --}}
                    @php

                        $today = now()->startOfDay();

                        $isStarted = !$taxRule->starts_at ||
                            $taxRule->starts_at->startOfDay()->lte($today);

                        $isNotExpired = !$taxRule->ends_at ||
                            $taxRule->ends_at->startOfDay()->gte($today);

                        $currentlyEffective =
                            $taxRule->is_active &&
                            $isStarted &&
                            $isNotExpired;

                    @endphp


                    <div class="mt-4">

                        @if($currentlyEffective)

                            <div class="alert alert-success mb-0">

                                <i class="fas fa-check-circle me-2"></i>

                                <strong>This tax rule is currently effective.</strong>

                                It can be considered by the tax calculation
                                system.

                            </div>

                        @elseif(!$taxRule->is_active)

                            <div class="alert alert-secondary mb-0">

                                <i class="fas fa-ban me-2"></i>

                                <strong>This tax rule is inactive.</strong>

                                It will not be used for new calculations.

                            </div>

                        @elseif(!$isStarted)

                            <div class="alert alert-warning mb-0">

                                <i class="fas fa-clock me-2"></i>

                                <strong>This tax rule has not started yet.</strong>

                            </div>

                        @elseif(!$isNotExpired)

                            <div class="alert alert-danger mb-0">

                                <i class="fas fa-calendar-times me-2"></i>

                                <strong>This tax rule has expired.</strong>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Calculation Example --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">

                        <i class="fas fa-calculator text-primary me-2"></i>

                        Calculation Example

                    </h5>

                </div>


                <div class="card-body">

                    @if($taxRule->type === 'percentage')

                        <div class="calculation-example">

                            <div class="row align-items-center">

                                <div class="col-md-4">

                                    <div class="example-label">
                                        Base Amount
                                    </div>

                                    <div class="example-value">
                                        10,000.00
                                    </div>

                                </div>

                                <div class="col-md-1 text-center">

                                    <i class="fas fa-times text-muted"></i>

                                </div>

                                <div class="col-md-3">

                                    <div class="example-label">
                                        Tax Rate
                                    </div>

                                    <div class="example-value">
                                        {{ number_format((float) $taxRule->rate, 2) }}%
                                    </div>

                                </div>

                                <div class="col-md-1 text-center">

                                    <i class="fas fa-equals text-muted"></i>

                                </div>

                                <div class="col-md-3">

                                    <div class="example-label">
                                        Tax Amount
                                    </div>

                                    <div class="example-value text-primary">

                                        {{ number_format(
                                            10000 * ((float) $taxRule->rate / 100),
                                            2
                                        ) }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    @else

                        <div class="calculation-example">

                            <div class="row align-items-center">

                                <div class="col-md-4">

                                    <div class="example-label">
                                        Base Amount
                                    </div>

                                    <div class="example-value">
                                        10,000.00
                                    </div>

                                </div>

                                <div class="col-md-1 text-center">

                                    <i class="fas fa-plus text-muted"></i>

                                </div>

                                <div class="col-md-3">

                                    <div class="example-label">
                                        Fixed Tax
                                    </div>

                                    <div class="example-value">

                                        {{ number_format((float) $taxRule->rate, 2) }}

                                    </div>

                                </div>

                                <div class="col-md-1 text-center">

                                    <i class="fas fa-equals text-muted"></i>

                                </div>

                                <div class="col-md-3">

                                    <div class="example-label">
                                        Total With Tax
                                    </div>

                                    <div class="example-value text-primary">

                                        {{ number_format(
                                            10000 + (float) $taxRule->rate,
                                            2
                                        ) }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endif


                    <div class="alert alert-light border mt-3 mb-0 small">

                        <i class="fas fa-info-circle me-2"></i>

                        This is only a demonstration example using a
                        base amount of <strong>10,000.00</strong>.
                        Actual tax calculation will be handled by the
                        application's tax calculation service.

                    </div>

                </div>

            </div>


            {{-- Timestamps --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">

                        <i class="fas fa-history text-primary me-2"></i>

                        Record Information

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="timeline-info">

                                <span class="timeline-icon">
                                    <i class="fas fa-plus"></i>
                                </span>

                                <div>

                                    <small class="text-muted d-block">
                                        Created At
                                    </small>

                                    <strong>
                                        {{ $taxRule->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                    </strong>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="timeline-info">

                                <span class="timeline-icon">
                                    <i class="fas fa-sync-alt"></i>
                                </span>

                                <div>

                                    <small class="text-muted d-block">
                                        Last Updated
                                    </small>

                                    <strong>
                                        {{ $taxRule->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Right Sidebar --}}
        <div class="col-xl-4">

            {{-- Status Card --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">

                        <i class="fas fa-toggle-on text-primary me-2"></i>

                        Rule Status

                    </h5>

                </div>


                <div class="card-body text-center">

                    <div class="status-icon
                        {{ $taxRule->is_active ? 'status-active' : 'status-inactive' }}">

                        @if($taxRule->is_active)

                            <i class="fas fa-check-circle"></i>

                        @else

                            <i class="fas fa-ban"></i>

                        @endif

                    </div>


                    <h5 class="mt-3 mb-1">

                        {{ $taxRule->is_active ? 'Active Tax Rule' : 'Inactive Tax Rule' }}

                    </h5>


                    <p class="text-muted small mb-4">

                        @if($taxRule->is_active)

                            This rule is enabled for tax calculations.

                        @else

                            This rule is currently disabled.

                        @endif

                    </p>


                    {{-- Toggle --}}
                    <form method="POST"
                          action="{{ route('admin.tax-rules.toggle-status', $taxRule->id) }}">

                        @csrf

                        @if($taxRule->is_active)

                            <button type="submit"
                                    class="btn btn-outline-warning w-100">

                                <i class="fas fa-toggle-off me-1"></i>

                                Deactivate Rule

                            </button>

                        @else

                            <button type="submit"
                                    class="btn btn-outline-success w-100">

                                <i class="fas fa-toggle-on me-1"></i>

                                Activate Rule

                            </button>

                        @endif

                    </form>

                </div>

            </div>


            {{-- Quick Actions --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">

                        <i class="fas fa-bolt text-warning me-2"></i>

                        Quick Actions

                    </h5>

                </div>


                <div class="card-body p-2">

                    <a href="{{ route('admin.tax-rules.edit', $taxRule->id) }}"
                       class="quick-action">

                        <span>

                            <i class="fas fa-edit text-primary"></i>

                            Edit Tax Rule

                        </span>

                        <i class="fas fa-chevron-right text-muted"></i>

                    </a>


                    <a href="{{ route('admin.tax-rules.index') }}"
                       class="quick-action">

                        <span>

                            <i class="fas fa-list text-info"></i>

                            All Tax Rules

                        </span>

                        <i class="fas fa-chevron-right text-muted"></i>

                    </a>


                    <button type="button"
                            class="quick-action w-100 border-0 bg-transparent text-start"
                            onclick="confirmDelete()">

                        <span>

                            <i class="fas fa-trash-alt text-danger"></i>

                            Delete Tax Rule

                        </span>

                        <i class="fas fa-chevron-right text-muted"></i>

                    </button>

                </div>

            </div>


            {{-- Applicability --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">

                        <i class="fas fa-crosshairs text-primary me-2"></i>

                        Applicability

                    </h5>

                </div>


                <div class="card-body">

                    @if($taxRule->applies_to === 'booking')

                        <div class="application-box">

                            <i class="fas fa-ticket-alt"></i>

                            <div>

                                <strong>Booking</strong>

                                <small>
                                    Applied to eligible booking calculations.
                                </small>

                            </div>

                        </div>

                    @elseif($taxRule->applies_to === 'vendor_payout')

                        <div class="application-box">

                            <i class="fas fa-store"></i>

                            <div>

                                <strong>Vendor Payout</strong>

                                <small>
                                    Applied to eligible vendor payout calculations.
                                </small>

                            </div>

                        </div>

                    @else

                        <div class="application-box">

                            <i class="fas fa-layer-group"></i>

                            <div>

                                <strong>Booking & Vendor Payout</strong>

                                <small>
                                    Applicable to both calculation flows.
                                </small>

                            </div>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Rule Summary --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">

                        <i class="fas fa-list-ul text-primary me-2"></i>

                        Rule Summary

                    </h5>

                </div>


                <div class="card-body">

                    <div class="summary-row">

                        <span class="text-muted">
                            Code
                        </span>

                        <strong>
                            {{ $taxRule->code }}
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span class="text-muted">
                            Type
                        </span>

                        <strong>
                            {{ ucfirst($taxRule->type) }}
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span class="text-muted">
                            Rate
                        </span>

                        <strong>

                            {{ number_format((float) $taxRule->rate, 2) }}

                            @if($taxRule->type === 'percentage')
                                %
                            @endif

                        </strong>

                    </div>


                    <div class="summary-row">

                        <span class="text-muted">
                            Priority
                        </span>

                        <strong>
                            {{ $taxRule->priority }}
                        </strong>

                    </div>


                    <div class="summary-row border-0 pb-0">

                        <span class="text-muted">
                            Status
                        </span>

                        @if($taxRule->is_active)

                            <span class="badge bg-success">
                                Active
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Inactive
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- Delete Confirmation Modal --}}
<div class="modal fade"
     id="deleteTaxModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title text-danger">

                    <i class="fas fa-trash-alt me-2"></i>

                    Delete Tax Rule

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body">

                <p class="mb-2">
                    Are you sure you want to delete this tax rule?
                </p>

                <div class="p-3 bg-light rounded">

                    <strong>
                        {{ $taxRule->name }}
                    </strong>

                    <div class="small text-muted mt-1">
                        Code: {{ $taxRule->code }}
                    </div>

                </div>


                <div class="alert alert-warning mt-3 mb-0">

                    <i class="fas fa-exclamation-triangle me-2"></i>

                    This action cannot be undone.

                </div>

            </div>


            <div class="modal-footer">

                <button type="button"
                        class="btn btn-light border"
                        data-bs-dismiss="modal">

                    Cancel

                </button>


                <form method="POST"
                      action="{{ route('admin.tax-rules.destroy', $taxRule->id) }}">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-danger">

                        <i class="fas fa-trash-alt me-1"></i>

                        Delete Tax Rule

                    </button>

                </form>

            </div>

        </div>

    </div>

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

    .detail-item {
        height: 100%;
    }

    .detail-label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 7px;
    }

    .detail-value {
        font-size: 15px;
        font-weight: 600;
        color: #212529;
    }

    .code-badge {
        display: inline-block;
        padding: 6px 10px;
        background: #f1f3f5;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-family: monospace;
        font-size: 13px;
        color: #495057;
    }

    .rate-value {
        font-size: 24px;
        font-weight: 700;
    }

    .priority-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 30px;
        padding: 0 8px;
        border-radius: 7px;
        background: #f1f3f5;
        border: 1px solid #dee2e6;
        font-weight: 700;
    }

    .description-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 9px;
        padding: 15px;
        line-height: 1.7;
        color: #495057;
    }

    .date-box {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .date-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, .1);
        color: #0d6efd;
    }

    .date-value {
        font-size: 16px;
        font-weight: 700;
    }

    .calculation-example {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 22px;
    }

    .example-label {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 5px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .example-value {
        font-size: 20px;
        font-weight: 700;
    }

    .timeline-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .timeline-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f3f5;
        color: #6c757d;
    }

    .status-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }

    .status-active {
        background: rgba(25, 135, 84, .1);
        color: #198754;
    }

    .status-inactive {
        background: rgba(108, 117, 125, .1);
        color: #6c757d;
    }

    .quick-action {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        border-radius: 8px;
        text-decoration: none;
        color: #212529;
        transition: .15s ease;
    }

    .quick-action:hover {
        background: #f8f9fa;
        color: #212529;
    }

    .quick-action span {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quick-action span i {
        width: 20px;
        text-align: center;
    }

    .application-box {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .application-box > i {
        font-size: 20px;
        margin-top: 2px;
    }

    .application-box strong {
        display: block;
        margin-bottom: 3px;
    }

    .application-box small {
        display: block;
        color: #6c757d;
        line-height: 1.5;
    }

    .summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
    }

    .bg-purple {
        background: rgba(111, 66, 193, .12) !important;
    }

    @media (max-width: 767px) {

        .rate-value {
            font-size: 20px;
        }

        .calculation-example {
            padding: 15px;
        }

        .calculation-example .row > div {
            margin-bottom: 15px;
        }

        .calculation-example .row > div:last-child {
            margin-bottom: 0;
        }

    }

</style>

@endpush


@push('scripts')

<script>

function confirmDelete()
{
    const modalElement =
        document.getElementById('deleteTaxModal');

    const modal =
        new bootstrap.Modal(modalElement);

    modal.show();
}

</script>

@endpush