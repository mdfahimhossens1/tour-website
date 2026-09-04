@extends('layouts.vendor')

@section('title', 'My Resorts')

@section('page')

<style>

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {

    --rs-surface: #1a1d27;
    --rs-surface2: #222636;
    --rs-border: rgba(255,255,255,.07);

    --rs-text: #e2e8f0;
    --rs-muted: #64748b;

    --rs-indigo: #6366f1;
    --rs-purple: #8b5cf6;

    --rs-success: #22c55e;
    --rs-warning: #f59e0b;
    --rs-danger: #ef4444;
    --rs-cyan: #0ea5e9;

    --rs-radius: 14px;
    --rs-shadow: 0 8px 32px rgba(0,0,0,.45);
}


.rs-wrap {

    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--rs-text);

}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.rs-header {

    background:
        linear-gradient(
            135deg,
            #0c1a2e 0%,
            #0e0c2e 55%,
            #0c1a2e 100%
        );

    border-radius: var(--rs-radius);
    padding: 28px 30px;
    margin-bottom: 22px;
    box-shadow: var(--rs-shadow);
    position: relative;
    overflow: hidden;

}


.rs-header::before {

    content: '';
    position: absolute;
    inset: 0;

    background:
        url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");

}


.rs-header-content {

    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;

}


