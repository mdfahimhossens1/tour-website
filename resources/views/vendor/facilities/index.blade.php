@extends('layouts.vendor')

@section('title', 'Facilities')

@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
    --fc-surface: #1a1d27;
    --fc-surface2: #222636;
    --fc-border: rgba(255,255,255,.07);

    --fc-text: #e2e8f0;
    --fc-muted: #64748b;

    --fc-indigo: #6366f1;
    --fc-purple: #8b5cf6;

    --fc-success: #22c55e;
    --fc-warning: #f59e0b;
    --fc-danger: #ef4444;
    --fc-info: #0ea5e9;

    --fc-radius: 14px;
    --fc-shadow: 0 8px 32px rgba(0,0,0,.45);
}

.fc-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--fc-text);
}


/* =========================================================
   HEADER
========================================================= */

.fc-header {
    background:
        linear-gradient(
            135deg,
            #0c1a2e 0%,
            #0e0c2e 55%,
            #0c1a2e 100%
        );

    border-radius: var(--fc-radius);

    padding: 28px 30px;

    margin-bottom: 22px;

    box-shadow: var(--fc-shadow);

    position: relative;

    overflow: hidden;
}

.fc-header::before {
    content: '';

    position: absolute;

    inset: 0;

    background:
        url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}

.fc-header-content {
    position: relative;

    z-index: 1;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    flex-wrap: wrap;
}

