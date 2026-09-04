@extends('layouts.vendor')

@section('title', 'Transport Bookings')

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
.rs-wrap .alert ul { padding-left: 18px; margin: 6px 0 0; }

.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; }

.rs-toolbar { padding: 17px 20px; border-bottom: 1px solid var(--rs-border); display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; }
.rs-toolbar h2 { font-size: .95rem; font-weight: 700; margin: 0; color: var(--rs-text); }

.rs-search { display: flex; gap: 8px; max-width: 340px; width: 100%; }
.rs-search input {
    flex: 1; background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-text);
    border-radius: 9px; padding: 9px 13px; font-size: .8rem; outline: none;
}
.rs-search input::placeholder { color: var(--rs-muted); }
.rs-search input:focus { border-color: rgba(99,102,241,.5); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.rs-search button {
    border: none; background: linear-gradient(135deg, var(--rs-indigo), var(--rs-purple)); color: #fff;
    border-radius: 9px; padding: 0 15px; font-size: .82rem; cursor: pointer;
}

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

.rs-action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid var(--rs-border); background: rgba(255,255,255,.02); color: var(--rs-muted); transition: all .2s; }
.rs-action-btn:hover, .rs-action-btn:focus { background: rgba(99,102,241,.14); color: #c7d2fe; border-color: rgba(99,102,241,.3); box-shadow: none; }
.rs-wrap .dropdown-menu { background: var(--rs-surface2); border: 1px solid var(--rs-border); border-radius: 10px; padding: 6px; box-shadow: 0 20px 45px rgba(0,0,0,.5); min-width: 200px; }
.rs-wrap .dropdown-item { border-radius: 7px; font-size: .8rem; padding: 8px 10px; color: #cbd5e1; }
.rs-wrap .dropdown-item:hover { background: rgba(255,255,255,.05); color: #fff; }
.rs-wrap .dropdown-item i { width: 18px; }
.rs-wrap .dropdown-item.text-success { color: #86efac !important; }
.rs-wrap .dropdown-item.text-danger { color: #fca5a5 !important; }
.rs-wrap .dropdown-divider { border-color: var(--rs-border); }

.rs-empty { text-align: center; padding: 70px 20px; color: var(--rs-muted); }
.rs-empty i { font-size: 2.4rem; opacity: .25; margin-bottom: 12px; display: block; }
.rs-empty-title { color: var(--rs-text); font-size: .95rem; font-weight: 700; margin-bottom: 6px; }

.rs-footer { padding: 14px 20px; border-top: 1px solid var(--rs-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
.rs-footer .rs-count { font-size: .76rem; color: var(--rs-muted); }
.rs-footer .pagination { margin: 0; }
.rs-footer .page-link { background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-muted); font-size: .75rem; }
.rs-footer .page-link:hover { background: rgba(255,255,255,.07); color: var(--rs-text); }
.rs-footer .page-item.active .page-link { background: var(--rs-indigo); border-color: var(--rs-indigo); color: #fff; }

@media (max-width: 700px) { .rs-search { max-width: 100%; } }

</style>


<div class="rs-wrap">


    {{-- HEADER --}}

    <div class="rs-header">
        <div class="rs-header-content">
            <div class="rs-title"><i class="bi bi-car-front me-2"></i> Transport Bookings</div>
            <div class="rs-subtitle">Manage your vehicle transport bookings.</div>
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
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- BOOKINGS CARD --}}

    <div class="rs-card">

        <div class="rs-toolbar">

            <h2>All Transport Bookings</h2>

            <form action="{{ route('vendor.transport-bookings.index') }}" method="GET" class="rs-search">
                <input type="text" name="search" placeholder="Search booking..." value="{{ request('search') }}">
                <button type="submit"><i class="bi bi-search"></i></button>
            </form>

        </div>


        <div class="table-responsive">

            <table class="rs-table">

                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Booking</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Journey</th>
                        <th>Passengers</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($bookings as $booking)

                    @php
                        $pendingPayment = null;
                        if ($booking->relationLoaded('payments')) {
                            $pendingPayment = $booking->payments->where('status', 'pending')->sortByDesc('id')->first();
                        }
                    @endphp

                    <tr>

                        <td class="ps-4 fw-semibold">{{ $bookings->firstItem() + $loop->index }}</td>

                        <td>
                            <div class="fw-semibold">{{ $booking->booking_code }}</div>
                            <small style="color:var(--rs-muted);">{{ optional($booking->created_at)->format('d M Y') }}</small>
                        </td>

                        <td>
                            @if($booking->user)
                                <div class="fw-semibold">{{ $booking->user->name }}</div>
                                @if($booking->user->email)
                                    <small style="color:var(--rs-muted);">{{ $booking->user->email }}</small>
                                @endif
                            @else
                                <span style="color:var(--rs-muted);">N/A</span>
                            @endif
                        </td>

                        <td>
                            @if($booking->vehicle)
                                <div class="fw-semibold">{{ $booking->vehicle->name }}</div>
                                <small style="color:var(--rs-muted);">{{ $booking->vehicle->registration_number }}</small>
                            @else
                                <span style="color:var(--rs-muted);">Vehicle unavailable</span>
                            @endif
                        </td>

                        <td>
                            <div>
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</span>
                                <span style="color:var(--rs-muted);">→</span>
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</span>
                            </div>
                            <small style="color:var(--rs-muted);">{{ $booking->total_days }} {{ $booking->total_days == 1 ? 'day' : 'days' }}</small>
                        </td>

                        <td><i class="bi bi-people me-1" style="color:var(--rs-muted);"></i>{{ $booking->passengers }}</td>

                        <td>
                            <div class="fw-bold">{{ number_format((float) $booking->total_amount, 2) }}</div>
                            <small style="color:var(--rs-muted);">/ day {{ number_format((float) $booking->price_per_day, 2) }}</small>
                        </td>

                        <td>
                            @php
                                $paymentClass = match($booking->payment_status) {
                                    'paid' => 'rs-badge-success',
                                    'failed' => 'rs-badge-danger',
                                    'refunded' => 'rs-badge-secondary',
                                    default => 'rs-badge-warning',
                                };
                            @endphp
                            <span class="rs-badge {{ $paymentClass }}">{{ ucfirst($booking->payment_status) }}</span>
                        </td>

                        <td>
                            @php
                                $statusClass = match($booking->booking_status) {
                                    'confirmed' => 'rs-badge-success',
                                    'completed' => 'rs-badge-primary',
                                    'cancelled' => 'rs-badge-danger',
                                    default => 'rs-badge-warning',
                                };
                            @endphp
                            <span class="rs-badge {{ $statusClass }}">{{ ucfirst($booking->booking_status) }}</span>
                        </td>

                        <td class="text-end pe-4">

                            <div class="dropdown">

                                <button class="rs-action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">

                                    <li>
                                        <a href="{{ route('vendor.transport-bookings.show', $booking) }}" class="dropdown-item">
                                            <i class="bi bi-eye me-2"></i> View
                                        </a>
                                    </li>

                                    @if($booking->payment_status === 'pending' && $pendingPayment)

                                        <li><hr class="dropdown-divider"></li>

                                        <li>
                                            <form action="{{ route('vendor.transport-bookings.payments.approve', ['booking' => $booking->id, 'payment' => $pendingPayment->id]) }}"
                                                  method="POST" onsubmit="return confirm('Are you sure you want to approve this payment?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-success">
                                                    <i class="bi bi-check-circle me-2"></i> Approve Payment
                                                </button>
                                            </form>
                                        </li>

                                        <li>
                                            <form action="{{ route('vendor.transport-bookings.payments.reject', ['booking' => $booking->id, 'payment' => $pendingPayment->id]) }}"
                                                  method="POST" onsubmit="return confirm('Are you sure you want to reject this payment?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-x-circle me-2"></i> Reject Payment
                                                </button>
                                            </form>
                                        </li>

                                    @endif

                                    @if($booking->booking_status === 'pending' && $booking->payment_status === 'paid')
                                        <li>
                                            <form action="{{ route('vendor.transport-bookings.confirm', $booking) }}" method="POST"
                                                  onsubmit="return confirm('Are you sure you want to confirm this booking?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-success">
                                                    <i class="bi bi-calendar-check me-2"></i> Confirm Booking
                                                </button>
                                            </form>
                                        </li>
                                    @endif

                                    @if(!in_array($booking->booking_status, ['cancelled', 'completed']))
                                        <li>
                                            <form action="{{ route('vendor.transport-bookings.cancel', $booking) }}" method="POST"
                                                  onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-x-lg me-2"></i> Cancel Booking
                                                </button>
                                            </form>
                                        </li>
                                    @endif

                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <form action="{{ route('vendor.transport-bookings.destroy', $booking) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this transport booking?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>

                                </ul>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="10">
                            <div class="rs-empty">
                                <i class="bi bi-car-front"></i>
                                <div class="rs-empty-title">No transport bookings found</div>
                                <div style="font-size:.78rem;">There are no transport bookings for your vehicles yet.</div>
                            </div>
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{-- FOOTER / PAGINATION --}}

        @if($bookings->hasPages())

            <div class="rs-footer">

                <div class="rs-count">
                    Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} bookings
                </div>

                <div>
                    {{ $bookings->withQueryString()->links() }}
                </div>

            </div>

        @endif

    </div>

</div>

@endsection