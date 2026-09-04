@extends('layouts.admin')

@section('title', 'Completed Bookings')

@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
    --b-surface: #1a1d27;
    --b-surface2: #222636;
    --b-border: rgba(255,255,255,.07);
    --b-success: #22c55e;
    --b-success2: #86efac;
    --b-info: #38bdf8;
    --b-purple: #a78bfa;
    --b-text: #e2e8f0;
    --b-muted: #64748b;
    --b-radius: 14px;
    --b-radius-sm: 8px;
    --b-shadow: 0 8px 32px rgba(0,0,0,.45);
}

.b-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--b-text);
}

.b-header {
    background: linear-gradient(135deg,#052e16 0%,#14532d 50%,#166534 100%);
    border-radius: var(--b-radius);
    padding: 28px 32px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--b-shadow);
}

.b-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(34,197,94,.03);
}

.b-header::after {
    content: '';
    position: absolute;
    right: -40px;
    top: -40px;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: radial-gradient(circle,rgba(34,197,94,.15) 0%,transparent 70%);
}

.b-header .title {
    font-size: 1.5rem;
    font-weight: 700;
    position: relative;
    z-index: 1;
    background: linear-gradient(90deg,#fff,#86efac);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.b-header .subtitle {
    color: rgba(255,255,255,.5);
    font-size: .85rem;
    margin-top: 4px;
    position: relative;
    z-index: 1;
}

.stat-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 40px;
    padding: 6px 16px;
    font-size: .8rem;
    font-weight: 600;
    color: #fff;
    position: relative;
    z-index: 1;
}

.stat-pill .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.b-table-card {
    background: var(--b-surface);
    border: 1px solid var(--b-border);
    border-radius: var(--b-radius);
    overflow: hidden;
    box-shadow: var(--b-shadow);
}

.b-search-bar {
    padding: 16px 20px;
    border-bottom: 1px solid var(--b-border);
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: space-between;
}

.b-search-wrap {
    position: relative;
}

.b-search-wrap .si {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--b-muted);
    font-size: .8rem;
}

.b-search-input {
    background: var(--b-surface2);
    border: 1px solid var(--b-border);
    border-radius: var(--b-radius-sm);
    padding: 8px 14px 8px 36px;
    color: var(--b-text);
    font-family: inherit;
    font-size: .875rem;
    width: 260px;
    outline: none;
    transition: border-color .2s;
}

.b-search-input:focus {
    border-color: var(--b-success);
    box-shadow: 0 0 0 3px rgba(34,197,94,.12);
}

.b-table {
    width: 100%;
    border-collapse: collapse;
}

.b-table thead tr {
    background: var(--b-surface2);
}

.b-table th {
    padding: 13px 18px;
    text-align: left;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--b-muted);
    white-space: nowrap;
}

.b-table td {
    padding: 13px 18px;
    vertical-align: middle;
    border-bottom: 1px solid var(--b-border);
    font-size: .875rem;
}

.b-table tbody tr {
    transition: background .15s;
}

.b-table tbody tr:hover {
    background: rgba(34,197,94,.04);
}

.b-table tbody tr:last-child td {
    border-bottom: none;
}

.b-code {
    font-family: 'JetBrains Mono', monospace;
    font-size: .8rem;
    background: var(--b-surface2);
    border: 1px solid var(--b-border);
    padding: 3px 8px;
    border-radius: 6px;
    color: var(--b-success2);
}

.b-user {
    display: flex;
    align-items: center;
    gap: 10px;
}

.b-user-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--b-border);
    flex-shrink: 0;
}

.b-user-avatar-placeholder {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: var(--b-success);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .75rem;
    font-weight: 700;
    color: #052e16;
    flex-shrink: 0;
}

.b-user-name {
    font-weight: 600;
    font-size: .875rem;
}

.b-user-email {
    font-size: .75rem;
    color: var(--b-muted);
}

.b-tour-name {
    font-weight: 600;
    max-width: 180px;
    font-size: .875rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.b-tour-dest {
    font-size: .75rem;
    color: var(--b-muted);
}

.b-amount {
    font-family: 'JetBrains Mono', monospace;
    font-weight: 600;
    color: var(--b-success2);
}

.b-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 700;
}

.b-badge-completed {
    background: rgba(167,139,250,.12);
    color: #c4b5fd;
    border: 1px solid rgba(167,139,250,.25);
}

.b-badge-paid {
    background: rgba(34,197,94,.12);
    color: #86efac;
    border: 1px solid rgba(34,197,94,.25);
}

.b-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: none;
    cursor: pointer;
    font-family: inherit;
    font-weight: 600;
    border-radius: var(--b-radius-sm);
    transition: all .2s;
    text-decoration: none;
}

.b-btn-outline {
    background: transparent;
    color: var(--b-text);
    border: 1px solid var(--b-border);
    padding: 9px 14px;
    font-size: .82rem;
}

.b-btn-outline:hover {
    background: var(--b-surface2);
    color: var(--b-text);
}

.b-btn-icon {
    background: var(--b-surface2);
    color: var(--b-muted);
    border: 1px solid var(--b-border);
    padding: 6px 10px;
    font-size: .78rem;
    border-radius: 6px;
}

.b-btn-icon:hover {
    color: var(--b-info);
    border-color: rgba(56,189,248,.3);
}

.b-actions-cell {
    display: flex;
    gap: 6px;
    align-items: center;
}

.b-empty {
    text-align: center;
    padding: 60px 20px;
    color: var(--b-muted);
}

.b-empty i {
    font-size: 2.5rem;
    margin-bottom: 14px;
    opacity: .4;
    display: block;
}