.fc-title {
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

.fc-subtitle {
    color: rgba(255,255,255,.45);

    font-size: .82rem;

    margin-top: 5px;
}


/* =========================================================
   HEADER BUTTON
========================================================= */

.fc-add-btn {
    display: inline-flex;

    align-items: center;

    gap: 7px;

    background: var(--fc-indigo);

    color: #fff;

    text-decoration: none;

    border-radius: 8px;

    padding: 9px 14px;

    font-size: .76rem;

    font-weight: 600;

    transition: all .2s;
}

.fc-add-btn:hover {
    background: #4f46e5;

    color: #fff;

    transform: translateY(-1px);
}


/* =========================================================
   STAT CARDS
========================================================= */

.fc-stats {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 14px;

    margin-bottom: 22px;
}

.fc-stat {
    background: var(--fc-surface);

    border: 1px solid var(--fc-border);

    border-radius: var(--fc-radius);

    padding: 18px 20px;

    box-shadow: var(--fc-shadow);

    display: flex;

    align-items: center;

    gap: 14px;
}

.fc-stat-icon {
    width: 44px;

    height: 44px;

    border-radius: 11px;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 1rem;

    flex-shrink: 0;
}

.fc-stat-total .fc-stat-icon {
    background: rgba(99,102,241,.12);
    color: #a5b4fc;
}

.fc-stat-resort .fc-stat-icon {
    background: rgba(14,165,233,.12);
    color: #7dd3fc;
}

.fc-stat-room .fc-stat-icon {
    background: rgba(139,92,246,.12);
    color: #c4b5fd;
}

.fc-stat-active .fc-stat-icon {
    background: rgba(34,197,94,.12);
    color: #86efac;
}

.fc-stat-label {
    color: var(--fc-muted);

    font-size: .68rem;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .06em;
}

.fc-stat-value {
    color: var(--fc-text);

    font-size: 1.15rem;

    font-family: 'JetBrains Mono', monospace;

    font-weight: 700;

    margin-top: 3px;
}


/* =========================================================
   CARD
========================================================= */

.fc-card {
    background: var(--fc-surface);

    border: 1px solid var(--fc-border);

    border-radius: var(--fc-radius);

    box-shadow: var(--fc-shadow);

    overflow: hidden;
}


/* =========================================================
   TOOLBAR
========================================================= */

.fc-toolbar {
    padding: 17px 20px;

    border-bottom: 1px solid var(--fc-border);

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 12px;

    flex-wrap: wrap;
}

.fc-toolbar-title {
    font-size: .85rem;

    font-weight: 700;

    display: flex;

    align-items: center;

    gap: 8px;
}

.fc-toolbar-title i {
    color: #a5b4fc;
}


/* =========================================================
   FILTER
========================================================= */

.fc-filter {
    display: flex;

    align-items: center;

    gap: 8px;

    flex-wrap: wrap;
}

.fc-search {
    display: flex;

    align-items: center;

    gap: 8px;
}

.fc-search-input,
.fc-select {
    background: var(--fc-surface2);

    border: 1px solid var(--fc-border);

    color: var(--fc-text);

    border-radius: 8px;

    padding: 8px 11px;

    font-family: 'Plus Jakarta Sans', sans-serif;

    font-size: .75rem;

    outline: none;
}

.fc-search-input {
    width: 220px;
}

.fc-select {
    min-width: 120px;
}

.fc-search-input:focus,
.fc-select:focus {
    border-color: rgba(99,102,241,.5);

    box-shadow:
        0 0 0 3px rgba(99,102,241,.08);
}

.fc-search-input::placeholder {
    color: var(--fc-muted);
}

.fc-search-btn {
    border: none;

    background: var(--fc-indigo);

    color: #fff;

    border-radius: 8px;

    padding: 8px 13px;

    cursor: pointer;

    font-size: .75rem;

    font-weight: 600;
}

.fc-search-btn:hover {
    background: #4f46e5;
}


/* =========================================================
   TABLE
========================================================= */

.fc-table {
    width: 100%;

    border-collapse: collapse;
}

.fc-table thead tr {
    background: var(--fc-surface2);

    border-bottom: 1px solid var(--fc-border);
}

.fc-table th {
    padding: 13px 17px;

    text-align: left;

    color: var(--fc-muted);

    font-size: .66rem;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .07em;

    white-space: nowrap;
}

.fc-table td {
    padding: 15px 17px;

    border-bottom: 1px solid var(--fc-border);

    font-size: .79rem;

    vertical-align: middle;

    color: var(--fc-text);
}

.fc-table tbody tr {
    transition: background .15s;
}

.fc-table tbody tr:hover {
    background: rgba(255,255,255,.02);
}

.fc-table tbody tr:last-child td {
    border-bottom: none;
}


/* =========================================================
   FACILITY
========================================================= */

.fc-facility {
    display: flex;

    align-items: center;

    gap: 10px;
}

.fc-icon {
    width: 36px;

    height: 36px;

    border-radius: 9px;

    display: flex;

    align-items: center;

    justify-content: center;

    background: rgba(99,102,241,.12);

    color: #a5b4fc;

    border: 1px solid rgba(99,102,241,.16);

    flex-shrink: 0;
}

.fc-name {
    font-size: .78rem;

    font-weight: 600;
}

.fc-id {
    color: var(--fc-muted);

    font-family: 'JetBrains Mono', monospace;

    font-size: .63rem;

    margin-top: 2px;
}


/* =========================================================
   TYPE BADGE
========================================================= */

.fc-type {
    display: inline-flex;

    align-items: center;

    gap: 5px;

    padding: 5px 9px;

    border-radius: 7px;

    font-family: 'JetBrains Mono', monospace;

    font-size: .68rem;

    font-weight: 700;

    white-space: nowrap;
}

.fc-type-resort {
    background: rgba(14,165,233,.1);

    color: #7dd3fc;

    border: 1px solid rgba(14,165,233,.18);
}

.fc-type-room {
    background: rgba(139,92,246,.1);

    color: #c4b5fd;

    border: 1px solid rgba(139,92,246,.18);
}


/* =========================================================
   STATUS
========================================================= */

.fc-status {
    display: inline-flex;

    align-items: center;

    gap: 5px;

    padding: 5px 9px;

    border-radius: 7px;

    font-size: .68rem;

    font-weight: 700;
}

.fc-status-active {
    background: rgba(34,197,94,.1);

    color: #86efac;

    border: 1px solid rgba(34,197,94,.18);
}

.fc-status-inactive {
    background: rgba(239,68,68,.1);

    color: #fca5a5;

    border: 1px solid rgba(239,68,68,.18);
}


/* =========================================================
   ACTIONS
========================================================= */

.fc-actions {
    display: flex;

    align-items: center;

    gap: 6px;
}

.fc-action {
    width: 31px;

    height: 31px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border-radius: 7px;

    text-decoration: none;

    border: 1px solid var(--fc-border);

    transition: all .2s;
}

.fc-edit {
    background: rgba(99,102,241,.1);

    color: #a5b4fc;
}

.fc-edit:hover {
    background: rgba(99,102,241,.2);

    color: #c7d2fe;
}

.fc-delete {
    background: rgba(239,68,68,.08);

    color: #fca5a5;

    cursor: pointer;
}

.fc-delete:hover {
    background: rgba(239,68,68,.18);

    color: #fecaca;
}


/* =========================================================
   EMPTY
========================================================= */

.fc-empty {
    text-align: center;

    padding: 70px 20px;

    color: var(--fc-muted);
}

.fc-empty i {
    font-size: 2.4rem;

    opacity: .25;

    margin-bottom: 12px;

    display: block;
}

.fc-empty-title {
    color: var(--fc-text);

    font-size: .86rem;

    font-weight: 600;

    margin-bottom: 5px;
}

.fc-empty-text {
    font-size: .72rem;
}


/* =========================================================
   PAGINATION
========================================================= */

.fc-pagination {
    padding: 14px 18px;

    border-top: 1px solid var(--fc-border);
}

.fc-pagination .pagination {
    margin: 0;
}

.fc-pagination .page-link {
    background: var(--fc-surface2);

    border: 1px solid var(--fc-border);

    color: var(--fc-muted);

    font-size: .75rem;
}

.fc-pagination .page-link:hover {
    background: rgba(255,255,255,.07);

    color: var(--fc-text);
}

.fc-pagination .page-item.active .page-link {
    background: var(--fc-indigo);

    border-color: var(--fc-indigo);

    color: #fff;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1150px) {

    .fc-stats {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}

@media (max-width: 800px) {

    .fc-toolbar {
        align-items: stretch;
    }

    .fc-filter {
        width: 100%;
    }

    .fc-search {
        width: 100%;
    }

    .fc-search-input {
        width: 100%;
    }

}

@media (max-width: 600px) {

    .fc-stats {
        grid-template-columns: 1fr;
    }

}
</style>

<div class="fc-wrap">

```
{{-- =====================================================
     HEADER
====================================================== --}}

<div class="fc-header">

    <div class="fc-header-content">

        <div>

            <div class="fc-title">

                <i class="fas fa-concierge-bell me-2"></i>

                Facilities

            </div>

            <div class="fc-subtitle">

                Create and manage facilities for your resorts and rooms.

            </div>

        </div>


        <a
            href="{{ route('vendor.facilities.create') }}"
            class="fc-add-btn"
        >

            <i class="fas fa-plus"></i>

            Add Facility

        </a>

    </div>

</div>


{{-- =====================================================
     STATISTICS
====================================================== --}}

<div class="fc-stats">


    {{-- TOTAL --}}

    <div class="fc-stat fc-stat-total">

        <div class="fc-stat-icon">

            <i class="fas fa-list"></i>

        </div>

        <div>

            <div class="fc-stat-label">

                Total Facilities

            </div>

            <div class="fc-stat-value">

                {{ number_format($stats['total']) }}

            </div>

        </div>

    </div>


    {{-- RESORT --}}

    <div class="fc-stat fc-stat-resort">

        <div class="fc-stat-icon">

            <i class="fas fa-hotel"></i>

        </div>

        <div>

            <div class="fc-stat-label">

                Resort Facilities

            </div>

            <div class="fc-stat-value">

                {{ number_format($stats['resort']) }}

            </div>

        </div>

    </div>


    {{-- ROOM --}}

    <div class="fc-stat fc-stat-room">

        <div class="fc-stat-icon">

            <i class="fas fa-bed"></i>

        </div>

        <div>

            <div class="fc-stat-label">

                Room Facilities

            </div>

            <div class="fc-stat-value">

                {{ number_format($stats['room']) }}

            </div>

        </div>

    </div>


    {{-- ACTIVE --}}

    <div class="fc-stat fc-stat-active">

        <div class="fc-stat-icon">

            <i class="fas fa-check-circle"></i>

        </div>

        <div>

            <div class="fc-stat-label">

                Active Facilities

            </div>

            <div class="fc-stat-value">

                {{ number_format($stats['active']) }}

            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     TABLE CARD
====================================================== --}}

<div class="fc-card">


    {{-- TOOLBAR --}}

    <div class="fc-toolbar">

        <div class="fc-toolbar-title">

            <i class="fas fa-layer-group"></i>

            My Facilities

        </div>


        <form
            method="GET"
            action="{{ route('vendor.facilities.index') }}"
            class="fc-filter"
        >

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="fc-search-input"
                placeholder="Search facility..."
            >


            <select
                name="type"
                class="fc-select"
            >

                <option value="">
                    All Types
                </option>

                <option
                    value="resort"
                    {{ request('type') === 'resort' ? 'selected' : '' }}
                >
                    Resort
                </option>

                <option
                    value="room"
                    {{ request('type') === 'room' ? 'selected' : '' }}
                >
                    Room
                </option>

            </select>


            <select
                name="status"
                class="fc-select"
            >

                <option value="">
                    All Status
                </option>

                <option
                    value="1"
                    {{ request('status') === '1' ? 'selected' : '' }}
                >
                    Active
                </option>

                <option
                    value="0"
                    {{ request('status') === '0' ? 'selected' : '' }}
                >
                    Inactive
                </option>

            </select>


            <button
                type="submit"
                class="fc-search-btn"
            >

                <i class="fas fa-search"></i>

                Filter

            </button>

        </form>

    </div>


    {{-- =================================================
         TABLE
    ================================================== --}}

    <div class="table-responsive">

        <table class="fc-table">

            <thead>

                <tr>

                    <th>#</th>

                    <th>Facility</th>

                    <th>Type</th>

                    <th>Status</th>

                    <th>Created</th>

                    <th>Action</th>

                </tr>

            </thead>


            <tbody>


                @forelse($facilities as $key => $facility)

                    <tr>


                        {{-- SERIAL --}}

                        <td>

                            <span
                                style="
                                    color:var(--fc-muted);
                                    font-family:'JetBrains Mono',monospace;
                                    font-size:.7rem;
                                "
                            >

                                {{ str_pad(
                                    $facilities->firstItem() + $key,
                                    2,
                                    '0',
                                    STR_PAD_LEFT
                                ) }}

                            </span>

                        </td>


                        {{-- FACILITY --}}

                        <td>

                            <div class="fc-facility">

                                <div class="fc-icon">

                                    @if($facility->icon)

                                        <i class="{{ $facility->icon }}"></i>

                                    @else

                                        <i class="fas fa-star"></i>

                                    @endif

                                </div>


                                <div>

                                    <div class="fc-name">

                                        {{ $facility->name }}

                                    </div>

                                    <div class="fc-id">

                                        ID #{{ $facility->id }}

                                    </div>

                                </div>

                            </div>

                        </td>


                        {{-- TYPE --}}

                        <td>

                            @if($facility->type === 'resort')

                                <span class="fc-type fc-type-resort">

                                    <i class="fas fa-hotel"></i>

                                    Resort

                                </span>

                            @else

                                <span class="fc-type fc-type-room">

                                    <i class="fas fa-bed"></i>

                                    Room

                                </span>

                            @endif

                        </td>


                        {{-- STATUS --}}

                        <td>

                            @if($facility->status)

                                <span class="fc-status fc-status-active">

                                    <i class="fas fa-check-circle"></i>

                                    Active

                                </span>

                            @else

                                <span class="fc-status fc-status-inactive">

                                    <i class="fas fa-times-circle"></i>

                                    Inactive

                                </span>

                            @endif

                        </td>


                        {{-- CREATED --}}

                        <td>

                            <span
                                style="
                                    color:var(--fc-muted);
                                    font-size:.7rem;
                                "
                            >

                                {{ $facility->created_at?->format('d M Y') ?? '—' }}

                            </span>

                        </td>


                        {{-- ACTION --}}

                        <td>

                            <div class="fc-actions">


                                {{-- EDIT --}}

                                <a
                                    href="{{ route(
                                        'vendor.facilities.edit',
                                        $facility->id
                                    ) }}"
                                    class="fc-action fc-edit"
                                    title="Edit"
                                >

                                    <i class="fas fa-edit"></i>

                                </a>


                                {{-- DELETE --}}

                                <form
                                    action="{{ route(
                                        'vendor.facilities.destroy',
                                        $facility->id
                                    ) }}"
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this facility?');"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="fc-action fc-delete"
                                        title="Delete"
                                    >

                                        <i class="fas fa-trash"></i>

                                    </button>

                                </form>


                            </div>

                        </td>


                    </tr>

                @empty

                    <tr>

                        <td colspan="6">

                            <div class="fc-empty">

                                <i class="fas fa-concierge-bell"></i>

                                <div class="fc-empty-title">

                                    No facilities found.

                                </div>

                                <div class="fc-empty-text">

                                    Create your first resort or room facility.

                                </div>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- =================================================
         PAGINATION
    ================================================== --}}

    @if($facilities->hasPages())

        <div class="fc-pagination">

            {{ $facilities->links() }}

        </div>

    @endif


</div>
</div>

@endsection
