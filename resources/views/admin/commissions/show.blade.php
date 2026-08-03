@extends('layouts.admin')

@section('title', 'Commission Details')

@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
    --cd-surface: #1a1d27;
    --cd-surface2: #222636;
    --cd-border: rgba(255,255,255,.07);

    --cd-text: #e2e8f0;
    --cd-muted: #64748b;

    --cd-indigo: #6366f1;
    --cd-purple: #8b5cf6;
    --cd-success: #22c55e;
    --cd-danger: #ef4444;
    --cd-warning: #f59e0b;
    --cd-info: #0ea5e9;

    --cd-radius: 14px;
    --cd-shadow: 0 8px 32px rgba(0,0,0,.45);
}

.cd-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--cd-text);
}


/* =========================================================
   HEADER
========================================================= */

.cd-header {
    background:
        linear-gradient(
            135deg,
            #0c1a2e 0%,
            #0e0c2e 55%,
            #0c1a2e 100%
        );

    border-radius: var(--cd-radius);

    padding: 26px 30px;

    margin-bottom: 22px;

    box-shadow: var(--cd-shadow);

    position: relative;

    overflow: hidden;
}

.cd-header::before {
    content: '';

    position: absolute;

    inset: 0;

    background:
        url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}

.cd-header-content {
    position: relative;

    z-index: 1;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    flex-wrap: wrap;
}

.cd-title {
    font-size: 1.35rem;

    font-weight: 700;

    background:
        linear-gradient(
            90deg,
            #fff,
            #a5b4fc
        );

    -webkit-background-clip: text;

    -webkit-text-fill-color: transparent;
}

.cd-subtitle {
    color: rgba(255,255,255,.45);

    font-size: .8rem;

    margin-top: 5px;
}


/* =========================================================
   BACK BUTTON
========================================================= */

.cd-back {
    display: inline-flex;

    align-items: center;

    gap: 7px;

    background: rgba(255,255,255,.06);

    border: 1px solid rgba(255,255,255,.1);

    color: #cbd5e1;

    padding: 8px 14px;

    border-radius: 8px;

    text-decoration: none;

    font-size: .78rem;

    font-weight: 600;

    transition: all .2s;
}

.cd-back:hover {
    background: rgba(255,255,255,.1);

    color: #fff;

    transform: translateY(-1px);
}


/* =========================================================
   GRID
========================================================= */

.cd-grid {
    display: grid;

    grid-template-columns:
        minmax(0, 1.4fr)
        minmax(300px, .8fr);

    gap: 20px;

    align-items: start;
}


/* =========================================================
   CARD
========================================================= */

.cd-card {
    background: var(--cd-surface);

    border: 1px solid var(--cd-border);

    border-radius: var(--cd-radius);

    box-shadow: var(--cd-shadow);

    overflow: hidden;

    margin-bottom: 20px;
}

.cd-card:last-child {
    margin-bottom: 0;
}

.cd-card-header {
    background: var(--cd-surface2);

    border-bottom: 1px solid var(--cd-border);

    padding: 16px 20px;

    display: flex;

    align-items: center;

    gap: 9px;

    font-size: .82rem;

    font-weight: 700;
}

.cd-card-header i {
    color: #a5b4fc;
}


/* =========================================================
   INFO GRID
========================================================= */

.cd-info-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 1px;

    background: var(--cd-border);
}

.cd-info {
    background: var(--cd-surface);

    padding: 17px 20px;
}

.cd-label {
    color: var(--cd-muted);

    font-size: .67rem;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .07em;

    margin-bottom: 5px;
}

.cd-value {
    color: var(--cd-text);

    font-size: .84rem;

    font-weight: 600;

    word-break: break-word;
}

.cd-value.mono {
    font-family: 'JetBrains Mono', monospace;

    color: #a5b4fc;

    font-size: .78rem;
}


/* =========================================================
   VENDOR / CUSTOMER PROFILE
========================================================= */

.cd-profile {
    padding: 20px;

    display: flex;

    align-items: center;

    gap: 13px;
}

.cd-avatar {
    width: 46px;

    height: 46px;

    border-radius: 50%;

    background:
        linear-gradient(
            135deg,
            var(--cd-indigo),
            var(--cd-purple)
        );

    display: flex;

    align-items: center;

    justify-content: center;

    color: #fff;

    font-size: .95rem;

    font-weight: 700;

    flex-shrink: 0;

    text-transform: uppercase;
}

.cd-profile-name {
    font-size: .9rem;

    font-weight: 700;
}

.cd-profile-email {
    color: var(--cd-muted);

    font-size: .72rem;

    margin-top: 2px;
}


/* =========================================================
   EARNING CARDS
========================================================= */

