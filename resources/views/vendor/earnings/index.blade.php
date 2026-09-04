@extends('layouts.vendor')

@section('title', 'Earnings')

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

.rs-btn-primary {
    display: inline-flex; align-items: center; gap: 6px;
    background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border: none;
    border-radius: 9px; padding: 10px 18px; font-size: .82rem; font-weight: 700; text-decoration: none;
}
.rs-btn-primary:hover { opacity: .92; color: #fff; }

.rs-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 18px; }
.rs-stat { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); padding: 20px; box-shadow: var(--rs-shadow); }
.rs-stat-top { display: flex; justify-content: space-between; align-items: flex-start; }
.rs-stat-label { color: var(--rs-muted); font-size: .78rem; margin-bottom: 5px; }
.rs-stat-value { font-size: 1.4rem; font-weight: 700; font-family: 'JetBrains Mono', monospace; color: var(--rs-text); }
.rs-stat-sub { color: var(--rs-muted); font-size: .72rem; margin-top: 8px; display: block; }
.rs-stat-icon { width: 46px; height: 46px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
.rs-icon-primary { background: rgba(99,102,241,.12); color: #a5b4fc; }
.rs-icon-success { background: rgba(34,197,94,.12);  color: #86efac; }
.rs-icon-info    { background: rgba(56,189,248,.12); color: #7dd3fc; }
.rs-icon-warning { background: rgba(245,158,11,.12); color: #fcd34d; }

.rs-mini-row { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 22px; }
.rs-mini { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); padding: 18px 20px; box-shadow: var(--rs-shadow); display: flex; align-items: center; gap: 14px; }
.rs-mini-label { color: var(--rs-muted); font-size: .72rem; }
.rs-mini-value { font-size: 1.15rem; font-weight: 700; color: var(--rs-text); font-family: 'JetBrains Mono', monospace; margin-top: 2px; }

.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; }
.rs-toolbar { padding: 17px 20px; border-bottom: 1px solid var(--rs-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
.rs-toolbar h2 { font-size: .95rem; font-weight: 700; margin: 0 0 3px; color: var(--rs-text); }
.rs-toolbar span.rs-sub { font-size: .74rem; color: var(--rs-muted); }

.rs-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 9px; border-radius: 7px; font-size: .68rem; font-weight: 700; }
.rs-badge-success   { background: rgba(34,197,94,.1);  color: #86efac; border: 1px solid rgba(34,197,94,.18); }
.rs-badge-warning   { background: rgba(245,158,11,.1); color: #fcd34d; border: 1px solid rgba(245,158,11,.18); }
.rs-badge-danger    { background: rgba(239,68,68,.1);  color: #fca5a5; border: 1px solid rgba(239,68,68,.18); }
.rs-badge-muted     { background: rgba(255,255,255,.04); color: var(--rs-muted); border: 1px solid var(--rs-border); }

.rs-table { width: 100%; min-width: 750px; border-collapse: collapse; }
.rs-table thead tr { background: var(--rs-surface2); border-bottom: 1px solid var(--rs-border); }
.rs-table th { padding: 13px 17px; text-align: left; color: var(--rs-muted); font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; white-space: nowrap; }
.rs-table td { padding: 15px 17px; border-bottom: 1px solid var(--rs-border); font-size: .79rem; vertical-align: middle; color: var(--rs-text); }
.rs-table tbody tr:hover { background: rgba(255,255,255,.02); }
.rs-table tbody tr:last-child td { border-bottom: none; }

.rs-txn-icon { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(34,197,94,.12); color: #86efac; flex-shrink: 0; }

.rs-empty { text-align: center; padding: 70px 20px; color: var(--rs-muted); }
.rs-empty i { font-size: 2.4rem; opacity: .25; margin-bottom: 12px; display: block; }
.rs-empty-title { color: var(--rs-text); font-size: .95rem; font-weight: 700; margin-bottom: 6px; }

.rs-footer { padding: 14px 20px; border-top: 1px solid var(--rs-border); }
.rs-footer .pagination { margin: 0; }
.rs-footer .page-link { background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-muted); font-size: .75rem; }
.rs-footer .page-link:hover { background: rgba(255,255,255,.07); color: var(--rs-text); }
.rs-footer .page-item.active .page-link { background: var(--rs-indigo); border-color: var(--rs-indigo); color: #fff; }

@media (max-width: 900px) { .rs-stats { grid-template-columns: 1fr 1fr; } .rs-mini-row { grid-template-columns: 1fr; } }
@media (max-width: 600px) { .rs-stats { grid-template-columns: 1fr; } }

</style>


<div class="rs-wrap">


    {{-- HEADER --}}

    <div class="rs-header">
        <div class="rs-header-content">
            <div>
                <div class="rs-title"><i class="bi bi-cash-coin me-2"></i> Earnings</div>
                <div class="rs-subtitle">Track your resort and tour earnings, wallet balance and transactions.</div>
            </div>
            <div>
                <a href="{{ route('vendor.withdrawals.index') }}" class="rs-btn-primary">
                    <i class="bi bi-wallet2"></i> Withdraw Money
                </a>
            </div>
        </div>
    </div>


    {{-- MESSAGES --}}

    @if(session('success'))
        <div class="alert alert-success mb-4">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
        </div>
    @endif


    {{-- SUMMARY CARDS --}}

    <div class="rs-stats">

        <div class="rs-stat">
            <div class="rs-stat-top">
                <div>
                    <div class="rs-stat-label">Available Balance</div>
                    <div class="rs-stat-value">৳{{ number_format($availableBalance, 2) }}</div>
                </div>
                <div class="rs-stat-icon rs-icon-primary"><i class="bi bi-wallet2"></i></div>
            </div>
            <span class="rs-stat-sub">Available for withdrawal</span>
        </div>

        <div class="rs-stat">
            <div class="rs-stat-top">
                <div>
                    <div class="rs-stat-label">Total Earned</div>
                    <div class="rs-stat-value">৳{{ number_format($totalEarned, 2) }}</div>
                </div>
                <div class="rs-stat-icon rs-icon-success"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
            <span class="rs-stat-sub">Completed earnings</span>
        </div>

        <div class="rs-stat">
            <div class="rs-stat-top">
                <div>
                    <div class="rs-stat-label">This Month</div>
                    <div class="rs-stat-value">৳{{ number_format($monthlyEarning, 2) }}</div>
                </div>
                <div class="rs-stat-icon rs-icon-info"><i class="bi bi-calendar3"></i></div>
            </div>
            <span class="rs-stat-sub">Earnings this month</span>
        </div>

        <div class="rs-stat">
            <div class="rs-stat-top">
                <div>
                    <div class="rs-stat-label">Total Withdrawn</div>
                    <div class="rs-stat-value">৳{{ number_format($totalWithdrawn, 2) }}</div>
                </div>
                <div class="rs-stat-icon rs-icon-warning"><i class="bi bi-cash-stack"></i></div>
            </div>
            <span class="rs-stat-sub">Successfully withdrawn</span>
        </div>

    </div>


    {{-- SECONDARY SUMMARY --}}

    <div class="rs-mini-row">

        <div class="rs-mini">
            <div class="rs-stat-icon rs-icon-success"><i class="bi bi-calendar-day"></i></div>
            <div>
                <div class="rs-mini-label">Today's Earnings</div>
                <div class="rs-mini-value">৳{{ number_format($todayEarning, 2) }}</div>
            </div>
        </div>

        <div class="rs-mini">
            <div class="rs-stat-icon rs-icon-warning"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="rs-mini-label">Pending Balance</div>
                <div class="rs-mini-value">৳{{ number_format($pendingBalance, 2) }}</div>
            </div>
        </div>

        <div class="rs-mini">
            <div class="rs-stat-icon rs-icon-primary"><i class="bi bi-bank"></i></div>
            <div>
                <div class="rs-mini-label">Wallet Balance</div>
                <div class="rs-mini-value">৳{{ number_format($wallet->balance ?? 0, 2) }}</div>
            </div>
        </div>

    </div>


    {{-- TRANSACTIONS --}}

    <div class="rs-card">

        <div class="rs-toolbar">
            <div>
                <h2>Earning Transactions</h2>
                <span class="rs-sub">Your completed earning history.</span>
            </div>
            <span class="rs-badge rs-badge-muted">{{ $transactions->total() }} Transactions</span>
        </div>

        @if($transactions->count())

            <div class="table-responsive">

                <table class="rs-table">

                    <thead>
                        <tr>
                            <th class="ps-4">Transaction</th>
                            <th>Booking</th>
                            <th>Tour</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($transactions as $transaction)

                            <tr>

                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rs-txn-icon"><i class="bi bi-arrow-down-left"></i></div>
                                        <div>
                                            <div class="fw-semibold">#{{ $transaction->id }}</div>
                                            <small style="color:var(--rs-muted);">{{ $transaction->note ?? 'Earning' }}</small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if($transaction->booking)
                                        <span class="fw-semibold">#{{ $transaction->booking->id }}</span>
                                    @else
                                        <span style="color:var(--rs-muted);">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($transaction->booking && $transaction->booking->tour)
                                        {{ $transaction->booking->tour->title }}
                                    @else
                                        <span style="color:var(--rs-muted);">—</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="fw-bold" style="color:#86efac;">+৳{{ number_format($transaction->amount, 2) }}</span>
                                </td>

                                <td>
                                    @if($transaction->status === 'completed')
                                        <span class="rs-badge rs-badge-success"><i class="bi bi-check-circle"></i> Completed</span>
                                    @elseif($transaction->status === 'pending')
                                        <span class="rs-badge rs-badge-warning"><i class="bi bi-clock"></i> Pending</span>
                                    @else
                                        <span class="rs-badge rs-badge-danger">{{ ucfirst($transaction->status) }}</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="fw-semibold">{{ $transaction->created_at->format('d M Y') }}</div>
                                    <small style="color:var(--rs-muted);">{{ $transaction->created_at->format('h:i A') }}</small>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="rs-empty">
                <i class="bi bi-bar-chart-line"></i>
                <div class="rs-empty-title">No earnings yet</div>
                <div style="font-size:.78rem;">Your completed booking earnings will appear here.</div>
            </div>

        @endif


        @if($transactions->hasPages())

            <div class="rs-footer">
                {{ $transactions->links() }}
            </div>

        @endif

    </div>

</div>

@endsection