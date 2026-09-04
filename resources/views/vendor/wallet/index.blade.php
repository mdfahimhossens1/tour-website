@extends('layouts.vendor')

@section('page')

<style>

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
    --rs-surface: #1a1d27; --rs-surface2: #222636; --rs-border: rgba(255,255,255,.07);
    --rs-text: #e2e8f0; --rs-muted: #64748b;
    --rs-indigo: #6366f1; --rs-purple: #8b5cf6;
    --rs-success: #22c55e; --rs-warning: #f59e0b; --rs-danger: #ef4444;
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

.rs-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 20px; }
.rs-stat { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); padding: 18px 20px; box-shadow: var(--rs-shadow); display: flex; align-items: center; gap: 14px; }
.rs-stat-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.rs-stat-balance .rs-stat-icon  { background: rgba(99,102,241,.12); color: #a5b4fc; }
.rs-stat-pending .rs-stat-icon  { background: rgba(245,158,11,.12); color: #fcd34d; }
.rs-stat-earned .rs-stat-icon   { background: rgba(34,197,94,.12);  color: #86efac; }
.rs-stat-withdrawn .rs-stat-icon{ background: rgba(239,68,68,.12);  color: #fca5a5; }
.rs-stat-label { color: var(--rs-muted); font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
.rs-stat-value { color: var(--rs-text); font-size: 1.15rem; font-family: 'JetBrains Mono', monospace; font-weight: 700; margin-top: 3px; }

.rs-summary-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 22px; }
@media (max-width: 650px) { .rs-summary-2 { grid-template-columns: 1fr; } }
.rs-summary-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); padding: 18px 20px; box-shadow: var(--rs-shadow); display: flex; align-items: center; gap: 14px; }
.rs-summary-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.rs-summary-icon.credit { background: rgba(34,197,94,.12); color: #86efac; }
.rs-summary-icon.debit  { background: rgba(239,68,68,.12); color: #fca5a5; }
.rs-summary-card small { color: var(--rs-muted); font-size: .74rem; }
.rs-summary-card .val { font-size: 1.15rem; font-weight: 700; font-family: 'JetBrains Mono', monospace; margin-top: 3px; }

.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; }
.rs-toolbar { padding: 17px 20px; border-bottom: 1px solid var(--rs-border); }
.rs-toolbar h2 { font-size: .95rem; font-weight: 700; margin: 0 0 3px; }
.rs-toolbar span { font-size: .74rem; color: var(--rs-muted); }

.rs-table { width: 100%; min-width: 800px; border-collapse: collapse; }
.rs-table thead tr { background: var(--rs-surface2); border-bottom: 1px solid var(--rs-border); }
.rs-table th { padding: 13px 17px; text-align: left; color: var(--rs-muted); font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; white-space: nowrap; }
.rs-table td { padding: 14px 17px; border-bottom: 1px solid var(--rs-border); font-size: .79rem; vertical-align: middle; color: var(--rs-text); }
.rs-table tbody tr:hover { background: rgba(255,255,255,.02); }
.rs-table tbody tr:last-child td { border-bottom: none; }

.rs-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 9px; border-radius: 7px; font-size: .68rem; font-weight: 700; }
.rs-badge-success   { background: rgba(34,197,94,.1);  color: #86efac; border: 1px solid rgba(34,197,94,.18); }
.rs-badge-danger    { background: rgba(239,68,68,.1);  color: #fca5a5; border: 1px solid rgba(239,68,68,.18); }
.rs-badge-warning   { background: rgba(245,158,11,.1); color: #fcd34d; border: 1px solid rgba(245,158,11,.18); }
.rs-badge-secondary { background: rgba(148,163,184,.1); color: #cbd5e1; border: 1px solid rgba(148,163,184,.18); }

.rs-empty { text-align: center; padding: 60px 20px; color: var(--rs-muted); }
.rs-empty i { font-size: 2.4rem; opacity: .25; margin-bottom: 12px; display: block; }
.rs-empty-title { color: var(--rs-text); font-size: .95rem; font-weight: 700; margin-bottom: 6px; }

.rs-pagination { padding: 14px 18px; border-top: 1px solid var(--rs-border); }
.rs-pagination .pagination { margin: 0; }
.rs-pagination .page-link { background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-muted); font-size: .75rem; }
.rs-pagination .page-link:hover { background: rgba(255,255,255,.07); color: var(--rs-text); }
.rs-pagination .page-item.active .page-link { background: var(--rs-indigo); border-color: var(--rs-indigo); color: #fff; }

@media (max-width: 900px) { .rs-stats { grid-template-columns: 1fr 1fr; } }
@media (max-width: 600px) { .rs-stats { grid-template-columns: 1fr; } }

</style>


<div class="rs-wrap">


    {{-- HEADER --}}

    <div class="rs-header">
        <div class="rs-header-content">
            <div class="rs-title"><i class="bi bi-wallet2 me-2"></i> My Wallet</div>
            <div class="rs-subtitle">View your earnings, wallet balance and transaction history.</div>
        </div>
    </div>


    {{-- MESSAGES --}}

    @if(session('success'))
        <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-1"></i> {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4"><i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}</div>
    @endif


    {{-- WALLET SUMMARY --}}

    <div class="rs-stats">

        <div class="rs-stat rs-stat-balance">
            <div class="rs-stat-icon"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="rs-stat-label">Available Balance</div>
                <div class="rs-stat-value">৳{{ number_format($wallet->balance ?? 0, 2) }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-pending">
            <div class="rs-stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="rs-stat-label">Pending Balance</div>
                <div class="rs-stat-value">৳{{ number_format($wallet->pending_balance ?? 0, 2) }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-earned">
            <div class="rs-stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <div>
                <div class="rs-stat-label">Total Earned</div>
                <div class="rs-stat-value">৳{{ number_format($wallet->total_earned ?? 0, 2) }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-withdrawn">
            <div class="rs-stat-icon"><i class="bi bi-arrow-up-right-circle"></i></div>
            <div>
                <div class="rs-stat-label">Total Withdrawn</div>
                <div class="rs-stat-value">৳{{ number_format($wallet->total_withdrawn ?? 0, 2) }}</div>
            </div>
        </div>

    </div>


    {{-- CREDIT / DEBIT --}}

    <div class="rs-summary-2">

        <div class="rs-summary-card">
            <div class="rs-summary-icon credit"><i class="bi bi-arrow-down-left"></i></div>
            <div>
                <small>Completed Credits</small>
                <div class="val" style="color:#86efac;">+৳{{ number_format($totalCredits ?? 0, 2) }}</div>
            </div>
        </div>

        <div class="rs-summary-card">
            <div class="rs-summary-icon debit"><i class="bi bi-arrow-up-right"></i></div>
            <div>
                <small>Completed Debits</small>
                <div class="val" style="color:#fca5a5;">-৳{{ number_format($totalDebits ?? 0, 2) }}</div>
            </div>
        </div>

    </div>


    {{-- TRANSACTION HISTORY --}}

    <div class="rs-card">

        <div class="rs-toolbar">
            <h2>Transaction History</h2>
            <span>Your latest wallet transactions.</span>
        </div>

        @if($transactions->count())

            <div class="table-responsive">

                <table class="rs-table">

                    <thead>
                        <tr>
                            <th class="ps-4">Date</th>
                            <th>Type</th>
                            <th>Booking</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Note</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($transactions as $transaction)

                            <tr>

                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $transaction->created_at?->format('d M Y') }}</div>
                                    <small style="color:var(--rs-muted);">{{ $transaction->created_at?->format('h:i A') }}</small>
                                </td>

                                <td>
                                    @if($transaction->type === 'credit')
                                        <span class="rs-badge rs-badge-success"><i class="bi bi-arrow-down-left"></i> Credit</span>
                                    @else
                                        <span class="rs-badge rs-badge-danger"><i class="bi bi-arrow-up-right"></i> Debit</span>
                                    @endif
                                </td>

                                <td>
                                    @if($transaction->booking)
                                        <span class="fw-semibold">#{{ $transaction->booking->id }}</span>
                                    @else
                                        <span style="color:var(--rs-muted);">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($transaction->type === 'credit')
                                        <span class="fw-bold" style="color:#86efac;">+৳{{ number_format($transaction->amount ?? 0, 2) }}</span>
                                    @else
                                        <span class="fw-bold" style="color:#fca5a5;">-৳{{ number_format($transaction->amount ?? 0, 2) }}</span>
                                    @endif
                                </td>

                                <td>
                                    @php
                                        $tClass = match($transaction->status) {
                                            'completed' => 'rs-badge-success',
                                            'pending' => 'rs-badge-warning',
                                            'failed' => 'rs-badge-danger',
                                            default => 'rs-badge-secondary',
                                        };
                                    @endphp
                                    <span class="rs-badge {{ $tClass }}">{{ ucfirst($transaction->status ?? 'Unknown') }}</span>
                                </td>

                                <td style="color:var(--rs-muted);" title="{{ $transaction->note }}">
                                    {{ \Illuminate\Support\Str::limit($transaction->note, 45) }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            @if($transactions->hasPages())
                <div class="rs-pagination">
                    {{ $transactions->links() }}
                </div>
            @endif

        @else

            <div class="rs-empty">
                <i class="bi bi-wallet2"></i>
                <div class="rs-empty-title">No Transactions Yet</div>
                <div style="font-size:.78rem;">Your wallet transactions will appear here.</div>
            </div>

        @endif

    </div>

</div>

@endsection