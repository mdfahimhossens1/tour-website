@extends('layouts.vendor')

@section('title', 'Commission Details')

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

.rs-btn-back { width: 34px; height: 34px; border-radius: 9px; border: 1px solid rgba(255,255,255,.15); background: rgba(255,255,255,.05); color: #e2e8f0; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; }
.rs-btn-back:hover { background: rgba(255,255,255,.1); color: #fff; }

.rs-booking-pill { display: inline-flex; align-items: center; gap: 8px; background: rgba(99,102,241,.12); border: 1px solid rgba(99,102,241,.25); color: #c7d2fe; border-radius: 999px; padding: 8px 16px; font-size: .82rem; font-weight: 700; }
.rs-booking-pill span:first-child { color: rgba(255,255,255,.5); font-weight: 400; }

.rs-detail-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start; }
@media (max-width: 991.98px) { .rs-detail-grid { grid-template-columns: 1fr; } }

.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; margin-bottom: 20px; }
.rs-card-head { padding: 16px 22px; border-bottom: 1px solid var(--rs-border); }
.rs-card-head h2 { font-size: .9rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px; color: var(--rs-text); }
.rs-card-head h2 i { color: #a5b4fc; }
.rs-card-body { padding: 22px; }

/* SUMMARY BOXES */
.rs-summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
@media (max-width: 650px) { .rs-summary-grid { grid-template-columns: 1fr; } }
.rs-summary-box { background: var(--rs-surface2); border: 1px solid var(--rs-border); border-radius: 12px; padding: 16px 18px; }
.rs-summary-box small { display: block; color: var(--rs-muted); font-size: .7rem; margin-bottom: 6px; }
.rs-summary-box .val { font-size: 1.25rem; font-weight: 700; font-family: 'JetBrains Mono', monospace; }
.rs-summary-box .val.danger { color: #fca5a5; }
.rs-summary-box .val.success { color: #86efac; }

/* INFO GRID WITH ICON */
.rs-icon-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
@media (max-width: 575.98px) { .rs-icon-info-grid { grid-template-columns: 1fr; } }
.rs-icon-info { display: flex; gap: 12px; }
.rs-icon-info .ic { width: 38px; height: 38px; border-radius: 10px; background: rgba(99,102,241,.12); color: #a5b4fc; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1rem; }
.rs-icon-info small { display: block; color: var(--rs-muted); font-size: .7rem; margin-bottom: 3px; }
.rs-icon-info strong { font-size: .85rem; font-weight: 600; }

.rs-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 7px; font-size: .7rem; font-weight: 700; }
.rs-badge-success { background: rgba(34,197,94,.12); color: #86efac; border: 1px solid rgba(34,197,94,.22); }
.rs-badge-primary { background: rgba(99,102,241,.12); color: #a5b4fc; border: 1px solid rgba(99,102,241,.22); }
.rs-badge-danger  { background: rgba(239,68,68,.12); color: #fca5a5; border: 1px solid rgba(239,68,68,.22); }
.rs-badge-warning { background: rgba(245,158,11,.12); color: #fcd34d; border: 1px solid rgba(245,158,11,.22); }

.rs-empty-mini { text-align: center; padding: 40px 20px; color: var(--rs-muted); }
.rs-empty-mini i { font-size: 1.8rem; opacity: .3; margin-bottom: 10px; display: block; }

/* CUSTOMER CARD */
.rs-customer-row { display: flex; align-items: center; gap: 16px; }
.rs-avatar-lg { width: 58px; height: 58px; border-radius: 50%; background: rgba(99,102,241,.12); color: #a5b4fc; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.3rem; }
.rs-customer-row h3 { font-size: 1rem; font-weight: 700; margin: 0 0 4px; }
.rs-customer-row .line { color: var(--rs-muted); font-size: .8rem; margin-top: 2px; }

/* SIDEBAR */
.rs-side-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; margin-bottom: 20px; }
.rs-side-head { padding: 14px 18px; border-bottom: 1px solid var(--rs-border); font-size: .85rem; font-weight: 700; display: flex; align-items: center; gap: 8px; }
.rs-side-body { padding: 18px; }

.rs-price-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: .82rem; }
.rs-price-row span:first-child { color: var(--rs-muted); }
.rs-price-row.total { border-top: 1px solid var(--rs-border); margin-top: 6px; padding-top: 12px; font-weight: 700; font-size: 1rem; }

.rs-vendor-item { margin-bottom: 14px; }
.rs-vendor-item:last-child { margin-bottom: 0; }
.rs-vendor-item small { display: block; color: var(--rs-muted); font-size: .7rem; margin-bottom: 4px; }
.rs-vendor-item strong { font-size: .85rem; }

.rs-btn-ghost { display: flex; align-items: center; justify-content: center; gap: 8px; border: 1px solid rgba(255,255,255,.12); background: rgba(255,255,255,.04); color: #e2e8f0; border-radius: 10px; padding: 11px 16px; font-size: .84rem; font-weight: 600; text-decoration: none; width: 100%; transition: all .2s ease; }
.rs-btn-ghost:hover { background: rgba(255,255,255,.09); color: #fff; }

</style>


<div class="rs-wrap">


    {{-- HEADER --}}

    <div class="rs-header">

        <div class="rs-header-content">

            <div>
                <div class="rs-title-row">
                    <a href="{{ route('vendor.commissions.index') }}" class="rs-btn-back"><i class="bi bi-arrow-left"></i></a>
                    <div class="rs-title"><i class="bi bi-receipt me-2"></i> Commission Details</div>
                </div>
                <div class="rs-subtitle">View complete information about this commission.</div>
            </div>

            @if($commission->booking)
                <div class="rs-booking-pill">
                    <span>Booking:</span> {{ $commission->booking->booking_code ?? 'N/A' }}
                </div>
            @endif

        </div>

    </div>


    {{-- MAIN CONTENT --}}

    <div class="rs-detail-grid">

        {{-- LEFT COLUMN --}}

        <div>

            {{-- COMMISSION SUMMARY --}}
            <div class="rs-card">
                <div class="rs-card-head"><h2><i class="bi bi-receipt"></i> Commission Summary</h2></div>
                <div class="rs-card-body">

                    <div class="rs-summary-grid">

                        <div class="rs-summary-box">
                            <small>Total Booking Amount</small>
                            <div class="val">৳{{ number_format($commission->total_amount ?? 0, 2) }}</div>
                        </div>

                        <div class="rs-summary-box">
                            <small>Commission Rate</small>
                            <div class="val danger">{{ number_format($commission->commission_rate ?? $vendor->commission_rate ?? 0, 2) }}%</div>
                        </div>

                        <div class="rs-summary-box">
                            <small>Your Earning</small>
                            <div class="val success">৳{{ number_format($commission->vendor_earning ?? 0, 2) }}</div>
                        </div>

                    </div>

                </div>
            </div>


            {{-- BOOKING INFORMATION --}}
            <div class="rs-card">
                <div class="rs-card-head"><h2><i class="bi bi-calendar-check"></i> Booking Information</h2></div>
                <div class="rs-card-body">

                    @if($commission->booking)

                        <div class="rs-icon-info-grid">

                            <div class="rs-icon-info">
                                <div class="ic"><i class="bi bi-upc-scan"></i></div>
                                <div><small>Booking Code</small><strong>{{ $commission->booking->booking_code ?? 'N/A' }}</strong></div>
                            </div>

                            <div class="rs-icon-info">
                                <div class="ic"><i class="bi bi-calendar-event"></i></div>
                                <div><small>Booking Date</small><strong>{{ $commission->booking->created_at ? $commission->booking->created_at->format('d M, Y h:i A') : 'N/A' }}</strong></div>
                            </div>

                            <div class="rs-icon-info">
                                <div class="ic"><i class="bi bi-map"></i></div>
                                <div><small>Tour</small><strong>{{ $commission->booking->tour->title ?? 'N/A' }}</strong></div>
                            </div>

                            <div class="rs-icon-info">
                                <div class="ic"><i class="bi bi-calendar3"></i></div>
                                <div><small>Tour Date</small><strong>{{ $commission->booking->tourDate?->date ? \Carbon\Carbon::parse($commission->booking->tourDate->date)->format('d M, Y') : 'N/A' }}</strong></div>
                            </div>

                            <div class="rs-icon-info">
                                <div class="ic"><i class="bi bi-people"></i></div>
                                <div><small>Travelers</small><strong>{{ $commission->booking->person_count ?? $commission->booking->travelers_count ?? 'N/A' }}</strong></div>
                            </div>

                            <div class="rs-icon-info">
                                <div class="ic"><i class="bi bi-info-circle"></i></div>
                                <div>
                                    <small>Booking Status</small>
                                    @php
                                        $status = strtolower($commission->booking->status ?? 'pending');
                                        $statusClass = match($status) {
                                            'confirmed' => 'rs-badge-success',
                                            'completed' => 'rs-badge-primary',
                                            'cancelled' => 'rs-badge-danger',
                                            default => 'rs-badge-warning',
                                        };
                                    @endphp
                                    <span class="rs-badge {{ $statusClass }}">{{ ucfirst($status) }}</span>
                                </div>
                            </div>

                        </div>

                    @else

                        <div class="rs-empty-mini">
                            <i class="bi bi-calendar-x"></i>
                            <p class="mb-0">Booking information is not available.</p>
                        </div>

                    @endif

                </div>
            </div>


            {{-- CUSTOMER INFORMATION --}}
            <div class="rs-card">
                <div class="rs-card-head"><h2><i class="bi bi-person-circle"></i> Customer Information</h2></div>
                <div class="rs-card-body">

                    @if($commission->booking?->user)

                        <div class="rs-customer-row">
                            <div class="rs-avatar-lg"><i class="bi bi-person"></i></div>
                            <div>
                                <h3>{{ $commission->booking->user->name }}</h3>
                                <div class="line"><i class="bi bi-envelope me-1"></i>{{ $commission->booking->user->email }}</div>
                                @if($commission->booking->user->phone)
                                    <div class="line"><i class="bi bi-telephone me-1"></i>{{ $commission->booking->user->phone }}</div>
                                @endif
                            </div>
                        </div>

                    @else

                        <p style="color:var(--rs-muted);font-size:.82rem;margin:0;">Customer information is not available.</p>

                    @endif

                </div>
            </div>


            {{-- TRANSACTION INFORMATION --}}
            <div class="rs-card">
                <div class="rs-card-head"><h2><i class="bi bi-credit-card"></i> Payment / Transaction Information</h2></div>
                <div class="rs-card-body">

                    @if($commission->booking?->transaction)

                        @php $transaction = $commission->booking->transaction; @endphp

                        <div class="rs-icon-info-grid">

                            <div class="rs-info-item"><small style="display:block;color:var(--rs-muted);font-size:.7rem;margin-bottom:4px;">Transaction ID</small><strong>{{ $transaction->transaction_id ?? $transaction->trx_id ?? 'N/A' }}</strong></div>
                            <div class="rs-info-item"><small style="display:block;color:var(--rs-muted);font-size:.7rem;margin-bottom:4px;">Payment Method</small><strong>{{ ucfirst($transaction->payment_method ?? 'N/A') }}</strong></div>
                            <div class="rs-info-item"><small style="display:block;color:var(--rs-muted);font-size:.7rem;margin-bottom:4px;">Transaction Amount</small><strong>৳{{ number_format($transaction->amount ?? $commission->total_amount ?? 0, 2) }}</strong></div>

                            <div class="rs-info-item">
                                <small style="display:block;color:var(--rs-muted);font-size:.7rem;margin-bottom:4px;">Transaction Status</small>
                                @php
                                    $transactionStatus = strtolower($transaction->status ?? 'pending');
                                    $tClass = in_array($transactionStatus, ['paid','completed','success']) ? 'rs-badge-success' : (in_array($transactionStatus, ['failed','cancelled']) ? 'rs-badge-danger' : 'rs-badge-warning');
                                @endphp
                                <span class="rs-badge {{ $tClass }}">{{ ucfirst($transactionStatus) }}</span>
                            </div>

                            <div class="rs-info-item"><small style="display:block;color:var(--rs-muted);font-size:.7rem;margin-bottom:4px;">Transaction Date</small><strong>{{ $transaction->created_at ? $transaction->created_at->format('d M, Y h:i A') : 'N/A' }}</strong></div>

                        </div>

                    @else

                        <div class="rs-empty-mini">
                            <i class="bi bi-credit-card-2-front"></i>
                            <p class="mb-0">No transaction information available.</p>
                        </div>

                    @endif

                </div>
            </div>

        </div>


        {{-- RIGHT COLUMN --}}

        <div>

            {{-- EARNINGS BREAKDOWN --}}
            <div class="rs-side-card">
                <div class="rs-side-head"><i class="bi bi-wallet2" style="color:#86efac;"></i> Earnings Breakdown</div>
                <div class="rs-side-body">
                    <div class="rs-price-row"><span>Total Amount</span><span class="fw-semibold">৳{{ number_format($commission->total_amount ?? 0, 2) }}</span></div>
                    <div class="rs-price-row"><span>Commission Rate</span><span class="fw-semibold" style="color:#fca5a5;">{{ number_format($commission->commission_rate ?? $vendor->commission_rate ?? 0, 2) }}%</span></div>
                    <div class="rs-price-row total"><span>Your Earning</span><span style="color:#86efac;">৳{{ number_format($commission->vendor_earning ?? 0, 2) }}</span></div>
                </div>
            </div>


            {{-- VENDOR ACCOUNT --}}
            <div class="rs-side-card">
                <div class="rs-side-head"><i class="bi bi-shop" style="color:#a5b4fc;"></i> Your Vendor Account</div>
                <div class="rs-side-body">

                    <div class="rs-vendor-item"><small>Vendor Name</small><strong>{{ $vendor->name ?? 'N/A' }}</strong></div>
                    <div class="rs-vendor-item"><small>Commission Rate</small><strong style="color:#a5b4fc;">{{ number_format($vendor->commission_rate ?? 0, 2) }}%</strong></div>

                    @if(isset($vendor->email))
                        <div class="rs-vendor-item"><small>Email</small><span style="font-size:.82rem;">{{ $vendor->email }}</span></div>
                    @endif

                    @if(isset($vendor->phone))
                        <div class="rs-vendor-item"><small>Phone</small><span style="font-size:.82rem;">{{ $vendor->phone }}</span></div>
                    @endif

                </div>
            </div>


            <a href="{{ route('vendor.commissions.index') }}" class="rs-btn-ghost">
                <i class="bi bi-arrow-left"></i> Back to Commissions
            </a>

        </div>

    </div>

</div>

@endsection