@extends('layouts.vendor')

@section('title', 'Room Types')

@section('page')

<style>

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
    --rs-surface: #1a1d27; --rs-surface2: #222636; --rs-border: rgba(255,255,255,.07);
    --rs-text: #e2e8f0; --rs-muted: #64748b;
    --rs-indigo: #6366f1; --rs-purple: #8b5cf6;
    --rs-success: #22c55e; --rs-cyan: #0ea5e9;
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
.rs-wrap .alert-danger { border-left: 3px solid #ef4444; }
.rs-wrap .btn-close { filter: invert(1) grayscale(1) opacity(.6); }

.rs-notice {
    display: flex; gap: 12px; align-items: flex-start;
    background: rgba(14,165,233,.08); border: 1px solid rgba(14,165,233,.2);
    border-radius: 12px; padding: 16px 18px; margin-bottom: 22px;
}
.rs-notice i { color: #7dd3fc; font-size: 1.1rem; margin-top: 2px; }
.rs-notice strong { display: block; font-size: .85rem; color: var(--rs-text); margin-bottom: 3px; }
.rs-notice p { margin: 0; font-size: .78rem; color: var(--rs-muted); }

.rs-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 22px; }
.rs-stat { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); padding: 18px 20px; box-shadow: var(--rs-shadow); display: flex; align-items: center; gap: 14px; }
.rs-stat-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.rs-stat-total .rs-stat-icon { background: rgba(99,102,241,.12); color: #a5b4fc; }
.rs-stat-page .rs-stat-icon  { background: rgba(34,197,94,.12);  color: #86efac; }
.rs-stat-used .rs-stat-icon  { background: rgba(14,165,233,.12); color: #7dd3fc; }
.rs-stat-label { color: var(--rs-muted); font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
.rs-stat-value { color: var(--rs-text); font-size: 1.15rem; font-family: 'JetBrains Mono', monospace; font-weight: 700; margin-top: 3px; }

.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; }
.rs-toolbar { padding: 17px 20px; border-bottom: 1px solid var(--rs-border); }
.rs-toolbar h2 { font-size: .95rem; font-weight: 700; margin: 0 0 3px; color: var(--rs-text); }
.rs-toolbar span { font-size: .74rem; color: var(--rs-muted); }

.rs-table { width: 100%; border-collapse: collapse; }
.rs-table thead tr { background: var(--rs-surface2); border-bottom: 1px solid var(--rs-border); }
.rs-table th { padding: 13px 17px; text-align: left; color: var(--rs-muted); font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; white-space: nowrap; }
.rs-table td { padding: 15px 17px; border-bottom: 1px solid var(--rs-border); font-size: .79rem; vertical-align: middle; color: var(--rs-text); }
.rs-table tbody tr { transition: background .15s; }
.rs-table tbody tr:hover { background: rgba(255,255,255,.02); }
.rs-table tbody tr:last-child td { border-bottom: none; }

.rs-type-icon { width: 40px; height: 40px; border-radius: 10px; background: rgba(99,102,241,.12); color: #a5b4fc; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.rs-type-name { font-weight: 600; font-size: .82rem; }
.rs-type-sub { color: var(--rs-muted); font-size: .68rem; margin-top: 2px; }

.rs-icon-code { background: var(--rs-surface2); border: 1px solid var(--rs-border); color: #cbd5e1; padding: 3px 8px; border-radius: 6px; font-family: 'JetBrains Mono', monospace; font-size: .72rem; }

.rs-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 9px; border-radius: 7px; font-size: .68rem; font-weight: 700; }
.rs-badge-used { background: rgba(34,197,94,.1); color: #86efac; border: 1px solid rgba(34,197,94,.18); }
.rs-badge-unused { background: rgba(255,255,255,.04); color: var(--rs-muted); border: 1px solid var(--rs-border); }
.rs-badge-available { background: rgba(34,197,94,.1); color: #86efac; border: 1px solid rgba(34,197,94,.18); }

.rs-empty { text-align: center; padding: 70px 20px; color: var(--rs-muted); }
.rs-empty i { font-size: 2.4rem; opacity: .25; margin-bottom: 12px; display: block; }
.rs-empty-title { color: var(--rs-text); font-size: .95rem; font-weight: 700; margin-bottom: 6px; }
.rs-empty-text { font-size: .78rem; }

.rs-pagination { padding: 14px 18px; border-top: 1px solid var(--rs-border); }
.rs-pagination .pagination { margin: 0; }
.rs-pagination .page-link { background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-muted); font-size: .75rem; }
.rs-pagination .page-link:hover { background: rgba(255,255,255,.07); color: var(--rs-text); }
.rs-pagination .page-item.active .page-link { background: var(--rs-indigo); border-color: var(--rs-indigo); color: #fff; }

@media (max-width: 700px) { .rs-stats { grid-template-columns: 1fr; } }

</style>


<div class="rs-wrap">


    {{-- HEADER --}}

    <div class="rs-header">
        <div class="rs-header-content">
            <div class="rs-title"><i class="bi bi-grid-3x3-gap me-2"></i> Room Types</div>
            <div class="rs-subtitle">View the room types available for your rooms.</div>
        </div>
    </div>


    {{-- MESSAGES --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif





    {{-- STATS --}}

    <div class="rs-stats">

        <div class="rs-stat rs-stat-total">
            <div class="rs-stat-icon"><i class="bi bi-grid-3x3-gap"></i></div>
            <div>
                <div class="rs-stat-label">Available Room Types</div>
                <div class="rs-stat-value">{{ $roomTypes->total() }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-page">
            <div class="rs-stat-icon"><i class="bi bi-list-check"></i></div>
            <div>
                <div class="rs-stat-label">Showing</div>
                <div class="rs-stat-value">{{ $roomTypes->count() }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-used">
            <div class="rs-stat-icon"><i class="bi bi-door-open"></i></div>
            <div>
                <div class="rs-stat-label">Types In Use</div>
                <div class="rs-stat-value">{{ $roomTypes->where('rooms_count', '>', 0)->count() }}</div>
            </div>
        </div>

    </div>


    {{-- TABLE --}}

    <div class="rs-card">

        <div class="rs-toolbar">
            <h2>Available Room Types</h2>
            <span>Room types provided by the platform administrator.</span>
        </div>

        @if($roomTypes->count())

            <div class="table-responsive">

                <table class="rs-table">

                    <thead>
                        <tr>
                            <th class="ps-4" style="width:80px;">#</th>
                            <th>Room Type</th>
                            <th>Icon</th>
                            <th>Your Rooms</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($roomTypes as $roomType)

                            <tr>

                                <td class="ps-4" style="color:var(--rs-muted);">#{{ $roomType->id }}</td>

                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rs-type-icon">
                                            @if($roomType->icon)
                                                <i class="{{ $roomType->icon }}"></i>
                                            @else
                                                <i class="bi bi-grid"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="rs-type-name">{{ $roomType->name }}</div>
                                            <div class="rs-type-sub">Platform Room Type</div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if($roomType->icon)
                                        <span class="rs-icon-code">{{ $roomType->icon }}</span>
                                    @else
                                        <span style="color:var(--rs-muted);">Not set</span>
                                    @endif
                                </td>

                                <td>
                                    @if($roomType->rooms_count > 0)
                                        <span class="rs-badge rs-badge-used">{{ $roomType->rooms_count }} {{ $roomType->rooms_count == 1 ? 'Room' : 'Rooms' }}</span>
                                    @else
                                        <span class="rs-badge rs-badge-unused">No Rooms</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="rs-badge rs-badge-available"><i class="bi bi-check-circle"></i> Available</span>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="rs-pagination">
                {{ $roomTypes->links() }}
            </div>

        @else

            <div class="rs-empty">
                <i class="bi bi-grid-3x3-gap"></i>
                <div class="rs-empty-title">No Room Types Available</div>
                <div class="rs-empty-text">No room types have been provided by the platform administrator yet.</div>
            </div>

        @endif

    </div>

</div>

@endsection