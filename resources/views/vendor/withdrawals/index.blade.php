@extends('layouts.vendor')

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
.rs-header-content { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.rs-title { font-size: 1.5rem; font-weight: 700; background: linear-gradient(90deg, #fff, #a5b4fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.rs-subtitle { color: rgba(255,255,255,.45); font-size: .82rem; margin-top: 5px; }

.rs-btn-ghost { display: inline-flex; align-items: center; gap: 8px; border: 1px solid rgba(255,255,255,.12); background: rgba(255,255,255,.04); color: #e2e8f0; border-radius: 10px; padding: 10px 16px; font-size: .82rem; font-weight: 600; text-decoration: none; white-space: nowrap; }
.rs-btn-ghost:hover { background: rgba(255,255,255,.09); color: #fff; }

.rs-wrap .alert { background: var(--rs-surface); border: 1px solid var(--rs-border); color: var(--rs-text); border-radius: 12px; font-size: .84rem; box-shadow: var(--rs-shadow); }
.rs-wrap .alert-success { border-left: 3px solid var(--rs-success); }
.rs-wrap .alert-danger { border-left: 3px solid var(--rs-danger); }
.rs-wrap .alert ul { padding-left: 18px; margin: 6px 0 0; }

.rs-balance-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 22px; }
@media (max-width: 650px) { .rs-balance-2 { grid-template-columns: 1fr; } }
.rs-balance-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); padding: 18px 20px; box-shadow: var(--rs-shadow); display: flex; align-items: center; gap: 14px; }
.rs-balance-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
.rs-balance-icon.avail { background: rgba(99,102,241,.12); color: #a5b4fc; }
.rs-balance-icon.pending { background: rgba(245,158,11,.12); color: #fcd34d; }
.rs-balance-card small { color: var(--rs-muted); font-size: .76rem; }
.rs-balance-card .val { font-size: 1.3rem; font-weight: 700; font-family: 'JetBrains Mono', monospace; margin-top: 3px; }

.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; margin-bottom: 20px; }
.rs-card-head { padding: 17px 22px; border-bottom: 1px solid var(--rs-border); }
.rs-card-head h2 { font-size: .95rem; font-weight: 700; margin: 0 0 3px; }
.rs-card-head span { font-size: .74rem; color: var(--rs-muted); }
.rs-card-body { padding: 22px; }

.rs-field { margin-bottom: 0; }
.rs-label { display: block; font-size: .78rem; font-weight: 600; color: #cbd5e1; margin-bottom: 7px; }
.rs-label .req { color: var(--rs-danger); }

.rs-input, .rs-select {
    width: 100%; background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-text);
    border-radius: 9px; padding: 11px 13px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .84rem;
    outline: none; transition: border-color .15s, box-shadow .15s;
}
.rs-input::placeholder { color: var(--rs-muted); }
.rs-input:focus, .rs-select:focus { border-color: rgba(99,102,241,.5); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.rs-input.is-invalid, .rs-select.is-invalid { border-color: rgba(239,68,68,.5); }

.rs-prefix-group { display: flex; }
.rs-prefix { display: flex; align-items: center; background: var(--rs-surface2); border: 1px solid var(--rs-border); border-right: none; color: var(--rs-muted); padding: 0 12px; border-radius: 9px 0 0 9px; font-size: .85rem; }
.rs-prefix-group .rs-input { border-radius: 0 9px 9px 0; }

.rs-error { color: #fca5a5; font-size: .72rem; margin-top: 5px; }
.rs-help { color: var(--rs-muted); font-size: .72rem; margin-top: 5px; }

.rs-btn-submit { display: inline-flex; align-items: center; gap: 8px; border: none; background: linear-gradient(135deg, var(--rs-indigo), var(--rs-purple)); color: #fff; border-radius: 10px; padding: 11px 22px; font-size: .84rem; font-weight: 600; box-shadow: 0 8px 22px rgba(99,102,241,.28); }
.rs-btn-submit:hover { color: #fff; }
.rs-btn-submit:disabled { opacity: .5; cursor: not-allowed; box-shadow: none; }

.rs-table { width: 100%; min-width: 800px; border-collapse: collapse; }
.rs-table thead tr { background: var(--rs-surface2); border-bottom: 1px solid var(--rs-border); }
.rs-table th { padding: 13px 17px; text-align: left; color: var(--rs-muted); font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; white-space: nowrap; }
.rs-table td { padding: 14px 17px; border-bottom: 1px solid var(--rs-border); font-size: .79rem; vertical-align: middle; color: var(--rs-text); }
.rs-table tbody tr:hover { background: rgba(255,255,255,.02); }
.rs-table tbody tr:last-child td { border-bottom: none; }

.rs-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 9px; border-radius: 7px; font-size: .68rem; font-weight: 700; }
.rs-badge-danger    { background: rgba(239,68,68,.1);  color: #fca5a5; border: 1px solid rgba(239,68,68,.18); }
.rs-badge-warning   { background: rgba(245,158,11,.1); color: #fcd34d; border: 1px solid rgba(245,158,11,.18); }
.rs-badge-primary   { background: rgba(99,102,241,.1); color: #a5b4fc; border: 1px solid rgba(99,102,241,.18); }
.rs-badge-info      { background: rgba(14,165,233,.1); color: #7dd3fc; border: 1px solid rgba(14,165,233,.18); }
.rs-badge-success   { background: rgba(34,197,94,.1);  color: #86efac; border: 1px solid rgba(34,197,94,.18); }
.rs-badge-secondary { background: rgba(148,163,184,.1); color: #cbd5e1; border: 1px solid rgba(148,163,184,.18); }

.rs-empty { text-align: center; padding: 60px 20px; color: var(--rs-muted); }
.rs-empty i { font-size: 2.4rem; opacity: .25; margin-bottom: 12px; display: block; }
.rs-empty-title { color: var(--rs-text); font-size: .95rem; font-weight: 700; margin-bottom: 6px; }

.rs-pagination { padding: 14px 18px; border-top: 1px solid var(--rs-border); }
.rs-pagination .pagination { margin: 0; }
.rs-pagination .page-link { background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-muted); font-size: .75rem; }
.rs-pagination .page-link:hover { background: rgba(255,255,255,.07); color: var(--rs-text); }
.rs-pagination .page-item.active .page-link { background: var(--rs-indigo); border-color: var(--rs-indigo); color: #fff; }

</style>


<div class="rs-wrap">


    {{-- HEADER --}}

    <div class="rs-header">

        <div class="rs-header-content">
            <div>
                <div class="rs-title"><i class="bi bi-cash-coin me-2"></i> Withdrawals</div>
                <div class="rs-subtitle">Request withdrawals and track your withdrawal history.</div>
            </div>

            <a href="{{ route('vendor.wallet.index') }}" class="rs-btn-ghost">
                <i class="bi bi-wallet2"></i> My Wallet
            </a>
        </div>

    </div>


    {{-- MESSAGES --}}

    @if(session('success'))
        <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-1"></i> {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4"><i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <div class="fw-bold mb-1">Please fix the following errors:</div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- BALANCE CARDS --}}

    <div class="rs-balance-2">

        <div class="rs-balance-card">
            <div class="rs-balance-icon avail"><i class="bi bi-wallet2"></i></div>
            <div>
                <small>Available Balance</small>
                <div class="val">৳{{ number_format($wallet->balance ?? 0, 2) }}</div>
            </div>
        </div>

        <div class="rs-balance-card">
            <div class="rs-balance-icon pending"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <small>Pending Balance</small>
                <div class="val">৳{{ number_format($wallet->pending_balance ?? 0, 2) }}</div>
            </div>
        </div>

    </div>


    {{-- WITHDRAW REQUEST --}}

    <div class="rs-card">

        <div class="rs-card-head">
            <h2>Request Withdrawal</h2>
            <span>Submit a withdrawal request from your available wallet balance.</span>
        </div>

        <div class="rs-card-body">

            <form action="{{ route('vendor.withdrawals.store') }}" method="POST">

                @csrf

                <div class="row g-3 align-items-start">

                    <div class="col-md-4">
                        <label class="rs-label">Withdrawal Amount <span class="req">*</span></label>
                        <div class="rs-prefix-group">
                            <span class="rs-prefix">৳</span>
                            <input type="number" name="amount" min="1" step="0.01" value="{{ old('amount') }}"
                                   class="rs-input @error('amount') is-invalid @enderror" placeholder="Enter amount" required>
                        </div>
                        @error('amount')<div class="rs-error">{{ $message }}</div>@enderror
                        <div class="rs-help">Available: ৳{{ number_format($wallet->balance ?? 0, 2) }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="rs-label">Withdrawal Method <span class="req">*</span></label>
                        <select name="method" class="rs-select @error('method') is-invalid @enderror" required>
                            <option value="">Select Method</option>
                            <option value="bkash" @selected(old('method') === 'bkash')>bKash</option>
                            <option value="nagad" @selected(old('method') === 'nagad')>Nagad</option>
                            <option value="bank" @selected(old('method') === 'bank')>Bank Transfer</option>
                        </select>
                        @error('method')<div class="rs-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="rs-label">Account Details <span class="req">*</span></label>
                        <input type="text" name="account_details" value="{{ old('account_details') }}"
                               class="rs-input @error('account_details') is-invalid @enderror" placeholder="Phone / Bank account details" required>
                        @error('account_details')<div class="rs-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 mt-2">
                        <button type="submit" class="rs-btn-submit" @disabled(($wallet->balance ?? 0) <= 0)>
                            <i class="bi bi-send"></i> Submit Withdrawal Request
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- WITHDRAWAL HISTORY --}}

    <div class="rs-card mb-0">

        <div class="rs-card-head">
            <h2>Withdrawal History</h2>
            <span>Track your withdrawal requests and their current status.</span>
        </div>

        @if($withdrawals->count())

            <div class="table-responsive">

                <table class="rs-table">

                    <thead>
                        <tr>
                            <th class="ps-4">Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Account</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($withdrawals as $withdrawal)

                            <tr>

                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $withdrawal->created_at?->format('d M Y') }}</div>
                                    <small style="color:var(--rs-muted);">{{ $withdrawal->created_at?->format('h:i A') }}</small>
                                </td>

                                <td class="fw-bold">৳{{ number_format($withdrawal->amount, 2) }}</td>

                                <td>
                                    @php
                                        $mClass = match($withdrawal->method) {
                                            'bkash' => 'rs-badge-danger',
                                            'nagad' => 'rs-badge-warning',
                                            'bank' => 'rs-badge-primary',
                                            default => 'rs-badge-secondary',
                                        };
                                    @endphp
                                    <span class="rs-badge {{ $mClass }}">{{ $withdrawal->method === 'bkash' ? 'bKash' : ($withdrawal->method === 'nagad' ? 'Nagad' : ($withdrawal->method === 'bank' ? 'Bank' : ucfirst($withdrawal->method ?? 'Unknown'))) }}</span>
                                </td>

                                <td style="color:var(--rs-muted);" title="{{ $withdrawal->account_details }}">
                                    {{ \Illuminate\Support\Str::limit($withdrawal->account_details, 30) }}
                                </td>

                                <td>
                                    @php
                                        $wMap = [
                                            'pending' => ['rs-badge-warning', 'bi-clock', 'Pending'],
                                            'approved' => ['rs-badge-info', 'bi-check-circle', 'Approved'],
                                            'completed' => ['rs-badge-success', 'bi-check2-all', 'Completed'],
                                            'rejected' => ['rs-badge-danger', 'bi-x-circle', 'Rejected'],
                                        ];
                                        [$wClass, $wIcon, $wLabel] = $wMap[$withdrawal->status] ?? ['rs-badge-secondary', 'bi-question-circle', ucfirst($withdrawal->status ?? 'Unknown')];
                                    @endphp
                                    <span class="rs-badge {{ $wClass }}"><i class="bi {{ $wIcon }}"></i> {{ $wLabel }}</span>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            @if($withdrawals->hasPages())
                <div class="rs-pagination">
                    {{ $withdrawals->links() }}
                </div>
            @endif

        @else

            <div class="rs-empty">
                <i class="bi bi-cash-stack"></i>
                <div class="rs-empty-title">No Withdrawal Requests</div>
                <div style="font-size:.78rem;">Your withdrawal history will appear here.</div>
            </div>

        @endif

    </div>

</div>

@endsection