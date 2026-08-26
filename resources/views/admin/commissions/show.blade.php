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

    --cd-radius: 14px;
    --cd-shadow: 0 8px 32px rgba(0,0,0,.45);
}

.cd-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--cd-text);
}

.cd-header {
    background: linear-gradient(135deg, #0c1a2e 0%, #0e0c2e 55%, #0c1a2e 100%);
    border-radius: var(--cd-radius);
    padding: 26px 30px;
    margin-bottom: 22px;
    box-shadow: var(--cd-shadow);
}

.cd-header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    flex-wrap: wrap;
}

.cd-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: #fff;
}

.cd-subtitle {
    color: rgba(255,255,255,.45);
    font-size: .8rem;
    margin-top: 5px;
}

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
}

.cd-back:hover {
    background: rgba(255,255,255,.1);
    color: #fff;
}

.cd-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.4fr) minmax(300px, .8fr);
    gap: 20px;
    align-items: start;
}

.cd-card {
    background: var(--cd-surface);
    border: 1px solid var(--cd-border);
    border-radius: var(--cd-radius);
    box-shadow: var(--cd-shadow);
    overflow: hidden;
    margin-bottom: 20px;
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

.cd-info-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
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
    background: linear-gradient(135deg, var(--cd-indigo), var(--cd-purple));
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

.cd-earnings {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
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

.cd-request {
    padding: 18px 20px;
    color: #cbd5e1;
    font-size: .82rem;
    line-height: 1.7;
    white-space: pre-wrap;
}

@media (max-width: 950px) {
    .cd-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 600px) {
    .cd-info-grid,
    .cd-earnings {
        grid-template-columns: 1fr;
    }

    .cd-header {
        padding: 22px;
    }
}
</style>

@php
    /*
    |--------------------------------------------------------------------------
    | BOOKING TYPE & DATA
    |--------------------------------------------------------------------------
    */

    $isRoomBooking = !empty($commission->room_booking_id);

    $booking = $isRoomBooking
        ? $commission->roomBooking
        : $commission->booking;

    /*
    |--------------------------------------------------------------------------
    | COMMON DATA
    |--------------------------------------------------------------------------
    */

    $vendor = $booking?->vendor;
    $user = $booking?->user;

    /*
    |--------------------------------------------------------------------------
    | TOUR DATA
    |--------------------------------------------------------------------------
    */

    $tour = $commission->booking?->tour;
    $tourDate = $commission->booking?->tourDate;

    /*
    |--------------------------------------------------------------------------
    | PAYMENT / TRANSACTION
    |--------------------------------------------------------------------------
    */

    $transaction = !$isRoomBooking
        ? $commission->booking?->transaction
        : null;

    $payment = $isRoomBooking
        ? $commission->roomBooking?->latestPayment
        : null;

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    $bookingStatus = $booking?->booking_status ?? 'pending';

    $paymentStatus = $booking?->payment_status
        ?? ($transaction?->status ?? $payment?->status ?? 'pending');

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER INITIAL
    |--------------------------------------------------------------------------
    */

    $customerInitial = strtoupper(
        substr($user?->name ?? 'U', 0, 1)
    );

    $vendorInitial = strtoupper(
        substr($vendor?->business_name ?? 'V', 0, 1)
    );
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
                    {{ $isRoomBooking ? 'Room Booking' : 'Tour Booking' }}
                    · Complete financial breakdown
                </div>
            </div>

            <a href="{{ route('admin.commissions.index') }}" class="cd-back">
                <i class="fas fa-arrow-left"></i>
                Back to Commissions
            </a>

        </div>
    </div>


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
                        <div class="cd-label">Booking Code</div>
                        <div class="cd-value mono">
                            {{ $booking?->booking_code ?? '—' }}
                        </div>
                    </div>

                    <div class="cd-info">
                        <div class="cd-label">Booking Date</div>
                        <div class="cd-value">
                            {{ $booking?->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </div>
                    </div>

                    <div class="cd-info">
                        <div class="cd-label">Booking Status</div>
                        <div class="cd-value">

                            @if(in_array($bookingStatus, ['confirmed', 'completed']))
                                <span class="cd-status cd-status-success">
                                    <i class="fas fa-check-circle"></i>
                                    {{ ucfirst($bookingStatus) }}
                                </span>
                            @elseif($bookingStatus === 'cancelled')
                                <span class="cd-status cd-status-danger">
                                    <i class="fas fa-times-circle"></i>
                                    Cancelled
                                </span>
                            @else
                                <span class="cd-status cd-status-pending">
                                    <i class="fas fa-clock"></i>
                                    {{ ucfirst($bookingStatus) }}
                                </span>
                            @endif

                        </div>
                    </div>

                    <div class="cd-info">
                        <div class="cd-label">Payment Status</div>
                        <div class="cd-value">

                            @if(in_array($paymentStatus, ['paid', 'success', 'completed']))
                                <span class="cd-status cd-status-success">
                                    <i class="fas fa-check-circle"></i>
                                    Paid
                                </span>
                            @elseif(in_array($paymentStatus, ['failed', 'cancelled']))
                                <span class="cd-status cd-status-danger">
                                    <i class="fas fa-times-circle"></i>
                                    {{ ucfirst($paymentStatus) }}
                                </span>
                            @else
                                <span class="cd-status cd-status-pending">
                                    <i class="fas fa-clock"></i>
                                    {{ ucfirst($paymentStatus) }}
                                </span>
                            @endif

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
                        {{ $customerInitial }}
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

                    <div class="cd-avatar">
                        {{ $vendorInitial }}
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


            {{-- =================================================
                 ROOM BOOKING INFORMATION
            ================================================== --}}
            @if($isRoomBooking)

                @php
                    $roomBooking = $commission->roomBooking;
                    $room = $roomBooking?->room;
                    $resort = $roomBooking?->resort;
                @endphp

                <div class="cd-card">

                    <div class="cd-card-header">
                        <i class="fas fa-hotel"></i>
                        Room & Stay Information
                    </div>

                    <div class="cd-info-grid">

                        <div class="cd-info">
                            <div class="cd-label">Resort / Hotel</div>
                            <div class="cd-value">
                                {{ $resort?->name ?? $resort?->title ?? '—' }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Room</div>
                            <div class="cd-value">
                                {{ $room?->name ?? $room?->title ?? '—' }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Check In</div>
                            <div class="cd-value">
                                {{ $roomBooking?->check_in?->format('d M Y') ?? '—' }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Check Out</div>
                            <div class="cd-value">
                                {{ $roomBooking?->check_out?->format('d M Y') ?? '—' }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Total Nights</div>
                            <div class="cd-value">
                                {{ $roomBooking?->total_nights ?? 0 }}
                                {{ ($roomBooking?->total_nights ?? 0) == 1 ? 'Night' : 'Nights' }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Room Count</div>
                            <div class="cd-value">
                                {{ $roomBooking?->room_count ?? 1 }}
                                {{ ($roomBooking?->room_count ?? 1) == 1 ? 'Room' : 'Rooms' }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Adults</div>
                            <div class="cd-value">
                                {{ $roomBooking?->adults ?? 0 }} Adults
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Children</div>
                            <div class="cd-value">
                                {{ $roomBooking?->children ?? 0 }} Children
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Room Price</div>
                            <div class="cd-value">
                                ৳{{ number_format((float) ($roomBooking?->room_price ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Subtotal</div>
                            <div class="cd-value">
                                ৳{{ number_format((float) ($roomBooking?->subtotal ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Discount</div>
                            <div class="cd-value">
                                ৳{{ number_format((float) ($roomBooking?->discount ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Tax</div>
                            <div class="cd-value">
                                ৳{{ number_format((float) ($roomBooking?->tax ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Final Amount</div>
                            <div class="cd-value">
                                <strong style="color:#7dd3fc;">
                                    ৳{{ number_format((float) ($roomBooking?->total_amount ?? 0), 2) }}
                                </strong>
                            </div>
                        </div>

                    </div>
                </div>

            {{-- =================================================
                 TOUR BOOKING INFORMATION
            ================================================== --}}
            @else

                <div class="cd-card">

                    <div class="cd-card-header">
                        <i class="fas fa-map-marked-alt"></i>
                        Tour Information
                    </div>

                    <div class="cd-info-grid">

                        <div class="cd-info">
                            <div class="cd-label">Tour</div>
                            <div class="cd-value">
                                {{ $tour?->title ?? '—' }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Tour Date</div>
                            <div class="cd-value">
                                {{ $tourDate?->date
                                    ? \Carbon\Carbon::parse($tourDate->date)->format('d M Y')
                                    : '—'
                                }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Person Count</div>
                            <div class="cd-value">
                                {{ $booking?->person_count ?? 0 }}
                                {{ ($booking?->person_count ?? 0) == 1 ? 'Person' : 'People' }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Unit Price</div>
                            <div class="cd-value">
                                ৳{{ number_format((float) ($booking?->unit_price ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Subtotal</div>
                            <div class="cd-value">
                                ৳{{ number_format((float) ($booking?->subtotal ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Discount</div>
                            <div class="cd-value">
                                ৳{{ number_format((float) ($booking?->discount ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Final Amount</div>
                            <div class="cd-value">
                                <strong style="color:#7dd3fc;">
                                    ৳{{ number_format((float) ($booking?->total_amount ?? 0), 2) }}
                                </strong>
                            </div>
                        </div>

                    </div>
                </div>

            @endif


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
                        <div class="cd-label">Booking Amount</div>
                        <div class="cd-value">
                            ৳{{ number_format((float) $commission->total_amount, 2) }}
                        </div>
                    </div>

                    <div class="cd-info">
                        <div class="cd-label">Commission Rate</div>
                        <div class="cd-value" style="color:#fcd34d;">
                            {{ number_format((float) $commission->commission_rate, 2) }}%
                        </div>
                    </div>

                </div>

                <div class="cd-earnings">

                    <div class="cd-earning cd-earning-admin">
                        <div class="cd-earning-label">
                            Admin Earning
                        </div>

                        <div class="cd-earning-value">
                            ৳{{ number_format((float) $commission->admin_earning, 2) }}
                        </div>
                    </div>

                    <div class="cd-earning cd-earning-vendor">
                        <div class="cd-earning-label">
                            Vendor Earning
                        </div>

                        <div class="cd-earning-value">
                            ৳{{ number_format((float) $commission->vendor_earning, 2) }}
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
                        <div class="cd-label">Total Amount</div>
                        <div class="cd-value">
                            ৳{{ number_format((float) $commission->total_amount, 2) }}
                        </div>
                    </div>

                    <div class="cd-info">
                        <div class="cd-label">Admin Profit</div>
                        <div class="cd-value" style="color:#86efac;">
                            + ৳{{ number_format((float) $commission->admin_earning, 2) }}
                        </div>
                    </div>

                    <div class="cd-info">
                        <div class="cd-label">Vendor Share</div>
                        <div class="cd-value" style="color:#c4b5fd;">
                            ৳{{ number_format((float) $commission->vendor_earning, 2) }}
                        </div>
                    </div>

                    <div class="cd-info">
                        <div class="cd-label">Commission Created</div>
                        <div class="cd-value">
                            {{ $commission->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </div>
                    </div>

                </div>
            </div>


            {{-- PAYMENT INFORMATION --}}
            <div class="cd-card">

                <div class="cd-card-header">
                    <i class="fas fa-money-check-alt"></i>
                    Payment Information
                </div>

                <div class="cd-info-grid">

                    @if($isRoomBooking)

                        <div class="cd-info">
                            <div class="cd-label">Payment Status</div>
                            <div class="cd-value">
                                {{ ucfirst($paymentStatus) }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Payment Amount</div>
                            <div class="cd-value">
                                ৳{{ number_format((float) ($payment?->amount ?? $booking?->total_amount ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Payment Method</div>
                            <div class="cd-value">
                                {{ $payment?->payment_method
                                    ? ucfirst(str_replace('_', ' ', $payment->payment_method))
                                    : '—'
                                }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Paid At</div>
                            <div class="cd-value">
                                {{ $payment?->created_at?->format('d M Y, h:i A') ?? '—' }}
                            </div>
                        </div>

                    @else

                        <div class="cd-info">
                            <div class="cd-label">Transaction ID</div>
                            <div class="cd-value mono">
                                {{ $transaction?->transaction_id ?? '—' }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Amount</div>
                            <div class="cd-value">
                                ৳{{ number_format((float) ($transaction?->amount ?? 0), 2) }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Status</div>
                            <div class="cd-value">
                                {{ ucfirst($transaction?->status ?? $paymentStatus) }}
                            </div>
                        </div>

                        <div class="cd-info">
                            <div class="cd-label">Paid At</div>
                            <div class="cd-value">
                                {{ $transaction?->paid_at
                                    ? $transaction->paid_at->format('d M Y, h:i A')
                                    : '—'
                                }}
                            </div>
                        </div>

                    @endif

                </div>
            </div>

        </div>

    </div>

</div>

@endsection