@extends('layouts.admin')

@section('title', 'Commissions')

@section('page')

<style>

.cm-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #e2e8f0;
}

.cm-header {
    background:
        linear-gradient(
            135deg,
            #0c1a2e 0%,
            #0e0c2e 55%,
            #0c1a2e 100%
        );

    border-radius: 14px;
    padding: 28px 30px;
    margin-bottom: 22px;

    box-shadow:
        0 8px 32px rgba(0,0,0,.45);

    position: relative;
    overflow: hidden;
}

.cm-header::before {
    content: '';
    position: absolute;
    inset: 0;

    background:
        radial-gradient(
            circle at 20% 20%,
            rgba(99,102,241,.12),
            transparent 40%
        ),
        radial-gradient(
            circle at 80% 80%,
            rgba(139,92,246,.10),
            transparent 40%
        );
}

.cm-header-content {
    position: relative;
    z-index: 1;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;
    flex-wrap: wrap;
}

.cm-title {
    font-size: 1.5rem;
    font-weight: 700;

    background:
        linear-gradient(
            90deg,
            #fff,
            #a5b4fc
        );

    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.cm-subtitle {
    color: rgba(255,255,255,.45);
    font-size: .82rem;
    margin-top: 5px;
}


/*
|--------------------------------------------------------------------------
| STATISTICS
|--------------------------------------------------------------------------
*/

.cm-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 22px;
}

.cm-stat {
    background: #1a1d27;
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 14px;
    padding: 18px 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,.45);

    display: flex;
    align-items: center;
    gap: 14px;
}

.cm-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 11px;

    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.cm-stat-sales .cm-stat-icon {
    background: rgba(14,165,233,.12);
    color: #7dd3fc;
}

.cm-stat-admin .cm-stat-icon {
    background: rgba(34,197,94,.12);
    color: #86efac;
}

.cm-stat-vendor .cm-stat-icon {
    background: rgba(139,92,246,.12);
    color: #c4b5fd;
}

.cm-stat-count .cm-stat-icon {
    background: rgba(245,158,11,.12);
    color: #fcd34d;
}

.cm-stat-label {
    color: #64748b;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
}

.cm-stat-value {
    color: #e2e8f0;
    font-size: 1.15rem;
    font-family: 'JetBrains Mono', monospace;
    font-weight: 700;
    margin-top: 3px;
}


/*
|--------------------------------------------------------------------------
| CARD
|--------------------------------------------------------------------------
*/

.cm-card {
    background: #1a1d27;
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 14px;
    box-shadow: 0 8px 32px rgba(0,0,0,.45);
    overflow: hidden;
}


/*
|--------------------------------------------------------------------------
| TOOLBAR
|--------------------------------------------------------------------------
*/

.cm-toolbar {
    padding: 17px 20px;
    border-bottom: 1px solid rgba(255,255,255,.07);

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 12px;
    flex-wrap: wrap;
}

.cm-toolbar-title {
    font-size: .85rem;
    font-weight: 700;

    display: flex;
    align-items: center;
    gap: 8px;
}

.cm-toolbar-title i {
    color: #a5b4fc;
}

.cm-search {
    display: flex;
    align-items: center;
    gap: 8px;
}

.cm-search-input {
    width: 270px;
    background: #222636;
    border: 1px solid rgba(255,255,255,.07);
    color: #e2e8f0;
    border-radius: 8px;
    padding: 8px 11px;
    font-size: .78rem;
    outline: none;
}

.cm-search-input:focus {
    border-color: rgba(99,102,241,.5);
    box-shadow: 0 0 0 3px rgba(99,102,241,.08);
}

.cm-search-input::placeholder {
    color: #64748b;
}

.cm-search-btn {
    border: none;
    background: #6366f1;
    color: #fff;
    border-radius: 8px;
    padding: 8px 13px;
    cursor: pointer;
    font-size: .78rem;
    font-weight: 600;
}

