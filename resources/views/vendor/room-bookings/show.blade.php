@extends('layouts.vendor')

@section('title', 'Room Booking Details')

@section('page')

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
    border-radius: var(--rs-radius); padding: 28px 30px; margin-bottom: 22px;
    box-shadow: var(--rs-shadow); position: relative; overflow: hidden;
}
.rs-header::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}
.rs-header-content { position: relative; z-index: 1; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px; }
.rs-title { font-size: 1.5rem; font-weight: 700; background: linear-gradient(90deg, #fff, #a5b4fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.rs-subtitle { color: rgba(255,255,255,.45); font-size: .82rem; margin-top: 5px; }

.rs-wrap .alert { background: var(--rs-surface); border: 1px solid var(--rs-border); color: var(--rs-text); border-radius: 12px; font-size: .84rem; box-shadow: var(--rs-shadow); }
.rs-wrap .alert-success { border-left: 3px solid var(--rs-success); }
.rs-wrap .alert-danger  { border-left: 3px solid var(--rs-danger); }
.rs-wrap .alert-warning { border-left: 3px solid var(--rs-warning); }
.rs-wrap .alert-primary { border-left: 3px solid var(--rs-indigo); }
.rs-wrap .alert-secondary { border-left: 3px solid var(--rs-secondary); }
.rs-wrap .btn-close { filter: invert(1) grayscale(1) opacity(.6); }

.rs-btn {
    display: inline-flex; align-items: center; gap: 6px;
    border: 1px solid rgba(255,255,255,.12); background: var(--rs-surface2); color: var(--rs-text);
    border-radius: 9px; padding: 9px 16px; font-size: .8rem; font-weight: 600; text-decoration: none;
}
.rs-btn:hover { background: rgba(255,255,255,.08); color: var(--rs-text); }
.rs-btn-outline-primary { border-color: rgba(99,102,241,.35); background: rgba(99,102,241,.1); color: #a5b4fc; }
.rs-btn-outline-primary:hover { background: rgba(99,102,241,.2); color: #c7d2fe; }

.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); margin-bottom: 22px; overflow: hidden; }
.rs-card-header { padding: 17px 20px; border-bottom: 1px solid var(--rs-border); display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; }
.rs-card-header h5 { font-size: .95rem; font-weight: 700; margin: 0 0 3px; color: var(--rs-text); }
.rs-card-header small { font-size: .74rem; color: var(--rs-muted); }
.rs-card-body { padding: 20px; }
.rs-card-body.p-0 { padding: 0; }

.rs-box { border: 1px solid var(--rs-border); background: var(--rs-surface2); border-radius: 11px; padding: 14px 16px; height: 100%; }
.rs-box small { color: var(--rs-muted); font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; display: block; margin-bottom: 5px; }
.rs-box .val { font-weight: 700; font-size: 1.02rem; color: var(--rs-text); }

.rs-label { color: var(--rs-muted); font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; display: block; margin-bottom: 3px; }
.rs-value { font-weight: 600; font-size: .85rem; color: var(--rs-text); }

.rs-divider { border: none; border-top: 1px solid var(--rs-border); margin: 20px 0; }

.rs-avatar { width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.rs-avatar-primary { background: rgba(99,102,241,.12); color: #a5b4fc; }
.rs-avatar-success { background: rgba(34,197,94,.12); color: #86efac; }

.rs-badge { display: inline-flex; align-items: center; gap: 5px; padding: 6px 11px; border-radius: 7px; font-size: .72rem; font-weight: 700; }
.rs-badge-success   { background: rgba(34,197,94,.1);  color: #86efac; border: 1px solid rgba(34,197,94,.18); }
.rs-badge-primary   { background: rgba(99,102,241,.1); color: #a5b4fc; border: 1px solid rgba(99,102,241,.18); }
.rs-badge-danger    { background: rgba(239,68,68,.1);  color: #fca5a5; border: 1px solid rgba(239,68,68,.18); }
.rs-badge-warning   { background: rgba(245,158,11,.1); color: #fcd34d; border: 1px solid rgba(245,158,11,.18); }
.rs-badge-secondary { background: rgba(148,163,184,.1); color: #cbd5e1; border: 1px solid rgba(148,163,184,.18); }
.rs-badge-muted     { background: rgba(255,255,255,.04); color: var(--rs-muted); border: 1px solid var(--rs-border); }

.rs-table { width: 100%; border-collapse: collapse; }
.rs-table thead tr { background: var(--rs-surface2); border-bottom: 1px solid var(--rs-border); }
.rs-table th { padding: 12px 17px; text-align: left; color: var(--rs-muted); font-size: .64rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; white-space: nowrap; }
.rs-table td { padding: 14px 17px; border-bottom: 1px solid var(--rs-border); font-size: .79rem; vertical-align: middle; color: var(--rs-text); }
.rs-table tbody tr:hover { background: rgba(255,255,255,.02); }
.rs-table tbody tr:last-child td { border-bottom: none; }

.rs-guest-box { border: 1px solid var(--rs-border); background: var(--rs-surface2); border-radius: 11px; padding: 14px 16px; height: 100%; }

.rs-empty { text-align: center; padding: 50px 20px; color: var(--rs-muted); }
.rs-empty i { font-size: 2.2rem; opacity: .25; margin-bottom: 10px; display: block; }
.rs-empty .fw-semibold { color: var(--rs-text); }

.rs-summary-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: .84rem; }
.rs-summary-row span:first-child { color: var(--rs-muted); }
.rs-summary-row.total span { font-weight: 700; font-size: 1.05rem; color: var(--rs-text); }

.rs-mini-btn { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 7px 13px; font-size: .74rem; font-weight: 600; border: 1px solid transparent; cursor: pointer; }
.rs-mini-success { background: rgba(34,197,94,.12); color: #86efac; border-color: rgba(34,197,94,.25); }
.rs-mini-success:hover { background: rgba(34,197,94,.22); }
.rs-mini-danger-outline { background: rgba(239,68,68,.08); color: #fca5a5; border-color: rgba(239,68,68,.25); }
.rs-mini-danger-outline:hover { background: rgba(239,68,68,.18); }

.rs-action-btn { display: flex; align-items: center; justify-content: center; gap: 7px; width: 100%; border: none; border-radius: 10px; padding: 11px 16px; font-size: .82rem; font-weight: 700; cursor: pointer; text-decoration: none; margin-bottom: 10px; }
.rs-action-success { background: linear-gradient(135deg, #22c55e, #16a34a); color: #fff; }
.rs-action-primary { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; }
.rs-action-secondary { background: var(--rs-surface2); color: var(--rs-text); border: 1px solid var(--rs-border); }
.rs-action-outline-danger { background: rgba(239,68,68,.08); color: #fca5a5; border: 1px solid rgba(239,68,68,.3); }
.rs-action-outline-danger:hover { background: rgba(239,68,68,.18); color: #fca5a5; }

@media (max-width: 991px) { .rs-sidebar { margin-top: 0; } }

</style>


<div class="rs-wrap">

    {{-- HEADER --}}
    <div class="rs-header">
        <div class="rs-header-content">
            <div>
                <div class="rs-title"><i class="bi bi-calendar-check me-2"></i> Room Booking Details</div>
                <div class="rs-subtitle">View and manage booking information.</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('vendor.room-bookings.index') }}" class="rs-btn">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                @if(!in_array($booking->booking_status, ['checked_out', 'cancelled']))
                    <a href="{{ route('vendor.room-bookings.edit', $booking) }}" class="rs-btn rs-btn-outline-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                @endif
            </div>
        </div>
    </div>


    {{-- MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    <div class="row g-4">

        {{-- ================= MAIN CONTENT ================= --}}
        <div class="col-xl-8">

            {{-- Booking Overview --}}
            <div class="rs-card">
                <div class="rs-card-header">
                    <div>
                        <h5>Booking Overview</h5>
                        <small>{{ $booking->booking_code }}</small>
                    </div>
                    <div>
                        @switch($booking->booking_status)
                            @case('pending')
                                <span class="rs-badge rs-badge-warning"><i class="bi bi-clock"></i> Pending</span>
                                @break
                            @case('confirmed')
                                <span class="rs-badge rs-badge-success"><i class="bi bi-check-circle"></i> Confirmed</span>
                                @break
                            @case('checked_in')
                                <span class="rs-badge rs-badge-primary"><i class="bi bi-box-arrow-in-right"></i> Checked In</span>
                                @break
                            @case('checked_out')
                                <span class="rs-badge rs-badge-secondary"><i class="bi bi-box-arrow-right"></i> Checked Out</span>
                                @break
                            @case('cancelled')
                                <span class="rs-badge rs-badge-danger"><i class="bi bi-x-circle"></i> Cancelled</span>
                                @break
                            @default
                                <span class="rs-badge rs-badge-secondary">{{ ucfirst($booking->booking_status ?? 'Unknown') }}</span>
                        @endswitch
                    </div>
                </div>

                <div class="rs-card-body">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="rs-box">
                                <small>Check-in</small>
                                <div class="val">{{ $booking->check_in?->format('d M Y') ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rs-box">
                                <small>Check-out</small>
                                <div class="val">{{ $booking->check_out?->format('d M Y') ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rs-box">
                                <small>Stay Duration</small>
                                <div class="val">{{ $booking->total_nights ?? 0 }} {{ ($booking->total_nights ?? 0) == 1 ? 'Night' : 'Nights' }}</div>
                            </div>
                        </div>
                    </div>

                    <hr class="rs-divider">

                    <div class="row g-3">
                        <div class="col-md-3">
                            <span class="rs-label">Rooms</span>
                            <div class="rs-value">{{ $booking->room_count ?? 1 }}</div>
                        </div>
                        <div class="col-md-3">
                            <span class="rs-label">Adults</span>
                            <div class="rs-value">{{ $booking->adults ?? 0 }}</div>
                        </div>
                        <div class="col-md-3">
                            <span class="rs-label">Children</span>
                            <div class="rs-value">{{ $booking->children ?? 0 }}</div>
                        </div>
                        <div class="col-md-3">
                            <span class="rs-label">Booked At</span>
                            <div class="rs-value">{{ $booking->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</div>
                        </div>
                    </div>

                </div>
            </div>


            {{-- Customer & Room --}}
            <div class="rs-card">
                <div class="rs-card-header">
                    <h5>Customer & Room Information</h5>
                </div>
                <div class="rs-card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="rs-box">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rs-avatar rs-avatar-primary me-3"><i class="bi bi-person fs-5"></i></div>
                                    <div>
                                        <span class="rs-label mb-0">Customer</span>
                                        <div class="fw-bold" style="color:var(--rs-text);">{{ $booking->user?->name ?? 'N/A' }}</div>
                                    </div>
                                </div>

                                @if($booking->user?->email)
                                    <div class="mb-2">
                                        <span class="rs-label mb-0">Email</span>
                                        <div class="rs-value">{{ $booking->user->email }}</div>
                                    </div>
                                @endif

                                @if($booking->user?->phone)
                                    <div>
                                        <span class="rs-label mb-0">Phone</span>
                                        <div class="rs-value">{{ $booking->user->phone }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="rs-box">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rs-avatar rs-avatar-success me-3"><i class="bi bi-building fs-5"></i></div>
                                    <div>
                                        <span class="rs-label mb-0">Resort</span>
                                        <div class="fw-bold" style="color:var(--rs-text);">{{ $booking->room?->name ?? 'N/A' }}</div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <span class="rs-label mb-0">Room</span>
                                    <div class="rs-value">{{ $booking->room?->name ?? 'N/A' }}</div>
                                </div>

                                @if($booking->room?->room_no)
                                    <div>
                                        <span class="rs-label mb-0">Room Number</span>
                                        <div class="rs-value">{{ $booking->room->room_no }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            {{-- Guest Information --}}
            <div class="rs-card">
                <div class="rs-card-header">
                    <h5 class="mb-0">Guest Information</h5>
                    <span class="rs-badge rs-badge-muted">
                        {{ $booking->guests?->count() ?? 0 }} {{ ($booking->guests?->count() ?? 0) == 1 ? 'Guest' : 'Guests' }}
                    </span>
                </div>

                <div class="rs-card-body">
                    @if($booking->guests && $booking->guests->count())

                        <div class="row g-3">
                            @foreach($booking->guests as $guest)
                                <div class="col-md-6">
                                    <div class="rs-guest-box">
                                        <div class="fw-bold mb-2" style="color:var(--rs-text);">
                                            <i class="bi bi-person-circle me-1"></i> {{ $guest->name }}
                                        </div>

                                        <div class="row g-2">
                                            @if($guest->age !== null)
                                                <div class="col-6">
                                                    <span class="rs-label mb-0">Age</span>
                                                    <span class="rs-value">{{ $guest->age }}</span>
                                                </div>
                                            @endif

                                            @if($guest->gender)
                                                <div class="col-6">
                                                    <span class="rs-label mb-0">Gender</span>
                                                    <span class="rs-value">{{ ucfirst($guest->gender) }}</span>
                                                </div>
                                            @endif

                                            @if($guest->phone)
                                                <div class="col-12">
                                                    <span class="rs-label mb-0">Phone</span>
                                                    <span class="rs-value">{{ $guest->phone }}</span>
                                                </div>
                                            @endif

                                            @if($guest->nid)
                                                <div class="col-12">
                                                    <span class="rs-label mb-0">NID</span>
                                                    <span class="rs-value">{{ $guest->nid }}</span>
                                                </div>
                                            @endif

                                            @if($guest->passport)
                                                <div class="col-12">
                                                    <span class="rs-label mb-0">Passport</span>
                                                    <span class="rs-value">{{ $guest->passport }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @else
                        <div class="rs-empty">
                            <i class="bi bi-people"></i>
                            No additional guest information available.
                        </div>
                    @endif
                </div>
            </div>


            {{-- Special Request --}}
            @if($booking->special_request)
                <div class="rs-card">
                    <div class="rs-card-header">
                        <h5>Special Request</h5>
                    </div>
                    <div class="rs-card-body">
                        <div class="rs-box" style="color:var(--rs-text);">
                            {!! nl2br(e($booking->special_request)) !!}
                        </div>
                    </div>
                </div>
            @endif


            {{-- Payment Information --}}
            <div class="rs-card">
                <div class="rs-card-header">
                    <div>
                        <h5>Payment Information</h5>
                        <small>Customer payment records</small>
                    </div>

                    @if($booking->payment_status === 'paid')
                        <span class="rs-badge rs-badge-success"><i class="bi bi-check-circle"></i> Payment Paid</span>
                    @elseif($booking->payment_status === 'pending')
                        <span class="rs-badge rs-badge-warning"><i class="bi bi-clock"></i> Payment Pending</span>
                    @elseif($booking->payment_status === 'failed')
                        <span class="rs-badge rs-badge-danger"><i class="bi bi-x-circle"></i> Payment Failed</span>
                    @elseif($booking->payment_status === 'refunded')
                        <span class="rs-badge rs-badge-secondary"><i class="bi bi-arrow-counterclockwise"></i> Refunded</span>
                    @else
                        <span class="rs-badge rs-badge-secondary">{{ ucfirst($booking->payment_status ?? 'Unknown') }}</span>
                    @endif
                </div>

                <div class="rs-card-body p-0">

                    @if($booking->payments && $booking->payments->count())

                        <div class="table-responsive">
                            <table class="rs-table">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Transaction ID</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Paid At</th>
                                        <th class="pe-4 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booking->payments as $payment)
                                        <tr>
                                            <td class="ps-4 fw-semibold">{{ $payment->trx_id ?? 'N/A' }}</td>

                                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}</td>

                                            <td class="fw-semibold">৳{{ number_format($payment->amount ?? 0, 2) }}</td>

                                            <td>
                                                @if($payment->status === 'paid')
                                                    <span class="rs-badge rs-badge-success"><i class="bi bi-check-circle"></i> Paid</span>
                                                @elseif($payment->status === 'pending')
                                                    <span class="rs-badge rs-badge-warning"><i class="bi bi-clock"></i> Pending</span>
                                                @elseif($payment->status === 'failed')
                                                    <span class="rs-badge rs-badge-danger"><i class="bi bi-x-circle"></i> Failed</span>
                                                @elseif($payment->status === 'refunded')
                                                    <span class="rs-badge rs-badge-secondary"><i class="bi bi-arrow-counterclockwise"></i> Refunded</span>
                                                @else
                                                    <span class="rs-badge rs-badge-secondary">{{ ucfirst($payment->status ?? 'Unknown') }}</span>
                                                @endif
                                            </td>

                                            <td>{{ $payment->paid_at?->format('d M Y, h:i A') ?? 'N/A' }}</td>

                                            <td class="pe-4 text-end">
                                                @if($payment->status === 'pending' && $booking->booking_status === 'pending')
                                                    <div class="d-flex justify-content-end gap-2">

                                                        <form action="{{ route('vendor.room-bookings.payment.approve', $payment) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="rs-mini-btn rs-mini-success" onclick="return confirm('Are you sure you want to approve this payment?')">
                                                                <i class="bi bi-check-circle"></i> Approve
                                                            </button>
                                                        </form>

                                                        <form action="{{ route('vendor.room-bookings.payment.reject', $payment) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="rs-mini-btn rs-mini-danger-outline" onclick="return confirm('Are you sure you want to reject this payment?')">
                                                                <i class="bi bi-x-circle"></i> Reject
                                                            </button>
                                                        </form>

                                                    </div>
                                                @elseif($payment->status === 'paid')
                                                    <span class="rs-badge rs-badge-success"><i class="bi bi-check-circle"></i> Verified</span>
                                                @elseif($payment->status === 'failed')
                                                    <span class="rs-badge rs-badge-danger">Rejected</span>
                                                @else
                                                    <span style="color:var(--rs-muted);">—</span>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @else
                        <div class="rs-empty">
                            <i class="bi bi-credit-card"></i>
                            <div class="fw-semibold">No payment submitted yet.</div>
                            <small>Waiting for customer payment.</small>
                        </div>
                    @endif

                </div>
            </div>

        </div>


        {{-- ================= SIDEBAR ================= --}}
        <div class="col-xl-4 rs-sidebar">

            {{-- Payment Summary --}}
            <div class="rs-card">
                <div class="rs-card-header">
                    <h5>Payment Summary</h5>
                </div>
                <div class="rs-card-body">

                    <div class="rs-summary-row">
                        <span>Room Price</span>
                        <span>৳{{ number_format($booking->room_price ?? 0, 2) }}</span>
                    </div>

                    <div class="rs-summary-row">
                        <span>Subtotal</span>
                        <span>৳{{ number_format($booking->subtotal ?? 0, 2) }}</span>
                    </div>

                    <div class="rs-summary-row">
                        <span>Discount</span>
                        <span style="color:#86efac;">- ৳{{ number_format($booking->discount ?? 0, 2) }}</span>
                    </div>

                    <div class="rs-summary-row">
                        <span>Tax</span>
                        <span>৳{{ number_format($booking->tax ?? 0, 2) }}</span>
                    </div>

                    <hr class="rs-divider">

                    <div class="rs-summary-row total">
                        <span>Total Amount</span>
                        <span>৳{{ number_format($booking->total_amount ?? 0, 2) }}</span>
                    </div>

                    @if($booking->booking_status !== 'pending' && $booking->vendor_earning > 0)
                        <div class="rs-summary-row mb-0">
                            <span>Vendor Earning</span>
                            <span class="fw-bold" style="color:#86efac;">৳{{ number_format($booking->vendor_earning, 2) }}</span>
                        </div>
                    @else
                        <div class="rs-summary-row mb-0">
                            <span>Vendor Earning</span>
                            <span>Not calculated yet</span>
                        </div>
                    @endif

                </div>
            </div>


            {{-- Payment Status --}}
            <div class="rs-card">
                <div class="rs-card-header">
                    <h5>Payment Status</h5>
                </div>
                <div class="rs-card-body">

                    @if($booking->payment_status === 'paid')
                        <div class="alert alert-success mb-0">
                            <div class="d-flex">
                                <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                                <div>
                                    <div class="fw-bold">Payment Confirmed</div>
                                    <small>Payment has been verified by the vendor. You can now confirm the booking.</small>
                                </div>
                            </div>
                        </div>

                    @elseif($booking->payment_status === 'pending')
                        <div class="alert alert-warning mb-0">
                            <div class="d-flex">
                                <i class="bi bi-clock-fill fs-4 me-2"></i>
                                <div>
                                    <div class="fw-bold">Payment Pending</div>
                                    <small>Customer has submitted a payment. Please verify the payment before confirming the booking.</small>
                                </div>
                            </div>
                        </div>

                    @elseif($booking->payment_status === 'failed')
                        <div class="alert alert-danger mb-0">
                            <div class="d-flex">
                                <i class="bi bi-x-circle-fill fs-4 me-2"></i>
                                <div>
                                    <div class="fw-bold">Payment Failed</div>
                                    <small>The submitted payment was rejected or failed. Customer needs to submit a valid payment.</small>
                                </div>
                            </div>
                        </div>

                    @elseif($booking->payment_status === 'refunded')
                        <div class="alert alert-secondary mb-0">
                            <div class="d-flex">
                                <i class="bi bi-arrow-counterclockwise fs-4 me-2"></i>
                                <div>
                                    <div class="fw-bold">Payment Refunded</div>
                                    <small>This payment has been refunded.</small>
                                </div>
                            </div>
                        </div>

                    @else
                        <div class="alert alert-secondary mb-0">Unknown payment status.</div>
                    @endif

                </div>
            </div>


            {{-- Booking Actions --}}
            <div class="rs-card">
                <div class="rs-card-header">
                    <h5>Booking Actions</h5>
                </div>
                <div class="rs-card-body">

                    @if($booking->booking_status === 'pending' && $booking->payment_status === 'pending')
                        <div class="alert alert-warning">
                            <div class="d-flex">
                                <i class="bi bi-shield-exclamation fs-4 me-2"></i>
                                <div>
                                    <div class="fw-bold">Payment Verification Required</div>
                                    <small>Please verify the customer's payment from the Payment Information section before confirming this booking.</small>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($booking->booking_status === 'pending' && $booking->payment_status === 'failed')
                        <div class="alert alert-danger">
                            <div class="d-flex">
                                <i class="bi bi-x-circle-fill fs-4 me-2"></i>
                                <div>
                                    <div class="fw-bold">Payment Failed</div>
                                    <small>This booking cannot be confirmed until a valid payment is submitted.</small>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($booking->booking_status === 'pending' && $booking->payment_status === 'paid')
                        <div class="alert alert-success">
                            <div class="d-flex">
                                <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                                <div>
                                    <div class="fw-bold">Payment Verified</div>
                                    <small>Payment has been verified successfully. You can now confirm this booking.</small>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('vendor.room-bookings.confirm', $booking) }}" method="POST">
                            @csrf
                            <button type="submit" class="rs-action-btn rs-action-success" onclick="return confirm('Are you sure you want to confirm this booking?')">
                                <i class="bi bi-check-circle"></i> Confirm Booking
                            </button>
                        </form>
                    @endif

                    @if($booking->booking_status === 'confirmed')
                        <div class="alert alert-success">
                            <div class="d-flex">
                                <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                                <div>
                                    <div class="fw-bold">Booking Confirmed</div>
                                    <small>Payment is paid and booking is confirmed.</small>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('vendor.room-bookings.check-in', $booking) }}" method="POST">
                            @csrf
                            <button type="submit" class="rs-action-btn rs-action-primary" onclick="return confirm('Check in this guest?')">
                                <i class="bi bi-box-arrow-in-right"></i> Check In Guest
                            </button>
                        </form>
                    @endif

                    @if($booking->booking_status === 'checked_in')
                        <div class="alert alert-primary">
                            <div class="d-flex">
                                <i class="bi bi-person-check-fill fs-4 me-2"></i>
                                <div>
                                    <div class="fw-bold">Guest Checked In</div>
                                    <small>Guest is currently staying at the resort.</small>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('vendor.room-bookings.check-out', $booking) }}" method="POST">
                            @csrf
                            <button type="submit" class="rs-action-btn rs-action-secondary" onclick="return confirm('Check out this guest?')">
                                <i class="bi bi-box-arrow-right"></i> Check Out Guest
                            </button>
                        </form>
                    @endif

                    @if(!in_array($booking->booking_status, ['cancelled', 'checked_out']))
                        <form action="{{ route('vendor.room-bookings.cancel', $booking) }}" method="POST" class="mt-1">
                            @csrf
                            <button type="submit" class="rs-action-btn rs-action-outline-danger mb-0" onclick="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.')">
                                <i class="bi bi-x-circle"></i> Cancel Booking
                            </button>
                        </form>
                    @endif

                    @if($booking->booking_status === 'checked_out')
                        <div class="alert alert-secondary mb-0 mt-3">
                            <i class="bi bi-check2-all me-1"></i> This booking has been checked out successfully.
                        </div>
                    @elseif($booking->booking_status === 'cancelled')
                        <div class="alert alert-danger mb-0 mt-3">
                            <i class="bi bi-x-circle me-1"></i> This booking has been cancelled.
                        </div>
                    @endif

                </div>
            </div>


            {{-- Commission Information --}}
            @if($booking->booking_status !== 'pending' && $booking->vendor_earning !== null)
                <div class="rs-card mb-0">
                    <div class="rs-card-header">
                        <h5>Commission Information</h5>
                    </div>
                    <div class="rs-card-body">

                        <div class="rs-summary-row">
                            <span>Commission Rate</span>
                            <span class="fw-semibold" style="color:var(--rs-text);">{{ number_format($booking->commission_rate ?? 0, 2) }}%</span>
                        </div>

                        <div class="rs-summary-row">
                            <span>Admin Commission</span>
                            <span class="fw-semibold" style="color:#fca5a5;">৳{{ number_format($booking->admin_commission ?? 0, 2) }}</span>
                        </div>

                        <div class="rs-summary-row mb-0">
                            <span class="fw-bold" style="color:var(--rs-text);">Vendor Earning</span>
                            <span class="fw-bold" style="color:#86efac;">৳{{ number_format($booking->vendor_earning ?? 0, 2) }}</span>
                        </div>

                    </div>
                </div>
            @endif

        </div>

    </div>

</div>

@endsection