.rs-title {

    font-size: 1.5rem;
    font-weight: 700;

    background: linear-gradient(90deg, #fff, #a5b4fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;

}


.rs-subtitle {

    color: rgba(255,255,255,.45);
    font-size: .82rem;
    margin-top: 5px;

}


.rs-btn-primary {

    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    background: linear-gradient(135deg, var(--rs-indigo), var(--rs-purple));
    color: #fff;
    border-radius: 10px;
    padding: 11px 18px;
    font-size: .82rem;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 8px 22px rgba(99,102,241,.28);
    transition: transform .2s ease, box-shadow .2s ease;
    white-space: nowrap;

}


.rs-btn-primary:hover {

    transform: translateY(-1px);
    color: #fff;
    box-shadow: 0 10px 26px rgba(99,102,241,.4);

}


/*
|--------------------------------------------------------------------------
| ALERTS
|--------------------------------------------------------------------------
*/

.rs-wrap .alert {

    background: var(--rs-surface);
    border: 1px solid var(--rs-border);
    color: var(--rs-text);
    border-radius: 12px;
    font-size: .84rem;
    box-shadow: var(--rs-shadow);

}


.rs-wrap .alert-success { border-left: 3px solid var(--rs-success); }
.rs-wrap .alert-danger  { border-left: 3px solid var(--rs-danger); }
.rs-wrap .btn-close     { filter: invert(1) grayscale(1) opacity(.6); }


/*
|--------------------------------------------------------------------------
| STATS
|--------------------------------------------------------------------------
*/

.rs-stats {

    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 22px;

}


.rs-stat {

    background: var(--rs-surface);
    border: 1px solid var(--rs-border);
    border-radius: var(--rs-radius);
    padding: 18px 20px;
    box-shadow: var(--rs-shadow);
    display: flex;
    align-items: center;
    gap: 14px;

}


.rs-stat-icon {

    width: 44px;
    height: 44px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;

}


.rs-stat-total .rs-stat-icon    { background: rgba(99,102,241,.12); color: #a5b4fc; }
.rs-stat-active .rs-stat-icon   { background: rgba(34,197,94,.12);  color: #86efac; }
.rs-stat-featured .rs-stat-icon { background: rgba(245,158,11,.12); color: #fcd34d; }
.rs-stat-verified .rs-stat-icon { background: rgba(14,165,233,.12); color: #7dd3fc; }


.rs-stat-label {

    color: var(--rs-muted);
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;

}


.rs-stat-value {

    color: var(--rs-text);
    font-size: 1.15rem;
    font-family: 'JetBrains Mono', monospace;
    font-weight: 700;
    margin-top: 3px;

}


/*
|--------------------------------------------------------------------------
| CARD / TOOLBAR
|--------------------------------------------------------------------------
*/

.rs-card {

    background: var(--rs-surface);
    border: 1px solid var(--rs-border);
    border-radius: var(--rs-radius);
    box-shadow: var(--rs-shadow);
    overflow: hidden;

}


.rs-toolbar {

    padding: 17px 20px;
    border-bottom: 1px solid var(--rs-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;

}


.rs-toolbar-title {

    font-size: .85rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;

}


.rs-toolbar-title i { color: #a5b4fc; }


.rs-total-badge {

    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(99,102,241,.12);
    border: 1px solid rgba(99,102,241,.22);
    color: #c7d2fe;
    border-radius: 8px;
    padding: 8px 13px;
    font-size: .72rem;
    font-weight: 600;

}


/*
|--------------------------------------------------------------------------
| TABLE
|--------------------------------------------------------------------------
*/

.rs-table {

    width: 100%;
    min-width: 1000px;
    border-collapse: collapse;

}


.rs-table thead tr {

    background: var(--rs-surface2);
    border-bottom: 1px solid var(--rs-border);

}


.rs-table th {

    padding: 13px 17px;
    text-align: left;
    color: var(--rs-muted);
    font-size: .66rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    white-space: nowrap;

}


.rs-table td {

    padding: 15px 17px;
    border-bottom: 1px solid var(--rs-border);
    font-size: .79rem;
    vertical-align: middle;
    color: var(--rs-text);

}


.rs-table tbody tr { transition: background .15s; }
.rs-table tbody tr:hover { background: rgba(255,255,255,.02); }
.rs-table tbody tr:last-child td { border-bottom: none; }


/* Resort identity */

.rs-resort-info {

    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 250px;

}


.rs-resort-image {

    width: 62px;
    height: 50px;
    border-radius: 9px;
    object-fit: cover;
    flex-shrink: 0;
    border: 1px solid var(--rs-border);

}


.rs-resort-placeholder {

    width: 62px;
    height: 50px;
    border-radius: 9px;
    background: var(--rs-surface2);
    border: 1px solid var(--rs-border);
    color: var(--rs-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;

}


.rs-resort-name {

    font-size: .78rem;
    font-weight: 600;
    color: var(--rs-text);

}


.rs-resort-slug {

    color: var(--rs-muted);
    font-family: 'JetBrains Mono', monospace;
    font-size: .63rem;
    margin-top: 2px;

}


.rs-tags {

    margin-top: 6px;
    display: flex;
    gap: 4px;
    flex-wrap: wrap;

}


.rs-mini-tag {

    display: inline-flex;
    align-items: center;
    gap: 3px;
    border-radius: 5px;
    padding: 3px 6px;
    font-size: 9px;
    font-weight: 600;

}


.rs-tag-featured { background: rgba(245,158,11,.12); color: #fcd34d; }
.rs-tag-verified { background: rgba(34,197,94,.12);  color: #86efac; }


/* Destination / Location */

.rs-destination { color: #cbd5e1; font-weight: 600; }
.rs-destination i { color: var(--rs-muted); }

.rs-location-main { color: var(--rs-text); font-weight: 600; }
.rs-location-sub  { color: var(--rs-muted); font-size: .68rem; }


/* Rooms */

.rs-room-count { display: inline-flex; align-items: center; gap: 7px; }

.rs-room-number {

    min-width: 28px;
    height: 28px;
    padding: 0 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: rgba(99,102,241,.12);
    color: #a5b4fc;
    font-family: 'JetBrains Mono', monospace;
    font-size: .72rem;
    font-weight: 700;

}


.rs-room-label { color: var(--rs-muted); font-size: .68rem; }


/* Rating */

.rs-rating-value { color: var(--rs-text); font-weight: 700; }
.rs-rating-star  { color: #fcd34d; font-size: .72rem; }
.rs-review-count { color: var(--rs-muted); font-size: .66rem; display: block; margin-top: 2px; }


/* Status */

.rs-status-badge {

    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 8px;
    border-radius: 7px;
    font-size: .66rem;
    font-weight: 700;

}


.rs-status-active   { background: rgba(34,197,94,.1);  color: #86efac; border: 1px solid rgba(34,197,94,.18); }
.rs-status-inactive { background: rgba(239,68,68,.1);  color: #fca5a5; border: 1px solid rgba(239,68,68,.18); }


/*
|--------------------------------------------------------------------------
| ACTIONS
|--------------------------------------------------------------------------
*/

.rs-action-btn {

    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid var(--rs-border);
    background: rgba(255,255,255,.02);
    color: var(--rs-muted);
    transition: all .2s;

}


.rs-action-btn:hover,
.rs-action-btn:focus {

    background: rgba(99,102,241,.14);
    color: #c7d2fe;
    border-color: rgba(99,102,241,.3);
    box-shadow: none;

}


.rs-wrap .dropdown-menu {

    background: var(--rs-surface2);
    border: 1px solid var(--rs-border);
    border-radius: 10px;
    padding: 6px;
    box-shadow: 0 20px 45px rgba(0,0,0,.5);
    min-width: 190px;

}


.rs-wrap .dropdown-item {

    border-radius: 7px;
    font-size: .8rem;
    padding: 8px 10px;
    color: #cbd5e1;

}


.rs-wrap .dropdown-item:hover { background: rgba(255,255,255,.05); color: #fff; }
.rs-wrap .dropdown-item i { width: 18px; color: #93a2b8; }
.rs-wrap .dropdown-item.text-danger { color: #fca5a5 !important; }
.rs-wrap .dropdown-item.text-danger i { color: #fca5a5 !important; }
.rs-wrap .dropdown-divider { border-color: var(--rs-border); }


/*
|--------------------------------------------------------------------------
| EMPTY STATE
|--------------------------------------------------------------------------
*/

.rs-empty { text-align: center; padding: 70px 20px; color: var(--rs-muted); }
.rs-empty i { font-size: 2.4rem; opacity: .25; margin-bottom: 12px; display: block; }
.rs-empty-title { color: var(--rs-text); font-size: .95rem; font-weight: 700; margin-bottom: 6px; }
.rs-empty-text { font-size: .78rem; margin-bottom: 20px; }


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

.rs-pagination { padding: 14px 18px; border-top: 1px solid var(--rs-border); }
.rs-pagination .pagination { margin: 0; }
.rs-pagination .page-link { background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-muted); font-size: .75rem; }
.rs-pagination .page-link:hover { background: rgba(255,255,255,.07); color: var(--rs-text); }
.rs-pagination .page-item.active .page-link { background: var(--rs-indigo); border-color: var(--rs-indigo); color: #fff; }


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 1150px) {
    .rs-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

@media (max-width: 600px) {
    .rs-stats { grid-template-columns: 1fr; }
    .rs-header-content { align-items: flex-start; }
    .rs-btn-primary { width: 100%; justify-content: center; }
}

</style>


<div class="rs-wrap">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="rs-header">

        <div class="rs-header-content">

            <div>

                <div class="rs-title">
                    <i class="bi bi-buildings me-2"></i>
                    My Resorts
                </div>

                <div class="rs-subtitle">
                    Manage your resorts, rooms, facilities and bookings from one place.
                </div>

            </div>

            <a href="{{ route('vendor.resorts.create') }}" class="rs-btn-primary">
                <i class="bi bi-plus-lg"></i>
                Add New Resort
            </a>

        </div>

    </div>


    {{-- =====================================================
         ALERTS
    ====================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    @php
        $totalResorts    = $resorts->total();
        $activeResorts   = $resorts->where('status', 1)->count();
        $featuredResorts = $resorts->where('is_featured', 1)->count();
        $verifiedResorts = $resorts->where('is_verified', 1)->count();
    @endphp

    <div class="rs-stats">

        <div class="rs-stat rs-stat-total">
            <div class="rs-stat-icon"><i class="bi bi-buildings"></i></div>
            <div>
                <div class="rs-stat-label">Total Resorts</div>
                <div class="rs-stat-value">{{ $totalResorts }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-active">
            <div class="rs-stat-icon"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="rs-stat-label">Active Resorts</div>
                <div class="rs-stat-value">{{ $activeResorts }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-featured">
            <div class="rs-stat-icon"><i class="bi bi-star"></i></div>
            <div>
                <div class="rs-stat-label">Featured Resorts</div>
                <div class="rs-stat-value">{{ $featuredResorts }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-verified">
            <div class="rs-stat-icon"><i class="bi bi-patch-check"></i></div>
            <div>
                <div class="rs-stat-label">Verified Resorts</div>
                <div class="rs-stat-value">{{ $verifiedResorts }}</div>
            </div>
        </div>

    </div>


    {{-- =====================================================
         RESORT MANAGEMENT CARD
    ====================================================== --}}

    <div class="rs-card">

        <div class="rs-toolbar">

            <div class="rs-toolbar-title">
                <i class="bi bi-layer-group"></i>
                All Resorts
            </div>

            <span class="rs-total-badge">
                <i class="bi bi-buildings"></i>
                {{ $resorts->total() }} {{ $resorts->total() == 1 ? 'Resort' : 'Resorts' }}
            </span>

        </div>


        @if($resorts->count())

            <div class="table-responsive">

                <table class="rs-table">

                    <thead>
                        <tr>
                            <th class="ps-4">Resort</th>
                            <th>Destination</th>
                            <th>Location</th>
                            <th>Rooms</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($resorts as $resort)

                            <tr>

                                {{-- RESORT --}}
                                <td class="ps-4">

                                    <div class="rs-resort-info">

                                        @if($resort->featured_image)
                                            <img src="{{ asset('storage/' . $resort->featured_image) }}" alt="{{ $resort->name }}" class="rs-resort-image">
                                        @else
                                            <div class="rs-resort-placeholder"><i class="bi bi-building"></i></div>
                                        @endif

                                        <div>

                                            <div class="rs-resort-name">{{ $resort->name }}</div>

                                            @if($resort->slug)
                                                <div class="rs-resort-slug">/{{ $resort->slug }}</div>
                                            @endif

                                            <div class="rs-tags">

                                                @if($resort->is_featured)
                                                    <span class="rs-mini-tag rs-tag-featured">
                                                        <i class="bi bi-star-fill"></i> Featured
                                                    </span>
                                                @endif

                                                @if($resort->is_verified)
                                                    <span class="rs-mini-tag rs-tag-verified">
                                                        <i class="bi bi-patch-check-fill"></i> Verified
                                                    </span>
                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- DESTINATION --}}
                                <td>
                                    @if($resort->destination)
                                        <span class="rs-destination">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            {{ $resort->destination->name }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="color:var(--rs-muted) !important;">N/A</span>
                                    @endif
                                </td>


                                {{-- LOCATION --}}
                                <td>
                                    @if($resort->district)
                                        <div class="rs-location-main">{{ $resort->district }}</div>
                                    @endif

                                    @if($resort->division)
                                        <div class="rs-location-sub">{{ $resort->division }}</div>
                                    @endif

                                    @if($resort->area)
                                        <div class="rs-location-sub">{{ $resort->area }}</div>
                                    @endif

                                    @if(!$resort->district && !$resort->division && !$resort->area)
                                        <span style="color:var(--rs-muted);">N/A</span>
                                    @endif
                                </td>


                                {{-- ROOMS --}}
                                <td>
                                    <div class="rs-room-count">
                                        <span class="rs-room-number">{{ $resort->rooms_count ?? $resort->rooms->count() }}</span>
                                        <span class="rs-room-label">rooms</span>
                                    </div>
                                </td>


                                {{-- RATING --}}
                                <td>
                                    <div>
                                        <i class="bi bi-star-fill rs-rating-star"></i>
                                        <span class="rs-rating-value">{{ number_format($resort->rating ?? 0, 1) }}</span>
                                    </div>
                                    <span class="rs-review-count">{{ $resort->total_reviews ?? 0 }} reviews</span>
                                </td>


                                {{-- STATUS --}}
                                <td>
                                    @if($resort->status)
                                        <span class="rs-status-badge rs-status-active">
                                            <i class="bi bi-check-circle-fill"></i> Active
                                        </span>
                                    @else
                                        <span class="rs-status-badge rs-status-inactive">
                                            <i class="bi bi-x-circle-fill"></i> Inactive
                                        </span>
                                    @endif
                                </td>


                                {{-- ACTION --}}
                                <td class="text-end pe-4">

                                    <div class="dropdown">

                                        <button class="rs-action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="More actions">
                                            <i class="bi bi-three-dots"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end">

                                            <li>
                                                <a class="dropdown-item" href="{{ route('vendor.rooms.index', ['resort' => $resort->id]) }}">
                                                    <i class="bi bi-door-open me-2"></i> Manage Rooms
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item" href="{{ route('vendor.resorts.edit', $resort->slug) }}">
                                                    <i class="bi bi-pencil-square me-2"></i> Edit Resort
                                                </a>
                                            </li>

                                            <li><hr class="dropdown-divider"></li>

                                            <li>
                                                <form action="{{ route('vendor.resorts.delete', $resort->id) }}" method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this resort?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash3 me-2"></i> Delete Resort
                                                    </button>
                                                </form>
                                            </li>

                                        </ul>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            @if($resorts->hasPages())
                <div class="rs-pagination">
                    {{ $resorts->links() }}
                </div>
            @endif


        @else

            <div class="rs-empty">
                <i class="bi bi-building"></i>
                <div class="rs-empty-title">No Resorts Yet</div>
                <div class="rs-empty-text">You haven't added any resort to your vendor account yet.</div>
                <a href="{{ route('vendor.resorts.create') }}" class="rs-btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Add Your First Resort
                </a>
            </div>

        @endif

    </div>

</div>

@endsection