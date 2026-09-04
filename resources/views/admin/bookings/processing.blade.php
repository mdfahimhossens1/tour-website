@extends('layouts.admin')

@section('title', 'Processing Bookings')

@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
    --p-surface: #1a1d27;
    --p-surface2: #222636;
    --p-border: rgba(255,255,255,.07);

    --p-accent: #38bdf8;
    --p-accent2: #7dd3fc;

    --p-success: #22c55e;
    --p-danger: #ef4444;
    --p-warning: #f59e0b;
    --p-purple: #a78bfa;

    --p-text: #e2e8f0;
    --p-muted: #64748b;

    --p-radius: 14px;
    --p-radius-sm: 8px;

    --p-shadow: 0 8px 32px rgba(0,0,0,.45);
}

.p-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--p-text);
}

/* Header */

.p-header {
    background: linear-gradient(
        135deg,
        #082f49 0%,
        #075985 50%,
        #0369a1 100%
    );

    border-radius: var(--p-radius);
    padding: 28px 32px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--p-shadow);
}

.p-header::before {
    content: '';
    position: absolute;
    inset: 0;

    background:
        radial-gradient(
            circle at 80% 20%,
            rgba(125,211,252,.12),
            transparent 35%
        );
}

.p-header::after {
    content: '';
    position: absolute;
    right: -50px;
    top: -50px;

    width: 200px;
    height: 200px;

    border-radius: 50%;

    background:
        radial-gradient(
            circle,
            rgba(56,189,248,.18) 0%,
            transparent 70%
        );
}

