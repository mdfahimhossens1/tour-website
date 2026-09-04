@extends('layouts.vendor')

@section('title', 'Commissions')

@section('page')

<style>

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
    --rs-surface: #1a1d27; --rs-surface2: #222636; --rs-border: rgba(255,255,255,.07);
    --rs-text: #e2e8f0; --rs-muted: #64748b;
    --rs-indigo: #6366f1; --rs-purple: #8b5cf6;
    --rs-success: #22c55e; --rs-warning: #f59e0b; --rs-danger: #ef4444; --rs-cyan: #0ea5e9;
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

.rs-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 22px; }
.rs-stat { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); padding: 18px 20px; box-shadow: var(--rs-shadow); display: flex; align-items: center; gap: 14px; }
.rs-stat-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.rs-stat-bookings .rs-stat-icon { background: rgba(99,102,241,.12); color: #a5b4fc; }
.rs-stat-sales .rs-stat-icon    { background: rgba(34,197,94,.12);  color: #86efac; }
.rs-stat-earning .rs-stat-icon  { background: rgba(245,158,11,.12); color: #fcd34d; }
.rs-stat-rate .rs-stat-icon     { background: rgba(14,165,233,.12); color: #7dd3fc; }
.rs-stat-label { color: var(--rs-muted); font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
.rs-stat-value { color: var(--rs-text); font-size: 1.1rem; font-family: 'JetBrains Mono', monospace; font-weight: 700; margin-top: 3px; }

.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; }
.rs-toolbar { padding: 17px 20px; border-bottom: 1px solid var(--rs-border); display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; }
.rs-toolbar h2 { font-size: .95rem; font-weight: 700; margin: 0 0 3px; color: var(--rs-text); }
.rs-toolbar span { font-size: .74rem; color: var(--rs-muted); }

.rs-search { display: flex; gap: 8px; max-width: 340px; width: 100%; }
.rs-search input { flex: 1; background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-text); border-radius: 9px; padding: 9px 13px; font-size: .8rem; outline: none; }
.rs-search input::placeholder { color: var(--rs-muted); }
.rs-search input:focus { border-color: rgba(99,102,241,.5); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.rs-search button { border: none; background: linear-gradient(135deg, var(--rs-indigo), var(--rs-purple)); color: #fff; border-radius: 9px; padding: 0 16px; font-size: .8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }

.rs-table { width: 100%; min-width: 1050px; border-collapse: collapse; }
.rs-table thead tr { background: var(--rs-surface2); border-bottom: 1px solid var(--rs-border); }
.rs-table th { padding: 13px 17px; text-align: left; color: var(--rs-muted); font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; white-space: nowrap; }
.rs-table td { padding: 15px 17px; border-bottom: 1px solid var(--rs-border); font-size: .79rem; vertical-align: middle; color: var(--rs-text); }
.rs-table tbody tr:hover { background: rgba(255,255,255,.02); }
.rs-table tbody tr:last-child td { border-bottom: none; }

.rs-link { color: #a5b4fc; text-decoration: none; font-weight: 600; }
.rs-link:hover { color: #c7d2fe; }

.rs-avatar { width: 34px; height: 34px; border-radius: 50%; background: rgba(99,102,241,.12); color: #a5b4fc; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

.rs-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 9px; border-radius: 7px; font-size: .68rem; font-weight: 700; }
.rs-badge-danger { background: rgba(239,68,68,.1); color: #fca5a5; border: 1px solid rgba(239,68,68,.18); }

.rs-view-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid rgba(99,102,241,.25); background: rgba(99,102,241,.1); color: #a5b4fc; text-decoration: none; }
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
@media (max-width: 600px) { .rs-stats { grid-template-columns: 1fr; } .rs-search { max-width: 100%; } }

</style>


<div class="rs-wrap">


    {{-- HEADER --}}

    <div class="rs-header">
        <div class="rs-header-content">
            <div class="rs-title"><i class="bi bi-receipt me-2"></i> Commissions</div>
            <div class="rs-subtitle">View your booking sales, commission rate and earnings.</div>
        </div>
    </div>


    {{-- STATS --}}

    <div class="rs-stats">

        <div class="rs-stat rs-stat-bookings">
            <div class="rs-stat-icon"><i class="bi bi-calendar-check"></i></div>
            <div>
                <div class="rs-stat-label">Total Bookings</div>
                <div class="rs-stat-value">{{ number_format($stats['total_bookings']) }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-sales">
            <div class="rs-stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <div>
                <div class="rs-stat-label">Total Sales</div>
                <div class="rs-stat-value">৳{{ number_format($stats['total_sales'], 2) }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-earning">
            <div class="rs-stat-icon"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="rs-stat-label">Your Earnings</div>
                <div class="rs-stat-value">৳{{ number_format($stats['vendor_earning'], 2) }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-rate">
            <div class="rs-stat-icon"><i class="bi bi-percent"></i></div>
            <div>
                <div class="rs-stat-label">Commission Rate</div>
                <div class="rs-stat-value">{{ number_format($stats['commission_rate'], 2) }}%</div>
            </div>
        </div>

    </div>


    {{-- TABLE --}}

    <div class="rs-card">

        <div class="rs-toolbar">

            <div>
                <h2>Commission History</h2>
                <span>Your booking commission records</span>
            </div>

            <form action="{{ route('vendor.commissions.index') }}" method="GET" class="rs-search">
                <input type="text" name="search" placeholder="Search booking, customer or tour..." value="{{ request('search') }}">
                <button type="submit"><i class="bi bi-search"></i> Search</button>
            </form>

        </div>


        <div class="table-responsive">

            <table class="rs-table">

                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Booking</th>
                        <th>Customer</th>
                        <th>Tour</th>
                        <th>Tour Date</th>
                        <th>Total Amount</th>
                        <th>Commission</th>
                        <th>Your Earning</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($commissions as $commission)

                        @php $booking = $commission->booking; @endphp

                        <tr>

                            <td class="ps-4">{{ $commissions->firstItem() + $loop->index }}</td>

                            <td>
                                @if($booking)
                                    <a href="{{ route('vendor.commissions.show', $commission->id) }}" class="rs-link">{{ $booking->booking_code ?? 'N/A' }}</a>
                                @else
                                    <span style="color:var(--rs-muted);">N/A</span>
                                @endif
                            </td>

                            <td>
                                @if($booking?->user)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rs-avatar"><i class="bi bi-person"></i></div>
                                        <div>
                                            <div class="fw-semibold">{{ $booking->user->name }}</div>
                                            <small style="color:var(--rs-muted);">{{ $booking->user->email }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span style="color:var(--rs-muted);">N/A</span>
                                @endif
                            </td>

                            <td>
                                @if($booking?->tour)
                                    <span class="fw-semibold">{{ $booking->tour->title }}</span>
                                @else
                                    <span style="color:var(--rs-muted);">N/A</span>
                                @endif
                            </td>

                            <td>
                                @if($booking?->tourDate)
                                    {{ optional($booking->tourDate->date)->format('d M, Y') ?? 'N/A' }}
                                @else
                                    <span style="color:var(--rs-muted);">N/A</span>
                                @endif
                            </td>

                            <td class="fw-semibold">৳{{ number_format($commission->total_amount ?? 0, 2) }}</td>

                            <td><span class="rs-badge rs-badge-danger">{{ number_format($commission->commission_rate ?? $stats['commission_rate'], 2) }}%</span></td>

                            <td class="fw-bold" style="color:#86efac;">৳{{ number_format($commission->vendor_earning ?? 0, 2) }}</td>

                            <td class="text-end pe-4">
                                <a href="{{ route('vendor.commissions.show', $commission->id) }}" class="rs-view-btn" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9">
                                <div class="rs-empty">
                                    <i class="bi bi-receipt"></i>
                                    <div class="rs-empty-title">No Commission Records Found</div>
                                    <div style="font-size:.78rem;">
                                        @if(request('search'))
                                            No commission records matched your search.
                                        @else
                                            You don't have any commission records yet.
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($commissions->hasPages())

            <div class="rs-footer">
                <div class="rs-count">Showing {{ $commissions->firstItem() }} to {{ $commissions->lastItem() }} of {{ $commissions->total() }} results</div>
                <div>{{ $commissions->links() }}</div>
            </div>

        @endif

    </div>

</div>

@endsection