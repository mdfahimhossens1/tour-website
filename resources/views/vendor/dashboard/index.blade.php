@extends('layouts.vendor')

@section('page')

<div class="vd">

    <style>
        .vd{
            --ink:#14282D;
            --teal:#1C4B4F;
            --teal-deep:#0F3437;
            --sand:#F7F3EA;
            --sand-deep:#EFE7D4;
            --line:#E3DBC8;
            --gold:#B98B3C;
            --gold-deep:#8F6A2C;
            --coral:#B15A43;
            --text:#23393E;
            --text-soft:#6B7A7D;
            --white:#FFFFFF;
        }

        @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600;700&display=swap');

        .vd{
            font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
            color:var(--text);
            background:var(--sand);
            padding:2rem clamp(1rem,3vw,2.25rem) 3rem;
            border-radius:18px;
        }

        .vd .vd-serif{ font-family:'Fraunces','Georgia',serif; }

        /* ---------- header ---------- */
        .vd-header{
            display:flex;
            flex-wrap:wrap;
            justify-content:space-between;
            align-items:flex-end;
            gap:1rem;
            padding-bottom:1.75rem;
            margin-bottom:2rem;
            border-bottom:1px solid var(--line);
        }
        .vd-header h1{
            font-size:clamp(1.6rem,2.4vw,2.2rem);
            font-weight:500;
            color:var(--ink);
            margin:0 0 .35rem;
            line-height:1.15;
        }
        .vd-header p{
            color:var(--text-soft);
            margin:0;
            font-size:.95rem;
        }
        .vd-date{
            font-size:.8rem;
            color:var(--text-soft);
            border:1px solid var(--line);
            border-radius:999px;
            padding:.4rem .9rem;
            background:var(--white);
            white-space:nowrap;
        }

        /* ---------- alerts ---------- */
        .vd .alert{
            border:1px solid var(--line);
            border-radius:12px;
            background:var(--white);
            color:var(--text);
            font-size:.92rem;
        }
        .vd .alert-success{ border-left:4px solid var(--teal); }
        .vd .alert-danger{ border-left:4px solid var(--coral); }

        /* ---------- stat rail ---------- */
        .vd-stats{
            display:flex;
            background:var(--white);
            border:1px solid var(--line);
            border-radius:16px;
            margin-bottom:2rem;
            overflow:hidden;
        }
        .vd-stat{
            flex:1 1 0;
            padding:1.5rem 1.6rem;
            position:relative;
        }
        .vd-stat + .vd-stat{ border-left:1px solid var(--line); }
        .vd-stat-bar{
            width:28px;
            height:3px;
            border-radius:2px;
            margin-bottom:.9rem;
        }
        .vd-stat:nth-child(1) .vd-stat-bar{ background:var(--gold); }
        .vd-stat:nth-child(2) .vd-stat-bar{ background:var(--teal); }
        .vd-stat:nth-child(3) .vd-stat-bar{ background:var(--coral); }
        .vd-stat:nth-child(4) .vd-stat-bar{ background:var(--ink); }
        .vd-stat-label{
            font-size:.82rem;
            color:var(--text-soft);
            margin-bottom:.3rem;
        }
        .vd-stat-value{
            font-size:1.65rem;
            font-weight:600;
            color:var(--ink);
            font-family:'Fraunces','Georgia',serif;
        }
        .vd-stat-link{
            display:inline-flex;
            align-items:center;
            gap:.3rem;
            font-size:.8rem;
            color:var(--teal);
            text-decoration:none;
            margin-top:.85rem;
        }
        .vd-stat-link:hover{ color:var(--gold-deep); }

        @media (max-width:900px){
            .vd-stats{ flex-wrap:wrap; }
            .vd-stat{ flex:1 1 50%; }
            .vd-stat:nth-child(2){ border-left:1px solid var(--line); }
            .vd-stat:nth-child(3){ border-left:none; border-top:1px solid var(--line); }
            .vd-stat:nth-child(4){ border-top:1px solid var(--line); }
        }

        /* ---------- panels ---------- */
        .vd-panel{
            background:var(--white);
            border:1px solid var(--line);
            border-radius:16px;
            overflow:hidden;
        }
        .vd-panel-head{
            padding:1.35rem 1.5rem;
            border-bottom:1px solid var(--line);
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:1rem;
        }
        .vd-panel-head h2{
            font-family:'Fraunces','Georgia',serif;
            font-size:1.15rem;
            font-weight:500;
            color:var(--ink);
            margin:0 0 .2rem;
        }
        .vd-panel-head small{ color:var(--text-soft); font-size:.82rem; }
        .vd-link-btn{
            font-size:.8rem;
            color:var(--teal);
            border:1px solid var(--line);
            padding:.4rem .85rem;
            border-radius:999px;
            text-decoration:none;
            white-space:nowrap;
        }
        .vd-link-btn:hover{ border-color:var(--gold); color:var(--gold-deep); }

        /* ---------- tables ---------- */
        .vd .table{ margin-bottom:0; }
        .vd .table thead th{
            background:var(--sand-deep);
            color:var(--text-soft);
            font-weight:500;
            font-size:.78rem;
            border-bottom:none;
            padding:.8rem 1rem;
        }
        .vd .table tbody td{
            padding:.9rem 1rem;
            border-color:var(--line);
            font-size:.9rem;
            vertical-align:middle;
        }
        .vd .table tbody tr:hover{ background:var(--sand); }

        .vd-thumb{
            width:46px;height:46px;border-radius:10px;
            background:var(--sand-deep);
            display:flex;align-items:center;justify-content:center;
            overflow:hidden;flex-shrink:0;
        }
        .vd-thumb img{ width:100%;height:100%;object-fit:cover; }

        /* ---------- badges ---------- */
        .vd-badge{
            display:inline-block;
            font-size:.75rem;
            font-weight:500;
            padding:.3rem .7rem;
            border-radius:999px;
            border:1px solid transparent;
        }
        .vd-badge--pending{ background:#F7ECD8; color:var(--gold-deep); border-color:#E9D6A9; }
        .vd-badge--confirmed{ background:#E4EEEC; color:var(--teal-deep); border-color:#C7DBD7; }
        .vd-badge--completed{ background:#EAE6DD; color:var(--ink); border-color:#D8D0BE; }
        .vd-badge--cancelled{ background:#F3E3DE; color:var(--coral); border-color:#E7C9BF; }

        /* ---------- quick actions ---------- */
        .vd-actions{ list-style:none; margin:0; padding:0; }
        .vd-actions li + li{ border-top:1px solid var(--line); }
        .vd-actions a{
            display:flex;
            align-items:center;
            gap:.8rem;
            padding:.95rem 1.5rem;
            color:var(--text);
            text-decoration:none;
            font-size:.9rem;
            transition:background .15s ease;
        }
        .vd-actions a:hover{ background:var(--sand); color:var(--gold-deep); }
        .vd-actions .bi{ font-size:1rem; color:var(--teal); width:1.1rem; }
        .vd-actions a.is-primary{ background:var(--ink); color:var(--white); font-weight:500; }
        .vd-actions a.is-primary .bi{ color:var(--gold); }
        .vd-actions a.is-primary:hover{ background:var(--teal-deep); color:var(--white); }
        .vd-actions .bi-arrow-right{ margin-left:auto; color:var(--text-soft); }
        .vd-actions a.is-primary .bi-arrow-right{ color:var(--white); }

        /* ---------- sidebar mini blocks ---------- */
        .vd-mini{ padding:1.5rem; }
        .vd-mini + .vd-mini{ border-top:1px solid var(--line); }
        .vd-mini-label{ font-size:.8rem; color:var(--text-soft); margin-bottom:.3rem; }
        .vd-mini-value{
            font-family:'Fraunces','Georgia',serif;
            font-size:1.6rem;
            font-weight:500;
            color:var(--ink);
        }
        .vd-mini-row{
            display:flex;
            justify-content:space-between;
            padding:.55rem 0;
            font-size:.88rem;
            border-bottom:1px dashed var(--line);
        }
        .vd-mini-row:last-child{ border-bottom:none; }
        .vd-mini-row span:first-child{ color:var(--text-soft); }
        .vd-mini-row span:last-child{ font-weight:600; color:var(--ink); }
        .vd-btn-gold{
            display:block;
            text-align:center;
            background:var(--gold);
            color:var(--white);
            border-radius:10px;
            padding:.7rem;
            font-size:.88rem;
            font-weight:500;
            text-decoration:none;
            margin-top:1rem;
        }
        .vd-btn-gold:hover{ background:var(--gold-deep); color:var(--white); }

        /* ---------- empty states ---------- */
        .vd-empty{
            text-align:center;
            padding:3.5rem 1.5rem;
            color:var(--text-soft);
        }
        .vd-empty .bi{ font-size:1.8rem; color:var(--line); margin-bottom:.75rem; display:block; }
        .vd-empty strong{ display:block; color:var(--ink); font-size:.95rem; margin-bottom:.25rem; }
        .vd-empty small{ font-size:.85rem; }

        .vd-stack > * + *{ margin-top:1.5rem; }
    </style>


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="vd-header">

        <div>
            <h1 class="vd-serif">Welcome back, {{ $vendor->business_name ?? auth()->user()->name }}</h1>
            <p>Here's how your business is doing across all your resorts.</p>
        </div>

        <div class="vd-date">
            {{ now()->format('l, d M Y') }}
        </div>

    </div>


    {{-- =========================================================
        SUCCESS / ERROR MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-1"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

    @endif


    {{-- =========================================================
        STAT RAIL
    ========================================================== --}}

    <div class="vd-stats">

        <div class="vd-stat">
            <div class="vd-stat-bar"></div>
            <div class="vd-stat-label">Total resorts</div>
            <div class="vd-stat-value">{{ $totalResorts ?? 0 }}</div>
            <a href="{{ route('vendor.resorts.index') }}" class="vd-stat-link">
                Manage resorts <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="vd-stat">
            <div class="vd-stat-bar"></div>
            <div class="vd-stat-label">Total rooms</div>
            <div class="vd-stat-value">{{ $totalRooms ?? 0 }}</div>
            <a href="{{ route('vendor.rooms.index') }}" class="vd-stat-link">
                Manage rooms <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="vd-stat">
            <div class="vd-stat-bar"></div>
            <div class="vd-stat-label">Total bookings</div>
            <div class="vd-stat-value">{{ $totalBookings ?? 0 }}</div>
            <a href="{{ route('vendor.resorts.index') }}" class="vd-stat-link">
                View bookings <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="vd-stat">
            <div class="vd-stat-bar"></div>
            <div class="vd-stat-label">Total earnings</div>
            <div class="vd-stat-value">৳{{ number_format($totalEarnings ?? 0, 2) }}</div>
            <a href="{{ route('vendor.earnings.index') }}" class="vd-stat-link">
                View earnings <i class="bi bi-arrow-right"></i>
            </a>
        </div>

    </div>


    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}

    <div class="row g-4 mb-4 align-items-start">

        {{-- =====================================================
            RECENT BOOKINGS
        ====================================================== --}}

        <div class="col-xl-8">

            <div class="vd-panel">

                <div class="vd-panel-head">
                    <div>
                        <h2>Recent bookings</h2>
                        <small>Latest bookings across your resorts</small>
                    </div>
                    <a href="{{ route('vendor.resorts.index') }}" class="vd-link-btn">View all</a>
                </div>

                @if(isset($recentBookings) && $recentBookings->count())

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-4">Booking</th>
                                    <th>Guest</th>
                                    <th>Resort</th>
                                    <th>Amount</th>
                                    <th class="pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-semibold">#{{ $booking->booking_code ?? $booking->id }}</div>
                                            <small class="text-muted">{{ optional($booking->created_at)->format('d M Y') }}</small>
                                        </td>
                                        <td>{{ $booking->user->name ?? 'Guest' }}</td>
                                        <td>
                                            {{ $booking->resort->name ?? 'N/A' }}
                                            @if($booking->room)
                                                <br><small class="text-muted">{{ $booking->room->name ?? 'Room' }}</small>
                                            @endif
                                        </td>
                                        <td class="fw-semibold">৳{{ number_format($booking->total_amount ?? 0, 2) }}</td>
                                        <td class="pe-4">
                                            @php
                                                $status = $booking->booking_status ?? 'pending';
                                                $badgeClass = match($status) {
                                                    'confirmed' => 'vd-badge--confirmed',
                                                    'completed' => 'vd-badge--completed',
                                                    'cancelled' => 'vd-badge--cancelled',
                                                    default => 'vd-badge--pending',
                                                };
                                            @endphp
                                            <span class="vd-badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @else

                    <div class="vd-empty">
                        <i class="bi bi-calendar-x"></i>
                        <strong>No bookings yet</strong>
                        <small>New bookings from guests will show up here.</small>
                    </div>

                @endif

            </div>

        </div>


        {{-- =====================================================
            SIDEBAR: QUICK ACTIONS + WALLET + BOOKING SUMMARY
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="vd-stack">

                <div class="vd-panel">
                    <div class="vd-panel-head">
                        <div><h2>Quick actions</h2></div>
                    </div>
                    <ul class="vd-actions">
                        <li>
                            <a href="{{ route('vendor.resorts.create') }}" class="is-primary">
                                <i class="bi bi-building-add"></i>
                                Add new resort
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('vendor.rooms.index') }}">
                                <i class="bi bi-door-open"></i>
                                Manage rooms
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('vendor.resorts.index') }}">
                                <i class="bi bi-calendar-check"></i>
                                Manage bookings
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('vendor.wallet.index') }}">
                                <i class="bi bi-wallet2"></i>
                                My wallet
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('vendor.withdrawals.index') }}">
                                <i class="bi bi-bank"></i>
                                Withdraw money
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('vendor.profile.index') }}">
                                <i class="bi bi-person-circle"></i>
                                My profile
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SECOND ROW: WALLET / BOOKING SUMMARY / RESORT OVERVIEW
    ========================================================== --}}

    <div class="row g-4 mb-4">

        {{-- WALLET --}}
        <div class="col-xl-4">
            <div class="vd-panel h-100">
                <div class="vd-panel-head">
                    <div><h2>Wallet</h2></div>
                    <a href="{{ route('vendor.wallet.index') }}" class="vd-link-btn">View</a>
                </div>
                <div class="vd-mini">
                    <div class="vd-mini-label">Available balance</div>
                    <div class="vd-mini-value">৳{{ number_format($wallet->balance ?? 0, 2) }}</div>

                    <div class="vd-mini-row">
                        <span>Pending</span>
                        <span>৳{{ number_format($wallet->pending_balance ?? 0, 2) }}</span>
                    </div>
                    <div class="vd-mini-row">
                        <span>Withdrawn</span>
                        <span>৳{{ number_format($wallet->total_withdrawn ?? 0, 2) }}</span>
                    </div>

                    <a href="{{ route('vendor.withdrawals.index') }}" class="vd-btn-gold">
                        <i class="bi bi-cash-coin me-1"></i> Request withdrawal
                    </a>
                </div>
            </div>
        </div>

        {{-- BOOKING SUMMARY --}}
        <div class="col-xl-4">
            <div class="vd-panel h-100">
                <div class="vd-panel-head">
                    <div><h2>Booking summary</h2></div>
                </div>
                <div class="vd-mini">
                    <div class="vd-mini-row"><span>Pending</span><span class="vd-badge vd-badge--pending">{{ $pendingBookings ?? 0 }}</span></div>
                    <div class="vd-mini-row"><span>Confirmed</span><span class="vd-badge vd-badge--confirmed">{{ $confirmedBookings ?? 0 }}</span></div>
                    <div class="vd-mini-row"><span>Completed</span><span class="vd-badge vd-badge--completed">{{ $completedBookings ?? 0 }}</span></div>
                    <div class="vd-mini-row"><span>Cancelled</span><span class="vd-badge vd-badge--cancelled">{{ $cancelledBookings ?? 0 }}</span></div>
                </div>
            </div>
        </div>

        {{-- RESORT OVERVIEW --}}
        <div class="col-xl-4">
            <div class="vd-panel h-100">
                <div class="vd-panel-head">
                    <div><h2>Resort overview</h2></div>
                </div>
                <div class="vd-mini">
                    <div class="vd-mini-row"><span>Total resorts</span><span>{{ $totalResorts ?? 0 }}</span></div>
                    <div class="vd-mini-row"><span>Total rooms</span><span>{{ $totalRooms ?? 0 }}</span></div>
                    <div class="vd-mini-row"><span>Total bookings</span><span>{{ $totalBookings ?? 0 }}</span></div>
                    <div class="vd-mini-row"><span>Total earnings</span><span>৳{{ number_format($totalEarnings ?? 0, 2) }}</span></div>
                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
        RECENT RESORTS
    ========================================================== --}}

    <div class="vd-panel mb-4">

        <div class="vd-panel-head">
            <div>
                <h2>My resorts</h2>
                <small>Recently added resorts</small>
            </div>
            <a href="{{ route('vendor.resorts.index') }}" class="vd-link-btn">View all</a>
        </div>

        @if(isset($resorts) && $resorts->count())

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Resort</th>
                            <th>Added by</th>
                            <th>Date</th>
                            <th class="pe-4">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resorts->take(5) as $resort)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="vd-thumb">
                                            @if($resort->featured_image)
                                                <img src="{{ asset('storage/' . $resort->featured_image) }}" alt="{{ $resort->name }}">
                                            @else
                                                <i class="bi bi-building text-muted"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $resort->name }}</div>
                                            <small class="text-muted">{{ $resort->district ?? 'Location not set' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $vendor->business_name ?? auth()->user()->name }}</td>
                                <td class="text-muted">{{ $resort->created_at?->format('d M Y') }}</td>
                                <td class="pe-4 text-muted">{{ $resort->created_at?->format('h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else

            <div class="vd-empty">
                <i class="bi bi-building"></i>
                <strong>No resorts yet</strong>
                <small>Start by adding your first resort.</small>
            </div>

        @endif

    </div>


    {{-- =========================================================
        RECENT WALLET TRANSACTIONS
    ========================================================== --}}

    @if(isset($recentTransactions))

        <div class="vd-panel">

            <div class="vd-panel-head">
                <div>
                    <h2>Recent wallet activity</h2>
                    <small>Latest wallet transactions</small>
                </div>
                <a href="{{ route('vendor.wallet.index') }}" class="vd-link-btn">View wallet</a>
            </div>

            @if($recentTransactions->count())

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4">Date</th>
                                <th>Type</th>
                                <th>Booking</th>
                                <th>Note</th>
                                <th class="text-end pe-4">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td class="ps-4">{{ optional($transaction->created_at)->format('d M Y') }}</td>
                                    <td>
                                        @if($transaction->type === 'credit')
                                            <span class="vd-badge vd-badge--confirmed">Credit</span>
                                        @else
                                            <span class="vd-badge vd-badge--cancelled">Debit</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->booking_id ? '#'.$transaction->booking_id : '—' }}</td>
                                    <td>{{ $transaction->note ?? 'Wallet transaction' }}</td>
                                    <td class="text-end pe-4">
                                        <span class="fw-semibold" style="color:{{ $transaction->type === 'credit' ? 'var(--teal-deep)' : 'var(--coral)' }};">
                                            {{ $transaction->type === 'credit' ? '+' : '-' }}৳{{ number_format($transaction->amount ?? 0, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @else

                <div class="vd-empty">
                    <i class="bi bi-wallet2"></i>
                    <strong>No wallet activity yet</strong>
                    <small>Transactions will appear here once bookings are paid out.</small>
                </div>

            @endif

        </div>

    @endif

</div>

@endsection