.p-header .title {
    font-size: 1.5rem;
    font-weight: 700;

    position: relative;
    z-index: 1;

    background:
        linear-gradient(
            90deg,
            #fff,
            var(--p-accent2)
        );

    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.p-header .subtitle {
    color: rgba(255,255,255,.55);
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

/* Table */

.p-table-card {
    background: var(--p-surface);

    border: 1px solid var(--p-border);

    border-radius: var(--p-radius);

    overflow: hidden;

    box-shadow: var(--p-shadow);
}

.p-search-bar {
    padding: 16px 20px;

    border-bottom: 1px solid var(--p-border);

    display: flex;
    align-items: center;

    gap: 12px;

    flex-wrap: wrap;

    justify-content: space-between;
}

.p-search-wrap {
    position: relative;
}

.p-search-wrap .si {
    position: absolute;

    left: 11px;
    top: 50%;

    transform: translateY(-50%);

    color: var(--p-muted);

    font-size: .8rem;
}

.p-search-input {
    background: var(--p-surface2);

    border: 1px solid var(--p-border);

    border-radius: var(--p-radius-sm);

    padding: 8px 14px 8px 36px;

    color: var(--p-text);

    font-family: inherit;

    font-size: .875rem;

    width: 280px;

    outline: none;

    transition: border-color .2s;
}

.p-search-input:focus {
    border-color: var(--p-accent);

    box-shadow:
        0 0 0 3px rgba(56,189,248,.12);
}

/* Table */

.p-table {
    width: 100%;
    border-collapse: collapse;
}

.p-table thead tr {
    background: var(--p-surface2);
}

.p-table th {
    padding: 13px 18px;

    text-align: left;

    font-size: .72rem;

    font-weight: 700;

    letter-spacing: .08em;

    text-transform: uppercase;

    color: var(--p-muted);

    white-space: nowrap;
}

.p-table td {
    padding: 13px 18px;

    vertical-align: middle;

    border-bottom: 1px solid var(--p-border);

    font-size: .875rem;
}

.p-table tbody tr {
    transition: background .15s;
}

.p-table tbody tr:hover {
    background: rgba(56,189,248,.04);
}

.p-table tbody tr:last-child td {
    border-bottom: none;
}

/* Code */

.p-code {
    font-family: 'JetBrains Mono', monospace;

    font-size: .8rem;

    background: var(--p-surface2);

    border: 1px solid var(--p-border);

    padding: 3px 8px;

    border-radius: 6px;

    color: var(--p-accent2);
}

/* User */

.p-user {
    display: flex;
    align-items: center;

    gap: 10px;
}

.p-user-avatar {
    width: 34px;
    height: 34px;

    border-radius: 50%;

    object-fit: cover;

    border: 2px solid var(--p-border);

    flex-shrink: 0;
}

.p-user-avatar-placeholder {
    width: 34px;
    height: 34px;

    border-radius: 50%;

    background: var(--p-accent);

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: .75rem;

    font-weight: 700;

    color: #082f49;

    flex-shrink: 0;
}

.p-user-name {
    font-weight: 600;
    font-size: .875rem;
}

.p-user-email {
    font-size: .75rem;
    color: var(--p-muted);
}

/* Tour */

.p-tour-name {
    font-weight: 600;

    max-width: 180px;

    font-size: .875rem;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;
}

.p-tour-dest {
    font-size: .75rem;

    color: var(--p-muted);
}

/* Amount */

.p-amount {
    font-family: 'JetBrains Mono', monospace;

    font-weight: 600;

    color: var(--p-success);
}

/* Processing badge */

.p-processing-badge {
    display: inline-flex;

    align-items: center;

    gap: 5px;

    padding: 4px 10px;

    border-radius: 20px;

    font-size: .72rem;

    font-weight: 700;

    background: rgba(56,189,248,.12);

    color: #7dd3fc;

    border: 1px solid rgba(56,189,248,.25);
}

/* Buttons */

.p-btn {
    display: inline-flex;

    align-items: center;

    gap: 6px;

    border: none;

    cursor: pointer;

    font-family: inherit;

    font-weight: 600;

    border-radius: var(--p-radius-sm);

    transition: all .2s;

    text-decoration: none;
}

.p-btn-icon {
    background: var(--p-surface2);

    color: var(--p-muted);

    border: 1px solid var(--p-border);

    padding: 6px 10px;

    font-size: .78rem;

    border-radius: 6px;
}

.p-btn-icon:hover {
    color: var(--p-accent);

    border-color: rgba(56,189,248,.3);
}

.p-btn-success {
    background: rgba(34,197,94,.1);

    color: #86efac;

    border: 1px solid rgba(34,197,94,.2);

    padding: 6px 10px;

    font-size: .78rem;

    border-radius: 6px;
}

.p-btn-success:hover {
    background: rgba(34,197,94,.2);
}

.p-btn-danger {
    background: rgba(239,68,68,.1);

    color: #fca5a5;

    border: 1px solid rgba(239,68,68,.2);

    padding: 6px 10px;

    font-size: .78rem;

    border-radius: 6px;
}

.p-btn-danger:hover {
    background: rgba(239,68,68,.2);
}

.p-actions {
    display: flex;

    gap: 6px;

    align-items: center;
}

/* Empty */

.p-empty {
    text-align: center;

    padding: 60px 20px;

    color: var(--p-muted);
}

.p-empty i {
    font-size: 2.5rem;

    margin-bottom: 14px;

    opacity: .4;

    display: block;
}

/* Responsive */

@media (max-width: 768px) {

    .p-header {
        padding: 22px;
    }

    .p-search-input {
        width: 100%;
    }

    .p-search-wrap {
        width: 100%;
    }

    .p-table th,
    .p-table td {
        padding: 11px 12px;
    }
}
</style>


@if(session('success'))
<div
    id="flash-success"
    data-msg="{{ session('success') }}">
</div>
@endif

@if(session('error'))
<div
    id="flash-error"
    data-msg="{{ session('error') }}">
</div>
@endif


<div class="p-wrap">

    {{-- Header --}}
    <div class="p-header">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>

                <div class="title">
                    <i class="fas fa-spinner fa-spin me-2"></i>
                    Processing Bookings
                </div>

                <div class="subtitle">
                    Manage bookings currently being processed
                </div>

                <div class="d-flex gap-2 mt-3 flex-wrap">

                    <span class="stat-pill">

                        <span
                            class="dot"
                            style="background:var(--p-accent)">
                        </span>

                        {{ $bookings->count() }} Processing

                    </span>


                    <span class="stat-pill">

                        <span
                            class="dot"
                            style="background:var(--p-success)">
                        </span>

                        ৳{{ number_format($bookings->sum('total_amount'), 0) }}

                        Total Value

                    </span>

                </div>

            </div>


            <div
                style="position:relative;z-index:1;"
                class="d-flex gap-2 flex-wrap">

                <a
                    href="{{ route('admin.bookings.pending') }}"
                    class="p-btn p-btn-icon">

                    <i class="fas fa-hourglass-half"></i>

                    Pending

                </a>


                <a
                    href="{{ route('admin.bookings.confirmed') }}"
                    class="p-btn p-btn-icon">

                    <i class="fas fa-check-circle"></i>

                    Confirmed

                </a>

            </div>

        </div>

    </div>


    {{-- Table --}}

    <div class="p-table-card">

        <div class="p-search-bar">

            <div class="p-search-wrap">

                <i class="fas fa-search si"></i>

                <input
                    type="text"
                    class="p-search-input"
                    id="p-search"
                    placeholder="Search by code, user or tour...">

            </div>


            <span
                style="font-size:.8rem;color:var(--p-muted);"
                id="p-count">
            </span>

        </div>


        <div style="overflow-x:auto;">

            <table class="p-table">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Booking Code</th>

                        <th>Customer</th>

                        <th>Tour</th>

                        <th>Persons</th>

                        <th>Amount</th>

                        <th>TRX ID</th>

                        <th>Status</th>

                        <th>Date</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody id="p-tbody">

                    @forelse($bookings as $i => $booking)

                    <tr
                        data-search="{{ strtolower(
                            $booking->booking_code.' '.
                            ($booking->user->name ?? '').' '.
                            ($booking->tour->title ?? '')
                        ) }}">

                        <td
                            style="color:var(--p-muted);font-size:.8rem;">

                            {{ $i + 1 }}

                        </td>


                        <td>

                            <span class="p-code">

                                {{ $booking->booking_code }}

                            </span>

                        </td>


                        {{-- Customer --}}

                        <td>

                            <div class="p-user">

                                @if($booking->user->photo ?? false)

                                    <img
                                        src="{{ asset('uploads/users/'.$booking->user->photo) }}"
                                        class="p-user-avatar">

                                @else

                                    <div class="p-user-avatar-placeholder">

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

                                    <div class="p-user-name">

                                        {{ $booking->user->name ?? 'N/A' }}

                                    </div>

                                    <div class="p-user-email">

                                        {{ $booking->user->email ?? '' }}

                                    </div>

                                </div>

                            </div>

                        </td>


                        {{-- Tour --}}

                        <td>

                            <div
                                class="p-tour-name"
                                title="{{ $booking->tour->title ?? 'N/A' }}">

                                {{ $booking->tour->title ?? 'N/A' }}

                            </div>


                            @if($booking->tour->destination ?? false)

                                <div class="p-tour-dest">

                                    <i class="fas fa-map-marker-alt me-1"></i>

                                    {{ $booking->tour->destination->name }}

                                </div>

                            @endif

                        </td>


                        {{-- Persons --}}

                        <td
                            style="text-align:center;font-weight:600;">

                            {{ $booking->person_count }}

                        </td>


                        {{-- Amount --}}

                        <td>

                            <span class="p-amount">

                                ৳{{ number_format(
                                    $booking->total_amount,
                                    0
                                ) }}

                            </span>

                        </td>


                        {{-- TRX --}}

                        <td>

                            @if($booking->payment)

                                <span class="p-code">

                                    {{ $booking->payment->trx_id }}

                                </span>

                            @else

                                <span style="color:#64748b">

                                    --

                                </span>

                            @endif

                        </td>


                        {{-- Status --}}

                        <td>

                            <span class="p-processing-badge">

                                <i class="fas fa-sync-alt fa-spin"></i>

                                Processing

                            </span>

                        </td>


                        {{-- Date --}}

                        <td
                            style="font-size:.8rem;color:var(--p-muted);">

                            {{ $booking->created_at->format('d M Y') }}

                            <br>

                            {{ $booking->created_at->format('h:i A') }}

                        </td>


                        {{-- Actions --}}

                        <td>

                            <div class="p-actions">

                                {{-- View --}}

                                <a
                                    href="{{ route(
                                        'admin.bookings.show',
                                        $booking->id
                                    ) }}"
                                    class="p-btn p-btn-icon"
                                    title="View Details">

                                    <i class="fas fa-eye"></i>

                                </a>


                                {{-- Confirm --}}

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'admin.bookings.confirm',
                                        $booking->id
                                    ) }}"
                                    style="display:inline;">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="p-btn p-btn-success"
                                        title="Confirm Booking">

                                        <i class="fas fa-check"></i>

                                    </button>

                                </form>


                                {{-- Cancel --}}

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'admin.bookings.cancel',
                                        $booking->id
                                    ) }}"
                                    style="display:inline;">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="p-btn p-btn-danger"
                                        title="Cancel Booking">

                                        <i class="fas fa-times"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="10">

                            <div class="p-empty">

                                <i class="fas fa-cogs"></i>

                                <p>
                                    No processing bookings at the moment.
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<script>

(function () {

    const input =
        document.getElementById('p-search');

    const rows =
        Array.from(
            document.querySelectorAll(
                '#p-tbody tr[data-search]'
            )
        );

    const count =
        document.getElementById('p-count');


    function updateSearch() {

        const query =
            input.value
                .toLowerCase()
                .trim();

        let visible = 0;


        rows.forEach(row => {

            const show =
                !query ||
                row.dataset.search.includes(query);

            row.style.display =
                show ? '' : 'none';

            if (show) {
                visible++;
            }

        });


        count.textContent =
            visible +
            ' of ' +
            rows.length +
            ' bookings';

    }


    input.addEventListener(
        'input',
        updateSearch
    );


    updateSearch();

})();

</script>


@endsection