.cd-earnings {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 14px;

    padding: 18px 20px;
}

.cd-earning {
    border-radius: 11px;

    padding: 18px;

    border: 1px solid;
}

.cd-earning-admin {
    background: rgba(34,197,94,.08);

    border-color: rgba(34,197,94,.2);
}

.cd-earning-vendor {
    background: rgba(139,92,246,.08);

    border-color: rgba(139,92,246,.2);
}

.cd-earning-label {
    color: var(--cd-muted);

    font-size: .68rem;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .06em;
}

.cd-earning-value {
    font-family: 'JetBrains Mono', monospace;

    font-size: 1.3rem;

    font-weight: 700;

    margin-top: 7px;
}

.cd-earning-admin .cd-earning-value {
    color: #86efac;
}

.cd-earning-vendor .cd-earning-value {
    color: #c4b5fd;
}


/* =========================================================
   COMMISSION RATE
========================================================= */

.cd-rate-box {
    padding: 20px;
}

.cd-rate {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    background:
        rgba(245,158,11,.08);

    border:
        1px solid rgba(245,158,11,.2);

    border-radius: 11px;

    padding: 18px;
}

.cd-rate-label {
    color: var(--cd-muted);

    font-size: .7rem;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .06em;
}

.cd-rate-value {
    color: #fcd34d;

    font-family: 'JetBrains Mono', monospace;

    font-size: 1.25rem;

    font-weight: 700;
}


/* =========================================================
   STATUS
========================================================= */

.cd-status {
    display: inline-flex;

    align-items: center;

    gap: 6px;

    padding: 5px 10px;

    border-radius: 20px;

    font-size: .7rem;

    font-weight: 700;

    text-transform: capitalize;
}

.cd-status-success {
    background: rgba(34,197,94,.12);

    color: #86efac;

    border: 1px solid rgba(34,197,94,.2);
}

.cd-status-pending {
    background: rgba(245,158,11,.12);

    color: #fcd34d;

    border: 1px solid rgba(245,158,11,.2);
}

.cd-status-danger {
    background: rgba(239,68,68,.12);

    color: #fca5a5;

    border: 1px solid rgba(239,68,68,.2);
}

.cd-status-info {
    background: rgba(14,165,233,.12);

    color: #7dd3fc;

    border: 1px solid rgba(14,165,233,.2);
}


/* =========================================================
   SPECIAL REQUEST
========================================================= */

.cd-request {
    padding: 18px 20px;

    color: #cbd5e1;

    font-size: .82rem;

    line-height: 1.7;

    white-space: pre-wrap;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 950px) {

    .cd-grid {
        grid-template-columns: 1fr;
    }

}

@media (max-width: 600px) {

    .cd-info-grid {
        grid-template-columns: 1fr;
    }

    .cd-earnings {
        grid-template-columns: 1fr;
    }

    .cd-header {
        padding: 22px;
    }

    .cd-title {
        font-size: 1.15rem;
    }

}
</style>


@php

    $booking = $commission->booking;

    $vendor = $booking?->vendor;

    $user = $booking?->user;

    $tour = $booking?->tour;

    $tourDate = $booking?->tourDate;

    $transaction = $booking?->transaction;

@endphp