.cm-search-btn:hover {
    background: #4f46e5;
}


/*
|--------------------------------------------------------------------------
| TABLE
|--------------------------------------------------------------------------
*/

.cm-table {
    width: 100%;
    border-collapse: collapse;
}

.cm-table thead tr {
    background: #222636;
    border-bottom: 1px solid rgba(255,255,255,.07);
}

.cm-table th {
    padding: 13px 17px;
    text-align: left;
    color: #64748b;
    font-size: .66rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    white-space: nowrap;
}

.cm-table td {
    padding: 15px 17px;
    border-bottom: 1px solid rgba(255,255,255,.07);
    font-size: .79rem;
    vertical-align: middle;
    color: #e2e8f0;
}

.cm-table tbody tr {
    transition: background .15s;
}

.cm-table tbody tr:hover {
    background: rgba(255,255,255,.02);
}

.cm-table tbody tr:last-child td {
    border-bottom: none;
}


/*
|--------------------------------------------------------------------------
| SOURCE BADGE
|--------------------------------------------------------------------------
*/

.cm-source {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 8px;
    border-radius: 6px;
    font-size: .64rem;
    font-weight: 700;
    white-space: nowrap;
}

.cm-source-tour {
    background: rgba(99,102,241,.12);
    color: #a5b4fc;
    border: 1px solid rgba(99,102,241,.2);
}

.cm-source-room {
    background: rgba(14,165,233,.12);
    color: #7dd3fc;
    border: 1px solid rgba(14,165,233,.2);
}

.cm-source-transport {
    background: rgba(34,197,94,.12);
    color: #86efac;
    border: 1px solid rgba(34,197,94,.2);
}


/*
|--------------------------------------------------------------------------
| BOOKING
|--------------------------------------------------------------------------
*/

.cm-booking-code {
    font-family: 'JetBrains Mono', monospace;
    color: #a5b4fc;
    font-size: .74rem;
    font-weight: 600;
}

.cm-booking-date {
    color: #64748b;
    font-size: .68rem;
    margin-top: 3px;
}


/*
|--------------------------------------------------------------------------
| PEOPLE
|--------------------------------------------------------------------------
*/

.cm-person {
    display: flex;
    align-items: center;
    gap: 8px;
}

.cm-avatar {
    width: 31px;
    height: 31px;
    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    color: #fff;
    font-size: .67rem;
    font-weight: 700;
    text-transform: uppercase;
    flex-shrink: 0;
}

.cm-avatar-customer {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
}

