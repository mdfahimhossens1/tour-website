@extends('layouts.vendor')

@section('title', 'Transport Booking Details')

@section('page')

@php
    $pendingPayment = $booking->payments->where('status', 'pending')->sortByDesc('id')->first();
@endphp

<style>

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
    --rs-surface: #1a1d27; --rs-surface2: #222636; --rs-border: rgba(255,255,255,.07);
    --rs-text: #e2e8f0; --rs-muted: #64748b;
    --rs-indigo: #6366f1; --rs-purple: #8b5cf6;
    --rs-success: #22c55e; --rs-warning: #f59e0b; --rs-danger: #ef4444; --rs-secondary: #94a3b8;
    --rs-radius: 14px; --rs-shadow: 0 8px 32px rgba(0,0,0,.45);
}

.rs-wrap { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--rs-text); }

.rs-header {
    background: linear-gradient(135deg, #0c1a2e 0%, #0e0c2e 55%, #0c1a2e 100%);
    border-radius: var(--rs-radius); padding: 26px 28px; margin-bottom: 22px;
    box-shadow: var(--rs-shadow); position: relative; overflow: hidden;
}
.rs-header::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}
.rs-header-content { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.rs-title-row { display: flex; align-items: center; gap: 10px; }
.rs-title { font-size: 1.35rem; font-weight: 700; background: linear-gradient(90deg, #fff, #a5b4fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.rs-subtitle { color: rgba(255,255,255,.45); font-size: .82rem; margin-top: 5px; }

.rs-btn-back {
    width: 34px; height: 34px; border-radius: 9px; border: 1px solid rgba(255,255,255,.15);
    background: rgba(255,255,255,.05); color: #e2e8f0; display: inline-flex; align-items: center; justify-content: center;
    text-decoration: none;
}
.rs-btn-back:hover { background: rgba(255,255,255,.1); color: #fff; }

.rs-actions-row { display: flex; flex-wrap: wrap; gap: 8px; }

.rs-btn { display: inline-flex; align-items: center; gap: 7px; border-radius: 9px; padding: 9px 15px; font-size: .8rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; white-space: nowrap; }
.rs-btn-success { background: rgba(34,197,94,.15); color: #86efac; border: 1px solid rgba(34,197,94,.3); }
.rs-btn-success:hover { background: rgba(34,197,94,.25); color: #bbf7d0; }
.rs-btn-danger-outline { background: rgba(239,68,68,.1); color: #fca5a5; border: 1px solid rgba(239,68,68,.28); }
.rs-btn-danger-outline:hover { background: rgba(239,68,68,.2); color: #fecaca; }
.rs-btn-warning { background: rgba(245,158,11,.15); color: #fcd34d; border: 1px solid rgba(245,158,11,.3); }
.rs-btn-warning:hover { background: rgba(245,158,11,.25); color: #fde68a; }
.rs-btn-primary { background: linear-gradient(135deg, var(--rs-indigo), var(--rs-purple)); color: #fff; box-shadow: 0 6px 18px rgba(99,102,241,.3); }
.rs-btn-primary:hover { color: #fff; }
.rs-btn-ghost { background: rgba(255,255,255,.04); color: #e2e8f0; border: 1px solid rgba(255,255,255,.12); }
.rs-btn-ghost:hover { background: rgba(255,255,255,.09); color: #fff; }
.rs-btn-block { width: 100%; justify-content: center; }

.rs-wrap .alert { background: var(--rs-surface); border: 1px solid var(--rs-border); color: var(--rs-text); border-radius: 12px; font-size: .84rem; box-shadow: var(--rs-shadow); }
.rs-wrap .alert-success { border-left: 3px solid var(--rs-success); }
.rs-wrap .alert-danger { border-left: 3px solid var(--rs-danger); }
.rs-wrap .btn-close { filter: invert(1) grayscale(1) opacity(.6); }
.rs-wrap .alert ul { padding-left: 18px; margin: 6px 0 0; }

/* STATUS CARDS */
.rs-status-cards { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 22px; }
.rs-status-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); padding: 18px 20px; box-shadow: var(--rs-shadow); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.rs-status-card small { display: block; color: var(--rs-muted); font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 8px; }
.rs-status-card i.rs-status-icon { font-size: 1.5rem; color: var(--rs-muted); opacity: .6; }
.rs-status-value { font-size: 1.3rem; font-weight: 700; font-family: 'JetBrains Mono', monospace; }

.rs-badge { display: inline-flex; align-items: center; gap: 5px; padding: 6px 11px; border-radius: 8px; font-size: .74rem; font-weight: 700; }
.rs-badge-success   { background: rgba(34,197,94,.12);  color: #86efac; border: 1px solid rgba(34,197,94,.22); }
.rs-badge-primary   { background: rgba(99,102,241,.12); color: #a5b4fc; border: 1px solid rgba(99,102,241,.22); }
.rs-badge-danger    { background: rgba(239,68,68,.12);  color: #fca5a5; border: 1px solid rgba(239,68,68,.22); }
.rs-badge-warning   { background: rgba(245,158,11,.12); color: #fcd34d; border: 1px solid rgba(245,158,11,.22); }
.rs-badge-secondary { background: rgba(148,163,184,.12); color: #cbd5e1; border: 1px solid rgba(148,163,184,.22); }

/* LAYOUT */
.rs-detail-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start; }
@media (max-width: 991.98px) { .rs-detail-grid { grid-template-columns: 1fr; } }

/* CARD */
.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; margin-bottom: 20px; }
.rs-card-head { padding: 16px 22px; border-bottom: 1px solid var(--rs-border); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.rs-card-head h2 { font-size: .9rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px; color: var(--rs-text); }
.rs-card-head h2 i { color: #a5b4fc; }
.rs-card-body { padding: 22px; }

/* FIELD GRID */
.rs-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
@media (max-width: 575.98px) { .rs-info-grid { grid-template-columns: 1fr; } }
.rs-info-item small { display: block; color: var(--rs-muted); font-size: .7rem; margin-bottom: 4px; }
.rs-info-item div.val { font-weight: 600; font-size: .85rem; color: var(--rs-text); }

/* JOURNEY BOXES */
.rs-journey-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media (max-width: 575.98px) { .rs-journey-grid { grid-template-columns: 1fr; } }
.rs-journey-box { background: var(--rs-surface2); border: 1px solid var(--rs-border); border-radius: 12px; padding: 14px 16px; }
.rs-journey-tag { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 7px; font-size: .75rem; margin-right: 8px; }
.rs-journey-tag.pickup { background: rgba(34,197,94,.15); color: #86efac; }
.rs-journey-tag.dropoff { background: rgba(239,68,68,.15); color: #fca5a5; }
.rs-journey-label { font-weight: 700; font-size: .84rem; }
.rs-journey-value { color: var(--rs-muted); font-size: .8rem; margin-top: 8px; }
.rs-note-box { background: var(--rs-surface2); border: 1px solid var(--rs-border); border-radius: 12px; padding: 14px 16px; margin-top: 16px; }

/* PAYMENT TABLE */
.rs-pay-table { width: 100%; border-collapse: collapse; }
.rs-pay-table thead tr { background: var(--rs-surface2); }
.rs-pay-table th { padding: 10px 14px; text-align: left; color: var(--rs-muted); font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
.rs-pay-table td { padding: 12px 14px; border-bottom: 1px solid var(--rs-border); font-size: .78rem; vertical-align: middle; }
.rs-pay-table tr:last-child td { border-bottom: none; }
.rs-pay-actions { display: flex; gap: 6px; justify-content: flex-end; }
.rs-pay-btn { border: none; border-radius: 7px; padding: 6px 11px; font-size: .7rem; font-weight: 600; cursor: pointer; }
.rs-pay-btn-approve { background: rgba(34,197,94,.15); color: #86efac; }
.rs-pay-btn-reject { background: rgba(239,68,68,.15); color: #fca5a5; }

.rs-empty-mini { text-align: center; padding: 40px 20px; color: var(--rs-muted); }
.rs-empty-mini i { font-size: 1.8rem; opacity: .3; margin-bottom: 10px; display: block; }

/* SIDEBAR */
.rs-side-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; margin-bottom: 20px; }
.rs-side-head { padding: 14px 18px; border-bottom: 1px solid var(--rs-border); font-size: .85rem; font-weight: 700; display: flex; align-items: center; gap: 8px; }
.rs-side-head.is-warning { background: rgba(245,158,11,.08); }
.rs-side-head.is-success { background: rgba(34,197,94,.08); }
.rs-side-body { padding: 18px; }

.rs-price-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: .82rem; }
.rs-price-row span:first-child { color: var(--rs-muted); }
.rs-price-row.total { border-top: 1px solid var(--rs-border); margin-top: 6px; padding-top: 12px; font-weight: 700; font-size: .95rem; }
.rs-price-row .neg { color: #fca5a5; }
.rs-price-row .pos { color: #86efac; }

.rs-summary-box { background: var(--rs-surface2); border: 1px solid var(--rs-border); border-radius: 10px; padding: 12px 14px; margin-bottom: 14px; }

</style>


<div class="rs-wrap">


    {{-- HEADER --}}

    <div class="rs-header">

        <div class="rs-header-content">

            <div>
                <div class="rs-title-row">
                    <a href="{{ route('vendor.transport-bookings.index') }}" class="rs-btn-back"><i class="bi bi-arrow-left"></i></a>
                    <div class="rs-title"><i class="bi bi-car-front me-2"></i> Transport Booking Details</div>
                </div>
                <div class="rs-subtitle">Booking #{{ $booking->booking_code }}</div>
            </div>

            <div class="rs-actions-row">

                @if(!in_array($booking->booking_status, ['completed', 'cancelled'], true))
                    <a href="{{ route('vendor.transport-bookings.edit', $booking) }}" class="rs-btn rs-btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                @endif

                @if($booking->payment_status === 'pending' && $pendingPayment)

                    <form action="{{ route('vendor.transport-bookings.payments.approve', ['booking' => $booking->id, 'payment' => $pendingPayment->id]) }}"
                          method="POST" onsubmit="return confirm('Are you sure you want to approve this payment?')">
                        @csrf
                        <button type="submit" class="rs-btn rs-btn-success"><i class="bi bi-check-circle"></i> Approve Payment</button>
                    </form>

                    <form action="{{ route('vendor.transport-bookings.payments.reject', ['booking' => $booking->id, 'payment' => $pendingPayment->id]) }}"
                          method="POST" onsubmit="return confirm('Are you sure you want to reject this payment?')">
                        @csrf
                        <button type="submit" class="rs-btn rs-btn-danger-outline"><i class="bi bi-x-circle"></i> Reject Payment</button>
                    </form>

                @endif

                @if($booking->booking_status === 'pending' && $booking->payment_status === 'paid')
                    <form action="{{ route('vendor.transport-bookings.confirm', $booking) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to confirm this booking?')">
                        @csrf
                        <button type="submit" class="rs-btn rs-btn-primary"><i class="bi bi-calendar-check"></i> Confirm Booking</button>
                    </form>
                @endif

                @if(!in_array($booking->booking_status, ['completed', 'cancelled'], true))
                    <form action="{{ route('vendor.transport-bookings.cancel', $booking) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                        @csrf
                        <button type="submit" class="rs-btn rs-btn-danger-outline"><i class="bi bi-x-lg"></i> Cancel</button>
                    </form>
                @endif

            </div>

        </div>

    </div>


    {{-- MESSAGES --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- STATUS CARDS --}}

    <div class="rs-status-cards">

        <div class="rs-status-card">
            <div>
                <small>Booking Status</small>
                @php
                    $statusClass = match($booking->booking_status) {
                        'confirmed' => 'rs-badge-success',
                        'completed' => 'rs-badge-primary',
                        'cancelled' => 'rs-badge-danger',
                        default => 'rs-badge-warning',
                    };
                @endphp
                <span class="rs-badge {{ $statusClass }}">{{ ucfirst($booking->booking_status) }}</span>
            </div>
            <i class="bi bi-calendar-check rs-status-icon"></i>
        </div>

        <div class="rs-status-card">
            <div>
                <small>Payment Status</small>
                @php
                    $paymentClass = match($booking->payment_status) {
                        'paid' => 'rs-badge-success',
                        'failed' => 'rs-badge-danger',
                        'refunded' => 'rs-badge-secondary',
                        default => 'rs-badge-warning',
                    };
                @endphp
                <span class="rs-badge {{ $paymentClass }}">{{ ucfirst($booking->payment_status) }}</span>
            </div>
            <i class="bi bi-credit-card rs-status-icon"></i>
        </div>

        <div class="rs-status-card">
            <div>
                <small>Total Amount</small>
                <div class="rs-status-value">{{ number_format((float) $booking->total_amount, 2) }}</div>
            </div>
            <i class="bi bi-cash-stack rs-status-icon"></i>
        </div>

    </div>


    {{-- MAIN CONTENT --}}

    <div class="rs-detail-grid">

        {{-- LEFT COLUMN --}}

        <div>

            {{-- BOOKING INFORMATION --}}
            <div class="rs-card">
                <div class="rs-card-head"><h2><i class="bi bi-file-text"></i> Booking Information</h2></div>
                <div class="rs-card-body">
                    <div class="rs-info-grid">
                        <div class="rs-info-item"><small>Booking Code</small><div class="val">{{ $booking->booking_code }}</div></div>
                        <div class="rs-info-item"><small>Booking Date</small><div class="val">{{ optional($booking->created_at)->format('d M Y, h:i A') }}</div></div>
                        <div class="rs-info-item"><small>Start Date</small><div class="val">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</div></div>
                        <div class="rs-info-item"><small>End Date</small><div class="val">{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</div></div>
                        <div class="rs-info-item"><small>Total Days</small><div class="val">{{ $booking->total_days }} {{ $booking->total_days == 1 ? 'Day' : 'Days' }}</div></div>
                        <div class="rs-info-item"><small>Passengers</small><div class="val"><i class="bi bi-people me-1" style="color:var(--rs-muted);"></i>{{ $booking->passengers }}</div></div>
                    </div>
                </div>
            </div>


            {{-- JOURNEY INFORMATION --}}
            <div class="rs-card">
                <div class="rs-card-head"><h2><i class="bi bi-signpost-2"></i> Journey Information</h2></div>
                <div class="rs-card-body">

                    <div class="rs-journey-grid">

                        <div class="rs-journey-box">
                            <span class="rs-journey-tag pickup"><i class="bi bi-geo-alt-fill"></i></span>
                            <span class="rs-journey-label">Pickup Location</span>
                            <div class="rs-journey-value">{{ $booking->pickup_location ?: 'Not specified' }}</div>
                        </div>

                        <div class="rs-journey-box">
                            <span class="rs-journey-tag dropoff"><i class="bi bi-geo-alt-fill"></i></span>
                            <span class="rs-journey-label">Dropoff Location</span>
                            <div class="rs-journey-value">{{ $booking->dropoff_location ?: 'Not specified' }}</div>
                        </div>

                    </div>

                    @if($booking->special_request)
                        <div class="rs-note-box">
                            <strong style="display:block;margin-bottom:6px;font-size:.84rem;"><i class="bi bi-chat-left-text me-2" style="color:#a5b4fc;"></i>Special Request</strong>
                            <div style="color:var(--rs-muted);font-size:.8rem;">{{ $booking->special_request }}</div>
                        </div>
                    @endif

                </div>
            </div>


            {{-- VEHICLE INFORMATION --}}
            <div class="rs-card">
                <div class="rs-card-head"><h2><i class="bi bi-car-front"></i> Vehicle Information</h2></div>
                <div class="rs-card-body">

                    @if($booking->vehicle)
                        <div class="rs-info-grid">
                            <div class="rs-info-item"><small>Vehicle Name</small><div class="val">{{ $booking->vehicle->name }}</div></div>
                            <div class="rs-info-item"><small>Registration Number</small><div class="val">{{ $booking->vehicle->registration_number }}</div></div>
                            @if($booking->vehicle->brand)
                                <div class="rs-info-item"><small>Brand</small><div class="val">{{ $booking->vehicle->brand }}</div></div>
                            @endif
                            @if($booking->vehicle->model)
                                <div class="rs-info-item"><small>Model</small><div class="val">{{ $booking->vehicle->model }}</div></div>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-warning mb-0" style="border-left:3px solid var(--rs-warning);">
                            <i class="bi bi-exclamation-triangle me-2"></i> Vehicle information is unavailable.
                        </div>
                    @endif

                </div>
            </div>


            {{-- CUSTOMER INFORMATION --}}
            <div class="rs-card">
                <div class="rs-card-head"><h2><i class="bi bi-person"></i> Customer Information</h2></div>
                <div class="rs-card-body">

                    @if($booking->user)
                        <div class="rs-info-grid">
                            <div class="rs-info-item"><small>Name</small><div class="val">{{ $booking->user->name }}</div></div>
                            @if($booking->user->email)
                                <div class="rs-info-item"><small>Email</small><div class="val">{{ $booking->user->email }}</div></div>
                            @endif
                            @if($booking->user->phone)
                                <div class="rs-info-item"><small>Phone</small><div class="val">{{ $booking->user->phone }}</div></div>
                            @endif
                        </div>
                    @else
                        <div style="color:var(--rs-muted);font-size:.82rem;">Customer information is unavailable.</div>
                    @endif

                </div>
            </div>


            {{-- PAYMENT INFORMATION --}}
            <div class="rs-card">
                <div class="rs-card-head">
                    <h2><i class="bi bi-credit-card"></i> Payment Information</h2>
                    @php
                        $bookingPaymentClass = match($booking->payment_status) {
                            'paid' => 'rs-badge-success',
                            'failed' => 'rs-badge-danger',
                            'refunded' => 'rs-badge-secondary',
                            default => 'rs-badge-warning',
                        };
                    @endphp
                    <span class="rs-badge {{ $bookingPaymentClass }}">{{ ucfirst($booking->payment_status) }}</span>
                </div>

                <div class="rs-card-body" style="padding:0;">

                    @if($booking->payments && $booking->payments->count())

                        <div class="table-responsive">
                            <table class="rs-pay-table">
                                <thead>
                                    <tr>
                                        <th class="ps-3">#</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th class="text-end pe-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($booking->payments as $payment)

                                        <tr>
                                            <td class="ps-3">{{ $loop->iteration }}</td>
                                            <td class="fw-semibold">{{ number_format((float) $payment->amount, 2) }}</td>
                                            <td>{{ $payment->method ?? $payment->payment_method ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $paymentStatusClass = match($payment->status) {
                                                        'paid' => 'rs-badge-success',
                                                        'failed' => 'rs-badge-danger',
                                                        'refunded' => 'rs-badge-secondary',
                                                        default => 'rs-badge-warning',
                                                    };
                                                @endphp
                                                <span class="rs-badge {{ $paymentStatusClass }}">{{ ucfirst($payment->status) }}</span>
                                            </td>
                                            <td style="color:var(--rs-muted);">{{ optional($payment->created_at)->format('d M Y, h:i A') }}</td>
                                            <td class="text-end pe-3">

                                                @if($payment->status === 'pending')

                                                    <div class="rs-pay-actions">

                                                        <form action="{{ route('vendor.transport-bookings.payments.approve', ['booking' => $booking->id, 'payment' => $payment->id]) }}"
                                                              method="POST" onsubmit="return confirm('Are you sure you want to approve this payment?')">
                                                            @csrf
                                                            <button type="submit" class="rs-pay-btn rs-pay-btn-approve"><i class="bi bi-check"></i> Approve</button>
                                                        </form>

                                                        <form action="{{ route('vendor.transport-bookings.payments.reject', ['booking' => $booking->id, 'payment' => $payment->id]) }}"
                                                              method="POST" onsubmit="return confirm('Are you sure you want to reject this payment?')">
                                                            @csrf
                                                            <button type="submit" class="rs-pay-btn rs-pay-btn-reject"><i class="bi bi-x"></i> Reject</button>
                                                        </form>

                                                    </div>

                                                @elseif($payment->status === 'paid')
                                                    <span class="rs-badge rs-badge-success"><i class="bi bi-check-circle"></i> Approved</span>
                                                @elseif($payment->status === 'failed')
                                                    <span class="rs-badge rs-badge-danger"><i class="bi bi-x-circle"></i> Rejected</span>
                                                @else
                                                    <span style="color:var(--rs-muted);font-size:.72rem;">No action</span>
                                                @endif

                                            </td>
                                        </tr>

                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                    @else

                        <div class="rs-empty-mini">
                            <i class="bi bi-credit-card"></i>
                            <p class="mb-0">No payment records found.</p>
                        </div>

                    @endif

                </div>
            </div>

        </div>


        {{-- RIGHT COLUMN --}}

        <div>

            {{-- PAYMENT APPROVAL --}}
            @if($booking->payment_status === 'pending' && $pendingPayment)

                <div class="rs-side-card">
                    <div class="rs-side-head is-warning"><i class="bi bi-credit-card"></i> Payment Approval Required</div>
                    <div class="rs-side-body">

                        <p style="color:var(--rs-muted);font-size:.8rem;">A payment is waiting for vendor approval.</p>

                        <div class="rs-summary-box">
                            <div class="rs-price-row"><span>Payment Amount</span><span class="fw-semibold">{{ number_format((float) $pendingPayment->amount, 2) }}</span></div>
                            <div class="rs-price-row"><span>Payment Method</span><span class="fw-semibold">{{ $pendingPayment->method ?? $pendingPayment->payment_method ?? 'N/A' }}</span></div>
                        </div>

                        <form action="{{ route('vendor.transport-bookings.payments.approve', ['booking' => $booking->id, 'payment' => $pendingPayment->id]) }}"
                              method="POST" onsubmit="return confirm('Are you sure you want to approve this payment?')" class="mb-2">
                            @csrf
                            <button type="submit" class="rs-btn rs-btn-success rs-btn-block"><i class="bi bi-check-circle"></i> Approve Payment</button>
                        </form>

                        <form action="{{ route('vendor.transport-bookings.payments.reject', ['booking' => $booking->id, 'payment' => $pendingPayment->id]) }}"
                              method="POST" onsubmit="return confirm('Are you sure you want to reject this payment?')">
                            @csrf
                            <button type="submit" class="rs-btn rs-btn-danger-outline rs-btn-block"><i class="bi bi-x-circle"></i> Reject Payment</button>
                        </form>

                    </div>
                </div>

            @endif


            {{-- CONFIRM BOOKING --}}
            @if($booking->booking_status === 'pending' && $booking->payment_status === 'paid')

                <div class="rs-side-card">
                    <div class="rs-side-head is-success"><i class="bi bi-calendar-check"></i> Booking Ready</div>
                    <div class="rs-side-body">

                        <div class="alert alert-success" style="border-left:3px solid var(--rs-success);margin-bottom:14px;">
                            <i class="bi bi-check-circle me-2"></i> Payment has been approved. This booking is ready for confirmation.
                        </div>

                        <form action="{{ route('vendor.transport-bookings.confirm', $booking) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to confirm this booking?')">
                            @csrf
                            <button type="submit" class="rs-btn rs-btn-primary rs-btn-block"><i class="bi bi-calendar-check"></i> Confirm Booking</button>
                        </form>

                    </div>
                </div>

            @endif


            {{-- PRICE BREAKDOWN --}}
            <div class="rs-side-card">
                <div class="rs-side-head"><i class="bi bi-calculator" style="color:#a5b4fc;"></i> Price Breakdown</div>
                <div class="rs-side-body">
                    <div class="rs-price-row"><span>Price / Day</span><span class="fw-semibold">{{ number_format((float) $booking->price_per_day, 2) }}</span></div>
                    <div class="rs-price-row"><span>Total Days</span><span class="fw-semibold">{{ $booking->total_days }}</span></div>
                    <div class="rs-price-row"><span>Subtotal</span><span class="fw-semibold">{{ number_format((float) $booking->subtotal, 2) }}</span></div>
                    <div class="rs-price-row"><span>Discount</span><span class="fw-semibold neg">- {{ number_format((float) ($booking->discount ?? 0), 2) }}</span></div>
                    <div class="rs-price-row"><span>Tax</span><span class="fw-semibold">{{ number_format((float) ($booking->tax ?? 0), 2) }}</span></div>
                    <div class="rs-price-row total"><span>Total</span><span>{{ number_format((float) $booking->total_amount, 2) }}</span></div>
                </div>
            </div>


            {{-- COMMISSION --}}
            <div class="rs-side-card">
                <div class="rs-side-head"><i class="bi bi-pie-chart" style="color:#a5b4fc;"></i> Commission</div>
                <div class="rs-side-body">
                    <div class="rs-price-row"><span>Commission Rate</span><span class="fw-semibold">{{ number_format((float) ($booking->commission_rate ?? 0), 2) }}%</span></div>
                    <div class="rs-price-row"><span>Admin Earning</span><span class="fw-semibold" style="color:#a5b4fc;">{{ number_format((float) ($booking->admin_commission ?? 0), 2) }}</span></div>
                    <div class="rs-price-row"><span>Vendor Earning</span><span class="fw-semibold pos">{{ number_format((float) ($booking->vendor_earning ?? 0), 2) }}</span></div>
                </div>
            </div>


            {{-- VENDOR INFORMATION --}}
            <div class="rs-side-card">
                <div class="rs-side-head"><i class="bi bi-shop" style="color:#a5b4fc;"></i> Vendor Information</div>
                <div class="rs-side-body">

                    @if($booking->vendor)
                        <div class="rs-info-item mb-3"><small>Vendor</small><div class="val">{{ $booking->vendor->name ?? $booking->vendor->business_name ?? 'N/A' }}</div></div>
                        @if($booking->vendor->email)
                            <div class="rs-info-item mb-3"><small>Email</small><div class="val" style="font-weight:400;">{{ $booking->vendor->email }}</div></div>
                        @endif
                        @if($booking->vendor->phone)
                            <div class="rs-info-item"><small>Phone</small><div class="val" style="font-weight:400;">{{ $booking->vendor->phone }}</div></div>
                        @endif
                    @else
                        <div style="color:var(--rs-muted);font-size:.82rem;">Vendor information unavailable.</div>
                    @endif

                </div>
            </div>


            {{-- QUICK ACTIONS --}}
            <div class="rs-side-card">
                <div class="rs-side-head"><i class="bi bi-lightning-charge" style="color:#a5b4fc;"></i> Quick Actions</div>
                <div class="rs-side-body d-grid gap-2">

                    @if($booking->payment_status === 'pending' && $pendingPayment)

                        <form action="{{ route('vendor.transport-bookings.payments.approve', ['booking' => $booking->id, 'payment' => $pendingPayment->id]) }}"
                              method="POST" onsubmit="return confirm('Approve this payment?')">
                            @csrf
                            <button type="submit" class="rs-btn rs-btn-success rs-btn-block"><i class="bi bi-check-circle"></i> Approve Payment</button>
                        </form>

                        <form action="{{ route('vendor.transport-bookings.payments.reject', ['booking' => $booking->id, 'payment' => $pendingPayment->id]) }}"
                              method="POST" onsubmit="return confirm('Reject this payment?')">
                            @csrf
                            <button type="submit" class="rs-btn rs-btn-danger-outline rs-btn-block"><i class="bi bi-x-circle"></i> Reject Payment</button>
                        </form>

                    @endif

                    @if($booking->booking_status === 'pending' && $booking->payment_status === 'paid')
                        <form action="{{ route('vendor.transport-bookings.confirm', $booking) }}" method="POST" onsubmit="return confirm('Confirm this booking?')">
                            @csrf
                            <button type="submit" class="rs-btn rs-btn-primary rs-btn-block"><i class="bi bi-calendar-check"></i> Confirm Booking</button>
                        </form>
                    @endif

                    @if(!in_array($booking->booking_status, ['completed', 'cancelled'], true))
                        <a href="{{ route('vendor.transport-bookings.edit', $booking) }}" class="rs-btn rs-btn-warning rs-btn-block"><i class="bi bi-pencil"></i> Edit Booking</a>
                    @endif

                    @if(!in_array($booking->booking_status, ['completed', 'cancelled'], true))
                        <form action="{{ route('vendor.transport-bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                            @csrf
                            <button type="submit" class="rs-btn rs-btn-danger-outline rs-btn-block"><i class="bi bi-x-lg"></i> Cancel Booking</button>
                        </form>
                    @endif

                    <form action="{{ route('vendor.transport-bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this booking?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="rs-btn rs-btn-danger-outline rs-btn-block"><i class="bi bi-trash"></i> Delete Booking</button>
                    </form>

                    <a href="{{ route('vendor.transport-bookings.index') }}" class="rs-btn rs-btn-ghost rs-btn-block"><i class="bi bi-arrow-left"></i> Back to Bookings</a>

                </div>
            </div>

        </div>

    </div>

</div>

@endsection