<div class="cd-wrap">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="cd-header">

        <div class="cd-header-content">

            <div>

                <div class="cd-title">

                    <i class="fas fa-file-invoice-dollar me-2"></i>

                    Commission Details

                </div>

                <div class="cd-subtitle">

                    Complete financial breakdown for this booking.

                </div>

            </div>


            <a
                href="{{ route('admin.commissions.index') }}"
                class="cd-back"
            >

                <i class="fas fa-arrow-left"></i>

                Back to Commissions

            </a>

        </div>

    </div>


    {{-- =====================================================
         MAIN GRID
    ====================================================== --}}

    <div class="cd-grid">


        {{-- =================================================
             LEFT SIDE
        ================================================== --}}

        <div>


            {{-- BOOKING INFORMATION --}}

            <div class="cd-card">

                <div class="cd-card-header">

                    <i class="fas fa-ticket-alt"></i>

                    Booking Information

                </div>


                <div class="cd-info-grid">


                    <div class="cd-info">

                        <div class="cd-label">

                            Booking Code

                        </div>

                        <div class="cd-value mono">

                            {{ $booking?->booking_code ?? '—' }}

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Booking Date

                        </div>

                        <div class="cd-value">

                            {{ $booking?->created_at?->format('d M Y, h:i A') ?? '—' }}

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Booking Status

                        </div>

                        <div class="cd-value">

                            @if($booking?->booking_status === 'confirmed')

                                <span class="cd-status cd-status-success">

                                    <i class="fas fa-check-circle"></i>

                                    Confirmed

                                </span>

                            @elseif($booking?->booking_status === 'cancelled')

                                <span class="cd-status cd-status-danger">

                                    <i class="fas fa-times-circle"></i>

                                    Cancelled

                                </span>

                            @else

                                <span class="cd-status cd-status-pending">

                                    <i class="fas fa-clock"></i>

                                    {{ ucfirst($booking?->booking_status ?? 'Unknown') }}

                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Payment Status

                        </div>

                        <div class="cd-value">

                            @if($booking?->payment_status === 'paid')

                                <span class="cd-status cd-status-success">

                                    <i class="fas fa-check"></i>

                                    Paid

                                </span>

                            @elseif($booking?->payment_status === 'failed')

                                <span class="cd-status cd-status-danger">

                                    <i class="fas fa-times"></i>

                                    Failed

                                </span>

                            @else

                                <span class="cd-status cd-status-pending">

                                    <i class="fas fa-clock"></i>

                                    {{ ucfirst($booking?->payment_status ?? 'Unknown') }}

                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Person Count

                        </div>

                        <div class="cd-value">

                            {{ $booking?->person_count ?? 0 }}

                            {{ ($booking?->person_count ?? 0) == 1 ? 'Person' : 'People' }}

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Payment Method

                        </div>

                        <div class="cd-value">

                            {{ $transaction?->payment_method
                                ? ucfirst(str_replace('_', ' ', $transaction->payment_method))
                                : '—'
                            }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- CUSTOMER --}}

            <div class="cd-card">

                <div class="cd-card-header">

                    <i class="fas fa-user"></i>

                    Customer

                </div>


                <div class="cd-profile">

                    <div class="cd-avatar">

                        {{ strtoupper(
                            substr(
                                $user?->name ?? 'U',
                                0,
                                1
                            )
                        ) }}

                    </div>


                    <div>

                        <div class="cd-profile-name">

                            {{ $user?->name ?? '—' }}

                        </div>

                        <div class="cd-profile-email">

                            {{ $user?->email ?? '—' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- VENDOR --}}

            <div class="cd-card">

                <div class="cd-card-header">

                    <i class="fas fa-store"></i>

                    Vendor

                </div>


                <div class="cd-profile">

                    <div
                        class="cd-avatar"
                        style="
                            background:
                                linear-gradient(
                                    135deg,
                                    #0ea5e9,
                                    #6366f1
                                );
                        "
                    >

                        {{ strtoupper(
                            substr(
                                $vendor?->business_name ?? 'V',
                                0,
                                1
                            )
                        ) }}

                    </div>


                    <div>

                        <div class="cd-profile-name">

                            {{ $vendor?->business_name ?? '—' }}

                        </div>

                        <div class="cd-profile-email">

                            {{ $vendor?->phone ?? 'No phone number' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- TOUR --}}

            <div class="cd-card">

                <div class="cd-card-header">

                    <i class="fas fa-map-marked-alt"></i>

                    Tour Information

                </div>


                <div class="cd-info-grid">


                    <div class="cd-info">

                        <div class="cd-label">

                            Tour

                        </div>

                        <div class="cd-value">

                            {{ $tour?->title ?? '—' }}

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Tour Date

                        </div>

                        <div class="cd-value">

                            @if($tourDate?->date)

                                {{ \Carbon\Carbon::parse($tourDate->date)->format('d M Y') }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Unit Price

                        </div>

                        <div class="cd-value">

                            ৳{{ number_format(
                                (float) ($booking?->unit_price ?? 0),
                                2
                            ) }}

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Subtotal

                        </div>

                        <div class="cd-value">

                            ৳{{ number_format(
                                (float) ($booking?->subtotal ?? 0),
                                2
                            ) }}

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Discount

                        </div>

                        <div class="cd-value">

                            ৳{{ number_format(
                                (float) ($booking?->discount ?? 0),
                                2
                            ) }}

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Final Amount

                        </div>

                        <div class="cd-value">

                            <strong style="color:#7dd3fc;">

                                ৳{{ number_format(
                                    (float) ($booking?->total_amount ?? 0),
                                    2
                                ) }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- SPECIAL REQUEST --}}

            @if($booking?->special_request)

                <div class="cd-card">

                    <div class="cd-card-header">

                        <i class="fas fa-comment-alt"></i>

                        Special Request

                    </div>

                    <div class="cd-request">

                        {{ $booking->special_request }}

                    </div>

                </div>

            @endif


        </div>


        {{-- =================================================
             RIGHT SIDE
        ================================================== --}}

        <div>


            {{-- COMMISSION SUMMARY --}}

            <div class="cd-card">

                <div class="cd-card-header">

                    <i class="fas fa-chart-pie"></i>

                    Commission Summary

                </div>


                <div class="cd-info-grid">


                    <div class="cd-info">

                        <div class="cd-label">

                            Booking Amount

                        </div>

                        <div class="cd-value">

                            ৳{{ number_format(
                                (float) $commission->total_amount,
                                2
                            ) }}

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Commission Rate

                        </div>

                        <div class="cd-value">

                            <span
                                style="
                                    color:#fcd34d;
                                    font-family:'JetBrains Mono',monospace;
                                    font-weight:700;
                                "
                            >

                                {{ number_format(
                                    (float) $commission->commission_rate,
                                    2
                                ) }}%

                            </span>

                        </div>

                    </div>

                </div>


                <div class="cd-earnings">


                    {{-- ADMIN --}}

                    <div class="cd-earning cd-earning-admin">

                        <div class="cd-earning-label">

                            Your Earning

                        </div>

                        <div class="cd-earning-value">

                            ৳{{ number_format(
                                (float) $commission->admin_earning,
                                2
                            ) }}

                        </div>

                    </div>


                    {{-- VENDOR --}}

                    <div class="cd-earning cd-earning-vendor">

                        <div class="cd-earning-label">

                            Vendor Earning

                        </div>

                        <div class="cd-earning-value">

                            ৳{{ number_format(
                                (float) $commission->vendor_earning,
                                2
                            ) }}

                        </div>

                    </div>


                </div>


                <div class="cd-rate-box">

                    <div class="cd-rate">

                        <div>

                            <div class="cd-rate-label">

                                Commission Rate

                            </div>

                            <div
                                style="
                                    color:var(--cd-muted);
                                    font-size:.7rem;
                                    margin-top:3px;
                                "
                            >

                                Platform commission from this booking

                            </div>

                        </div>


                        <div class="cd-rate-value">

                            {{ number_format(
                                (float) $commission->commission_rate,
                                2
                            ) }}%

                        </div>

                    </div>

                </div>

            </div>


            {{-- FINANCIAL BREAKDOWN --}}

            <div class="cd-card">

                <div class="cd-card-header">

                    <i class="fas fa-calculator"></i>

                    Financial Breakdown

                </div>


                <div class="cd-info-grid">


                    <div class="cd-info">

                        <div class="cd-label">

                            Total Amount

                        </div>

                        <div class="cd-value">

                            ৳{{ number_format(
                                (float) $commission->total_amount,
                                2
                            ) }}

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Admin Profit

                        </div>

                        <div
                            class="cd-value"
                            style="color:#86efac;"
                        >

                            + ৳{{ number_format(
                                (float) $commission->admin_earning,
                                2
                            ) }}

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Vendor Share

                        </div>

                        <div
                            class="cd-value"
                            style="color:#c4b5fd;"
                        >

                            ৳{{ number_format(
                                (float) $commission->vendor_earning,
                                2
                            ) }}

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Commission Created

                        </div>

                        <div class="cd-value">

                            {{ $commission->created_at?->format('d M Y, h:i A') ?? '—' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- TRANSACTION --}}

            <div class="cd-card">

                <div class="cd-card-header">

                    <i class="fas fa-money-check-alt"></i>

                    Transaction

                </div>


                <div class="cd-info-grid">


                    <div class="cd-info">

                        <div class="cd-label">

                            Transaction ID

                        </div>

                        <div class="cd-value mono">

                            {{ $transaction?->transaction_id ?? '—' }}

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Amount

                        </div>

                        <div class="cd-value">

                            ৳{{ number_format(
                                (float) ($transaction?->amount ?? 0),
                                2
                            ) }}

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Status

                        </div>

                        <div class="cd-value">

                            @if($transaction?->status === 'success')

                                <span class="cd-status cd-status-success">

                                    <i class="fas fa-check-circle"></i>

                                    Success

                                </span>

                            @elseif($transaction?->status === 'pending')

                                <span class="cd-status cd-status-pending">

                                    <i class="fas fa-clock"></i>

                                    Pending

                                </span>

                            @else

                                <span class="cd-status cd-status-danger">

                                    <i class="fas fa-times-circle"></i>

                                    {{ ucfirst($transaction?->status ?? 'Unknown') }}

                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="cd-info">

                        <div class="cd-label">

                            Paid At

                        </div>

                        <div class="cd-value">

                            {{ $transaction?->paid_at
                                ? $transaction->paid_at->format('d M Y, h:i A')
                                : '—'
                            }}

                        </div>

                    </div>

                </div>

            </div>


        </div>

    </div>

</div>

@endsection