.cm-avatar-vendor {
    background: linear-gradient(135deg, #0ea5e9, #6366f1);
}

.cm-person-name {
    font-size: .76rem;
    font-weight: 600;
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.cm-person-sub {
    color: #64748b;
    font-size: .65rem;
    margin-top: 2px;
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}


/*
|--------------------------------------------------------------------------
| AMOUNT
|--------------------------------------------------------------------------
*/

.cm-amount {
    font-family: 'JetBrains Mono', monospace;
    font-size: .76rem;
    font-weight: 600;
    white-space: nowrap;
}

.cm-admin {
    color: #86efac;
}

.cm-vendor {
    color: #c4b5fd;
}

.cm-total {
    color: #7dd3fc;
}


/*
|--------------------------------------------------------------------------
| RATE
|--------------------------------------------------------------------------
*/

.cm-rate {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 9px;

    background: rgba(245,158,11,.1);
    border: 1px solid rgba(245,158,11,.2);
    border-radius: 7px;

    color: #fcd34d;
    font-family: 'JetBrains Mono', monospace;
    font-size: .72rem;
    font-weight: 700;
    white-space: nowrap;
}


/*
|--------------------------------------------------------------------------
| VIEW
|--------------------------------------------------------------------------
*/

.cm-view {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    background: rgba(99,102,241,.1);
    color: #a5b4fc;
    border: 1px solid rgba(99,102,241,.2);

    border-radius: 7px;
    padding: 6px 10px;
    text-decoration: none;

    font-size: .7rem;
    font-weight: 600;
    transition: all .2s;
    white-space: nowrap;
}

.cm-view:hover {
    background: rgba(99,102,241,.2);
    color: #c7d2fe;
    transform: translateY(-1px);
}


/*
|--------------------------------------------------------------------------
| EMPTY
|--------------------------------------------------------------------------
*/

.cm-empty {
    text-align: center;
    padding: 70px 20px;
    color: #64748b;
}

.cm-empty i {
    font-size: 2.4rem;
    opacity: .25;
    margin-bottom: 12px;
    display: block;
}


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

.cm-pagination {
    padding: 14px 18px;
    border-top: 1px solid rgba(255,255,255,.07);
}

.cm-pagination .pagination {
    margin: 0;
}

.cm-pagination .page-link {
    background: #222636;
    border: 1px solid rgba(255,255,255,.07);
    color: #64748b;
    font-size: .75rem;
}

.cm-pagination .page-link:hover {
    background: rgba(255,255,255,.07);
    color: #e2e8f0;
}

.cm-pagination .page-item.active .page-link {
    background: #6366f1;
    border-color: #6366f1;
    color: #fff;
}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 1150px) {

    .cm-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

@media (max-width: 700px) {

    .cm-stats {
        grid-template-columns: 1fr;
    }

    .cm-search {
        width: 100%;
    }

    .cm-search-input {
        width: 100%;
    }

}

</style>


@php

    $money = function ($amount) {
        return '৳' . number_format((float) $amount, 2);
    };

@endphp


<div class="cm-wrap">

    {{-- HEADER --}}

    <div class="cm-header">

        <div class="cm-header-content">

            <div>

                <div class="cm-title">
                    <i class="fas fa-hand-holding-usd me-2"></i>
                    Commission Management
                </div>

                <div class="cm-subtitle">
                    Monitor platform earnings from tour, room and transport bookings.
                </div>

            </div>

        </div>

    </div>


    {{-- STATISTICS --}}

    <div class="cm-stats">

        <div class="cm-stat cm-stat-sales">
            <div class="cm-stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>

            <div>
                <div class="cm-stat-label">Total Sales</div>
                <div class="cm-stat-value">
                    {{ $money($stats['total_sales']) }}
                </div>
            </div>
        </div>


        <div class="cm-stat cm-stat-admin">
            <div class="cm-stat-icon">
                <i class="fas fa-wallet"></i>
            </div>

            <div>
                <div class="cm-stat-label">Admin Profit</div>
                <div class="cm-stat-value">
                    {{ $money($stats['admin_earning']) }}
                </div>
            </div>
        </div>


        <div class="cm-stat cm-stat-vendor">
            <div class="cm-stat-icon">
                <i class="fas fa-store"></i>
            </div>

            <div>
                <div class="cm-stat-label">Vendor Earning</div>
                <div class="cm-stat-value">
                    {{ $money($stats['vendor_earning']) }}
                </div>
            </div>
        </div>


        <div class="cm-stat cm-stat-count">
            <div class="cm-stat-icon">
                <i class="fas fa-receipt"></i>
            </div>

            <div>
                <div class="cm-stat-label">Commissions</div>
                <div class="cm-stat-value">
                    {{ number_format($stats['total_commissions']) }}
                </div>
            </div>
        </div>

    </div>


    {{-- TABLE CARD --}}

    <div class="cm-card">

        {{-- TOOLBAR --}}

        <div class="cm-toolbar">

            <div class="cm-toolbar-title">
                <i class="fas fa-list"></i>
                Commission Records
            </div>

            <form
                method="GET"
                action="{{ route('admin.commissions.index') }}"
                class="cm-search"
            >

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="cm-search-input"
                    placeholder="Search booking, customer, vendor..."
                >

                <button type="submit" class="cm-search-btn">
                    <i class="fas fa-search"></i>
                    Search
                </button>

            </form>

        </div>


        {{-- TABLE --}}

        <div class="table-responsive">

            <table class="cm-table">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Booking</th>
                        <th>Customer</th>
                        <th>Vendor</th>
                        <th>Service</th>
                        <th>Total</th>
                        <th>Rate</th>
                        <th>Admin Profit</th>
                        <th>Vendor Earning</th>
                        <th>Action</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($commissions as $key => $commission)

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | DETERMINE COMMISSION TYPE
                            |--------------------------------------------------------------------------
                            */

                            $isTour = !empty($commission->booking_id);

                            $isRoom = !empty($commission->room_booking_id);

                            $isTransport = !empty(
                                $commission->transport_booking_id
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | RELATED BOOKING
                            |--------------------------------------------------------------------------
                            */

                            if ($isTour) {

                                $booking = $commission->booking;

                            } elseif ($isRoom) {

                                $booking = $commission->roomBooking;

                            } elseif ($isTransport) {

                                $booking = $commission->transportBooking;

                            } else {

                                $booking = null;

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | CUSTOMER & VENDOR
                            |--------------------------------------------------------------------------
                            */

                            $customer = $booking?->user;

                            $vendor = $booking?->vendor;


                            /*
                            |--------------------------------------------------------------------------
                            | SERVICE
                            |--------------------------------------------------------------------------
                            */

                            $tour = $isTour
                                ? $booking?->tour
                                : null;

                            $resort = $isRoom
                                ? $booking?->resort
                                : null;

                            $room = $isRoom
                                ? $booking?->room
                                : null;

                            $vehicle = $isTransport
                                ? $booking?->vehicle
                                : null;


                            /*
                            |--------------------------------------------------------------------------
                            | BOOKING INFORMATION
                            |--------------------------------------------------------------------------
                            */

                            $bookingCode =
                                $booking?->booking_code ?? '—';

                            $recordDate =
                                $commission->created_at;


                            /*
                            |--------------------------------------------------------------------------
                            | INITIALS
                            |--------------------------------------------------------------------------
                            */

                            $customerInitial = strtoupper(
                                substr(
                                    $customer?->name ?? 'U',
                                    0,
                                    1
                                )
                            );

                            $vendorInitial = strtoupper(
                                substr(
                                    $vendor?->business_name
                                        ?? $vendor?->name
                                        ?? 'V',
                                    0,
                                    1
                                )
                            );

                        @endphp


                        <tr>

                            {{-- SERIAL --}}

                            <td>
                                <span style="
                                    color:#64748b;
                                    font-family:'JetBrains Mono',monospace;
                                    font-size:.7rem;
                                ">
                                    {{ str_pad(
                                        $commissions->firstItem() + $key,
                                        2,
                                        '0',
                                        STR_PAD_LEFT
                                    ) }}
                                </span>
                            </td>


                            {{-- TYPE --}}

                            <td>

                                @if($isTour)

                                    <span class="cm-source cm-source-tour">
                                        <i class="fas fa-map-marked-alt"></i>
                                        Tour
                                    </span>

                                @elseif($isRoom)

                                    <span class="cm-source cm-source-room">
                                        <i class="fas fa-bed"></i>
                                        Room
                                    </span>

                                @elseif($isTransport)

                                    <span class="cm-source cm-source-transport">
                                        <i class="fas fa-car"></i>
                                        Transport
                                    </span>

                                @else

                                    <span class="cm-source">
                                        Unknown
                                    </span>

                                @endif

                            </td>


                            {{-- BOOKING --}}

                            <td>

                                <div class="cm-booking-code">
                                    {{ $bookingCode }}
                                </div>

                                <div class="cm-booking-date">
                                    {{ $recordDate?->format('d M Y') ?? '—' }}
                                </div>

                            </td>


                            {{-- CUSTOMER --}}

                            <td>

                                <div class="cm-person">

                                    <div class="cm-avatar cm-avatar-customer">
                                        {{ $customerInitial }}
                                    </div>

                                    <div>

                                        <div class="cm-person-name">
                                            {{ $customer?->name ?? '—' }}
                                        </div>

                                        <div class="cm-person-sub">
                                            {{ $customer?->email ?? '—' }}
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- VENDOR --}}

                            <td>

                                <div class="cm-person">

                                    <div class="cm-avatar cm-avatar-vendor">
                                        {{ $vendorInitial }}
                                    </div>

                                    <div>

                                        <div class="cm-person-name">
                                            {{
                                                $vendor?->business_name
                                                ?? $vendor?->name
                                                ?? '—'
                                            }}
                                        </div>

                                        <div class="cm-person-sub">
                                            {{ $vendor?->phone ?? 'Vendor' }}
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- SERVICE --}}

                            <td>

                                {{-- TOUR --}}

                                @if($isTour)

                                    <div style="
                                        max-width:150px;
                                        white-space:nowrap;
                                        overflow:hidden;
                                        text-overflow:ellipsis;
                                    ">
                                        {{ $tour?->title ?? '—' }}
                                    </div>

                                    <small style="
                                        color:#64748b;
                                        font-size:.62rem;
                                    ">
                                        Tour Booking
                                    </small>


                                {{-- ROOM --}}

                                @elseif($isRoom)

                                    <div style="
                                        max-width:150px;
                                        white-space:nowrap;
                                        overflow:hidden;
                                        text-overflow:ellipsis;
                                    ">
                                        {{ $resort?->name ?? '—' }}
                                    </div>

                                    <small style="
                                        color:#64748b;
                                        font-size:.62rem;
                                    ">
                                        {{ $room?->name ?? 'Room Booking' }}
                                    </small>


                                {{-- TRANSPORT --}}

                                @elseif($isTransport)

                                    <div style="
                                        max-width:150px;
                                        white-space:nowrap;
                                        overflow:hidden;
                                        text-overflow:ellipsis;
                                    ">
                                        {{ $vehicle?->name ?? 'Vehicle' }}
                                    </div>

                                    <small style="
                                        color:#64748b;
                                        font-size:.62rem;
                                    ">
                                        {{
                                            $vehicle?->registration_number
                                            ?? 'Transport Booking'
                                        }}
                                    </small>

                                @else

                                    —

                                @endif

                            </td>


                            {{-- TOTAL --}}

                            <td>
                                <span class="cm-amount cm-total">
                                    {{ $money($commission->total_amount) }}
                                </span>
                            </td>


                            {{-- RATE --}}

                            <td>

                                <span class="cm-rate">
                                    <i class="fas fa-percent"></i>
                                    {{
                                        number_format(
                                            (float) $commission->commission_rate,
                                            2
                                        )
                                    }}%
                                </span>

                            </td>


                            {{-- ADMIN PROFIT --}}

                            <td>

                                <span class="cm-amount cm-admin">
                                    + {{ $money($commission->admin_earning) }}
                                </span>

                            </td>


                            {{-- VENDOR EARNING --}}

                            <td>

                                <span class="cm-amount cm-vendor">
                                    {{ $money($commission->vendor_earning) }}
                                </span>

                            </td>


                            {{-- ACTION --}}

                            <td>

                                <a
                                    href="{{ route(
                                        'admin.commissions.show',
                                        $commission->id
                                    ) }}"
                                    class="cm-view"
                                >

                                    <i class="fas fa-eye"></i>
                                    View

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="11">

                                <div class="cm-empty">

                                    <i class="fas fa-file-invoice-dollar"></i>

                                    <div>
                                        No commission records found.
                                    </div>

                                    @if(request('search'))

                                        <small>
                                            Try another search keyword.
                                        </small>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}

        @if($commissions->hasPages())

            <div class="cm-pagination">
                {{ $commissions->links() }}
            </div>

        @endif

    </div>

</div>

@endsection