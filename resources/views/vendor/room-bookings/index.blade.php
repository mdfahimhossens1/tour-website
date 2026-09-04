@extends('layouts.vendor')

@section('title', 'Room Bookings')

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
.rs-header-content { position: relative; z-index: 1; }
.rs-title { font-size: 1.5rem; font-weight: 700; background: linear-gradient(90deg, #fff, #a5b4fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.rs-subtitle { color: rgba(255,255,255,.45); font-size: .82rem; margin-top: 5px; }

.rs-wrap .alert { background: var(--rs-surface); border: 1px solid var(--rs-border); color: var(--rs-text); border-radius: 12px; font-size: .84rem; box-shadow: var(--rs-shadow); }
.rs-wrap .alert-success { border-left: 3px solid var(--rs-success); }
.rs-wrap .alert-danger { border-left: 3px solid var(--rs-danger); }
.rs-wrap .btn-close { filter: invert(1) grayscale(1) opacity(.6); }

.rs-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 22px; }
.rs-stat { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); padding: 18px 20px; box-shadow: var(--rs-shadow); display: flex; align-items: center; gap: 14px; }
.rs-stat-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.rs-stat-total .rs-stat-icon    { background: rgba(99,102,241,.12); color: #a5b4fc; }
.rs-stat-pending .rs-stat-icon  { background: rgba(245,158,11,.12); color: #fcd34d; }
.rs-stat-confirmed .rs-stat-icon{ background: rgba(34,197,94,.12);  color: #86efac; }
.rs-stat-earning .rs-stat-icon  { background: rgba(34,197,94,.12);  color: #86efac; }
.rs-stat-label { color: var(--rs-muted); font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
.rs-stat-value { color: var(--rs-text); font-size: 1.1rem; font-family: 'JetBrains Mono', monospace; font-weight: 700; margin-top: 3px; }

.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; }
.rs-toolbar { padding: 17px 20px; border-bottom: 1px solid var(--rs-border); }
.rs-toolbar h2 { font-size: .95rem; font-weight: 700; margin: 0 0 3px; color: var(--rs-text); }
.rs-toolbar span { font-size: .74rem; color: var(--rs-muted); }

.rs-table { width: 100%; min-width: 1150px; border-collapse: collapse; }
.rs-table thead tr { background: var(--rs-surface2); border-bottom: 1px solid var(--rs-border); }
.rs-table th { padding: 13px 17px; text-align: left; color: var(--rs-muted); font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; white-space: nowrap; }
.rs-table td { padding: 15px 17px; border-bottom: 1px solid var(--rs-border); font-size: .79rem; vertical-align: middle; color: var(--rs-text); }
.rs-table tbody tr:hover { background: rgba(255,255,255,.02); }
.rs-table tbody tr:last-child td { border-bottom: none; }

.rs-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 9px; border-radius: 7px; font-size: .68rem; font-weight: 700; }
.rs-badge-success   { background: rgba(34,197,94,.1);  color: #86efac; border: 1px solid rgba(34,197,94,.18); }
.rs-badge-primary   { background: rgba(99,102,241,.1); color: #a5b4fc; border: 1px solid rgba(99,102,241,.18); }
.rs-badge-danger    { background: rgba(239,68,68,.1);  color: #fca5a5; border: 1px solid rgba(239,68,68,.18); }
.rs-badge-warning   { background: rgba(245,158,11,.1); color: #fcd34d; border: 1px solid rgba(245,158,11,.18); }
.rs-badge-secondary { background: rgba(148,163,184,.1); color: #cbd5e1; border: 1px solid rgba(148,163,184,.18); }
.rs-badge-muted     { background: rgba(255,255,255,.04); color: var(--rs-muted); border: 1px solid var(--rs-border); }

.rs-view-btn {
    display: inline-flex; align-items: center; gap: 6px;
    border: 1px solid rgba(99,102,241,.3); background: rgba(99,102,241,.1); color: #a5b4fc;
    border-radius: 8px; padding: 7px 13px; font-size: .74rem; font-weight: 600; text-decoration: none;
}
.rs-view-btn:hover { background: rgba(99,102,241,.2); color: #c7d2fe; }

.rs-empty { text-align: center; padding: 70px 20px; color: var(--rs-muted); }
.rs-empty i { font-size: 2.4rem; opacity: .25; margin-bottom: 12px; display: block; }
.rs-empty-title { color: var(--rs-text); font-size: .95rem; font-weight: 700; margin-bottom: 6px; }

.rs-footer { padding: 14px 20px; border-top: 1px solid var(--rs-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
.rs-footer .rs-count { font-size: .76rem; color: var(--rs-muted); }
.rs-footer .pagination { margin: 0; }
.rs-footer .page-link { background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-muted); font-size: .75rem; }
.rs-footer .page-link:hover { background: rgba(255,255,255,.07); color: var(--rs-text); }
.rs-footer .page-item.active .page-link { background: var(--rs-indigo); border-color: var(--rs-indigo); color: #fff; }

@media (max-width: 900px) { .rs-stats { grid-template-columns: 1fr 1fr; } }
@media (max-width: 600px) { .rs-stats { grid-template-columns: 1fr; } }

</style>


<div class="rs-wrap">


    {{-- HEADER --}}

    <div class="rs-header">
        <div class="rs-header-content">
            <div class="rs-title"><i class="bi bi-calendar-check me-2"></i> Room Bookings</div>
            <div class="rs-subtitle">Manage and monitor all room bookings.</div>
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


    {{-- STATS --}}

    <div class="rs-stats">

        <div class="rs-stat rs-stat-total">
            <div class="rs-stat-icon"><i class="bi bi-calendar-check"></i></div>
            <div>
                <div class="rs-stat-label">Total Bookings</div>
                <div class="rs-stat-value">{{ $bookings->total() }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-pending">
            <div class="rs-stat-icon"><i class="bi bi-clock"></i></div>
            <div>
                <div class="rs-stat-label">Pending</div>
                <div class="rs-stat-value">{{ $bookings->where('booking_status', 'pending')->count() }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-confirmed">
            <div class="rs-stat-icon"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="rs-stat-label">Confirmed</div>
                <div class="rs-stat-value">{{ $bookings->where('booking_status', 'confirmed')->count() }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-earning">
            <div class="rs-stat-icon"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="rs-stat-label">Current Page Earnings</div>
                <div class="rs-stat-value">৳{{ number_format($bookings->sum('vendor_earning'), 2) }}</div>
            </div>
        </div>

    </div>


    {{-- TABLE --}}

    <div class="rs-card">

        <div class="rs-toolbar">
            <h2>Room Booking List</h2>
            <span>All room bookings associated with your resorts.</span>
        </div>

        @if($bookings->count())

            <div class="table-responsive">

                <table class="rs-table">

                    <thead>
                        <tr>
                            <th class="ps-4">Booking</th>
                            <th>Customer</th>
                            <th>Resort / Room</th>
                            <th>Stay</th>
                            <th>Guests</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Booking Status</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($bookings as $booking)

                            <tr>

                                <td class="ps-4">
                                    <div class="fw-bold">{{ $booking->booking_code }}</div>
                                    <small style="color:var(--rs-muted);">{{ $booking->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</small>
                                </td>

                                <td>
                                    <div class="fw-semibold">{{ $booking->user?->name ?? 'N/A' }}</div>
                                    @if($booking->user?->email)
                                        <small style="color:var(--rs-muted);">{{ $booking->user->email }}</small>
                                    @endif
                                </td>

                                <td>
                                    <div class="fw-semibold">{{ $booking->room?->name ?? 'N/A' }}</div>
                                    <small style="color:var(--rs-muted);">
                                        {{ $booking->room?->name ?? 'N/A' }}
                                        @if($booking->room?->room_no)
                                            · Room {{ $booking->room->room_no }}
                                        @endif
                                    </small>
                                </td>

                                <td>
                                    <div class="fw-semibold">{{ $booking->check_in?->format('d M Y') ?? 'N/A' }}</div>
                                    <small style="color:var(--rs-muted);">to {{ $booking->check_out?->format('d M Y') ?? 'N/A' }}</small>
                                    <div class="mt-1"><span class="rs-badge rs-badge-muted">{{ $booking->total_nights }} {{ $booking->total_nights == 1 ? 'Night' : 'Nights' }}</span></div>
                                </td>

                                <td>
                                    <div class="fw-semibold"><i class="bi bi-people me-1" style="color:var(--rs-muted);"></i>{{ $booking->adults ?? 0 }} {{ ($booking->adults ?? 0) == 1 ? 'Adult' : 'Adults' }}</div>
                                    @if(($booking->children ?? 0) > 0)
                                        <small style="color:var(--rs-muted);">{{ $booking->children }} {{ $booking->children == 1 ? 'Child' : 'Children' }}</small>
                                    @endif
                                    <div class="mt-1"><span class="rs-badge rs-badge-muted">{{ $booking->room_count ?? 1 }} {{ ($booking->room_count ?? 1) == 1 ? 'Room' : 'Rooms' }}</span></div>
                                </td>

                                <td>
                                    <div class="fw-bold">৳{{ number_format($booking->total_amount ?? 0, 2) }}</div>
                                    <small style="color:#86efac;">Earning: ৳{{ number_format($booking->vendor_earning ?? 0, 2) }}</small>
                                </td>

                                <td>
                                    @php
                                        $payClass = match($booking->payment_status) {
                                            'paid' => 'rs-badge-success',
                                            'pending' => 'rs-badge-warning',
                                            'failed' => 'rs-badge-danger',
                                            'refunded' => 'rs-badge-secondary',
                                            default => 'rs-badge-secondary',
                                        };
                                        $payIcon = match($booking->payment_status) {
                                            'paid' => 'bi-check-circle',
                                            'pending' => 'bi-clock',
                                            'failed' => 'bi-x-circle',
                                            'refunded' => 'bi-arrow-counterclockwise',
                                            default => 'bi-question-circle',
                                        };
                                    @endphp
                                    <span class="rs-badge {{ $payClass }}"><i class="bi {{ $payIcon }}"></i> {{ ucfirst($booking->payment_status ?? 'Unknown') }}</span>
                                </td>

                                <td>
                                    @php
                                        $statusMap = [
                                            'pending' => ['rs-badge-warning', 'bi-clock', 'Pending'],
                                            'confirmed' => ['rs-badge-success', 'bi-check-circle', 'Confirmed'],
                                            'checked_in' => ['rs-badge-primary', 'bi-box-arrow-in-right', 'Checked In'],
                                            'checked_out' => ['rs-badge-secondary', 'bi-box-arrow-right', 'Checked Out'],
                                            'cancelled' => ['rs-badge-danger', 'bi-x-circle', 'Cancelled'],
                                        ];
                                        [$sClass, $sIcon, $sLabel] = $statusMap[$booking->booking_status] ?? ['rs-badge-secondary', 'bi-question-circle', ucfirst($booking->booking_status ?? 'Unknown')];
                                    @endphp
                                    <span class="rs-badge {{ $sClass }}"><i class="bi {{ $sIcon }}"></i> {{ $sLabel }}</span>
                                </td>

                                <td class="text-end pe-4">
                                    <a href="{{ route('vendor.room-bookings.show', $booking) }}" class="rs-view-btn">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            @if($bookings->hasPages())

                <div class="rs-footer">
                    <div class="rs-count">Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} bookings</div>
                    <div>{{ $bookings->links() }}</div>
                </div>

            @endif

        @else

            <div class="rs-empty">
                <i class="bi bi-calendar-x"></i>
                <div class="rs-empty-title">No Room Bookings Found</div>
                <div style="font-size:.78rem;">There are currently no room bookings associated with your resorts.</div>
            </div>

        @endif

    </div>

</div>

@endsection