@media(max-width:768px) {
    .b-header {
        padding: 22px 18px;
    }

    .b-search-input {
        width: 100%;
    }

    .b-search-wrap {
        width: 100%;
    }
}
</style>

@if(session('success')) <div id="flash-success" data-msg="{{ session('success') }}"></div>
@endif

@if(session('error')) <div id="flash-error" data-msg="{{ session('error') }}"></div>
@endif

<div class="b-wrap">

```
{{-- Header --}}
<div class="b-header">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>

            <div class="title">
                <i class="fas fa-check-double me-2"></i>
                Completed Bookings
            </div>

            <div class="subtitle">
                Successfully completed tour bookings
            </div>

            <div class="d-flex gap-2 mt-3 flex-wrap">

                <span class="stat-pill">
                    <span class="dot" style="background:var(--b-success)"></span>
                    {{ $bookings->count() }} Completed
                </span>

                <span class="stat-pill">
                    <span class="dot" style="background:var(--b-purple)"></span>
                    ৳{{ number_format($bookings->sum('total_amount'), 0) }}
                    Total Value
                </span>

            </div>

        </div>

        <div style="position:relative;z-index:1;">

            <a href="{{ route('admin.bookings.processing') }}"
               class="b-btn b-btn-outline">

                <i class="fas fa-spinner"></i>
                Processing Bookings

            </a>

        </div>

    </div>

</div>


{{-- Table --}}
<div class="b-table-card">

    <div class="b-search-bar">

        <div class="b-search-wrap">

            <i class="fas fa-search si"></i>

            <input
                type="text"
                class="b-search-input"
                id="b-search"
                placeholder="Search by code, user or tour..."
            >

        </div>

        <span
            style="font-size:.8rem;color:var(--b-muted);"
            id="b-count">
        </span>

    </div>


    <div style="overflow-x:auto;">

        <table class="b-table">

            <thead>

                <tr>
                    <th>#</th>
                    <th>Booking Code</th>
                    <th>Customer</th>
                    <th>Tour</th>
                    <th>Persons</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody id="b-tbody">

                @forelse($bookings as $i => $booking)

                    <tr
                        data-search="{{ strtolower(
                            $booking->booking_code . ' ' .
                            ($booking->user->name ?? '') . ' ' .
                            ($booking->user->email ?? '') . ' ' .
                            ($booking->tour->title ?? '')
                        ) }}"
                    >

                        <td style="color:var(--b-muted);font-size:.8rem;">
                            {{ $i + 1 }}
                        </td>

                        <td>
                            <span class="b-code">
                                {{ $booking->booking_code }}
                            </span>
                        </td>

                        <td>

                            <div class="b-user">

                                @if($booking->user->photo ?? false)

                                    <img
                                        src="{{ asset('uploads/users/'.$booking->user->photo) }}"
                                        class="b-user-avatar"
                                        alt="User"
                                    >

                                @else

                                    <div class="b-user-avatar-placeholder">

                                        {{ strtoupper(
                                            substr(
                                                $booking->user->name ?? 'U',
                                                0,
                                                1
                                            )
                                        ) }}

                                    </div>

                                @endif

                                <div>

                                    <div class="b-user-name">
                                        {{ $booking->user->name ?? 'N/A' }}
                                    </div>

                                    <div class="b-user-email">
                                        {{ $booking->user->email ?? '' }}
                                    </div>

                                </div>

                            </div>

                        </td>

                        <td>

                            <div
                                class="b-tour-name"
                                title="{{ $booking->tour->title ?? 'N/A' }}"
                            >
                                {{ $booking->tour->title ?? 'N/A' }}
                            </div>

                            @if($booking->tour->destination ?? false)

                                <div class="b-tour-dest">

                                    <i class="fas fa-map-marker-alt me-1"></i>

                                    {{ $booking->tour->destination->name }}

                                </div>

                            @endif

                        </td>

                        <td style="text-align:center;font-weight:600;">
                            {{ $booking->person_count }}
                        </td>

                        <td>
                            <span class="b-amount">
                                ৳{{ number_format($booking->total_amount, 0) }}
                            </span>
                        </td>

                        <td>

                            @if($booking->payment_status === 'paid')

                                <span class="b-badge b-badge-paid">
                                    <i class="fas fa-check-circle"></i>
                                    Paid
                                </span>

                            @else

                                <span class="b-badge b-badge-completed">
                                    {{ ucfirst($booking->payment_status) }}
                                </span>

                            @endif

                        </td>

                        <td style="font-size:.8rem;color:var(--b-muted);">

                            {{ $booking->created_at->format('d M Y') }}

                            <br>

                            {{ $booking->created_at->format('h:i A') }}

                        </td>

                        <td>

                            <div class="b-actions-cell">

                                <a
                                    href="{{ route('admin.bookings.show', $booking->id) }}"
                                    class="b-btn b-btn-icon"
                                    title="View Details"
                                >
                                    <i class="fas fa-eye"></i>
                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9">

                            <div class="b-empty">

                                <i class="fas fa-check-circle"></i>

                                <p>
                                    No completed bookings at the moment.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
```

</div>

<script>
(function () {

    const input = document.getElementById('b-search');
    const rows = Array.from(
        document.querySelectorAll('#b-tbody tr[data-search]')
    );

    const count = document.getElementById('b-count');

    if (!input || !count) {
        return;
    }

    function updateCount() {

        const query = input.value.toLowerCase().trim();

        let visible = 0;

        rows.forEach(row => {

            const show =
                !query ||
                row.dataset.search.includes(query);

            row.style.display = show ? '' : 'none';

            if (show) {
                visible++;
            }

        });

        count.textContent =
            visible + ' of ' + rows.length + ' bookings';

    }

    input.addEventListener('input', updateCount);

    updateCount();

})();
</script>

@endsection
