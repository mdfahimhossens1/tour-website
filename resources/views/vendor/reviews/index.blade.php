@extends('layouts.vendor')

@section('page')

<style>

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
    --rs-surface: #1a1d27; --rs-surface2: #222636; --rs-border: rgba(255,255,255,.07);
    --rs-text: #e2e8f0; --rs-muted: #64748b;
    --rs-indigo: #6366f1; --rs-purple: #8b5cf6;
    --rs-success: #22c55e; --rs-warning: #f59e0b; --rs-danger: #ef4444; --rs-gold: #fcd34d;
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
.rs-stat-total .rs-stat-icon    { background: rgba(99,102,241,.12); color: #a5b4fc; }
.rs-stat-rating .rs-stat-icon   { background: rgba(245,158,11,.12); color: #fcd34d; }
.rs-stat-approved .rs-stat-icon { background: rgba(34,197,94,.12);  color: #86efac; }
.rs-stat-pending .rs-stat-icon  { background: rgba(245,158,11,.12); color: #fcd34d; }
.rs-stat-label { color: var(--rs-muted); font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
.rs-stat-value { color: var(--rs-text); font-size: 1.15rem; font-family: 'JetBrains Mono', monospace; font-weight: 700; margin-top: 3px; }
.rs-stat-value small { font-size: .7rem; color: var(--rs-muted); font-family: 'Plus Jakarta Sans', sans-serif; }
.rs-stars-mini { color: var(--rs-gold); font-size: .7rem; margin-top: 4px; }

.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; margin-bottom: 20px; }
.rs-card-head { padding: 17px 22px; border-bottom: 1px solid var(--rs-border); }
.rs-card-head h2 { font-size: .95rem; font-weight: 700; margin: 0 0 3px; }
.rs-card-head span { font-size: .74rem; color: var(--rs-muted); }
.rs-card-body { padding: 22px; }

/* RATING BARS */
.rs-rating-row { display: flex; align-items: center; gap: 14px; margin-bottom: 14px; }
.rs-rating-row:last-child { margin-bottom: 0; }
.rs-rating-label { width: 46px; font-weight: 600; font-size: .82rem; flex-shrink: 0; }
.rs-rating-label i { color: var(--rs-gold); font-size: .72rem; }
.rs-rating-track { flex: 1; height: 8px; border-radius: 999px; background: var(--rs-surface2); overflow: hidden; }
.rs-rating-fill { height: 100%; background: linear-gradient(90deg, var(--rs-gold), #f59e0b); border-radius: 999px; }
.rs-rating-count { width: 90px; text-align: right; color: var(--rs-muted); font-size: .76rem; flex-shrink: 0; }

/* FILTER */
.rs-filter-row { display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 14px; align-items: end; }
@media (max-width: 800px) { .rs-filter-row { grid-template-columns: 1fr 1fr; } }
@media (max-width: 500px) { .rs-filter-row { grid-template-columns: 1fr; } }
.rs-label { display: block; font-size: .78rem; font-weight: 600; color: #cbd5e1; margin-bottom: 7px; }
.rs-input, .rs-select {
    width: 100%; background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-text);
    border-radius: 9px; padding: 10px 13px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .84rem; outline: none;
}
.rs-input::placeholder { color: var(--rs-muted); }
.rs-input:focus, .rs-select:focus { border-color: rgba(99,102,241,.5); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.rs-filter-btns { display: flex; gap: 8px; }
.rs-btn-primary { display: inline-flex; align-items: center; gap: 7px; border: none; background: linear-gradient(135deg, var(--rs-indigo), var(--rs-purple)); color: #fff; border-radius: 9px; padding: 10px 16px; font-size: .82rem; font-weight: 600; flex: 1; justify-content: center; white-space: nowrap; }
.rs-btn-reset { display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--rs-border); background: var(--rs-surface2); color: var(--rs-muted); border-radius: 9px; width: 40px; text-decoration: none; }
.rs-btn-reset:hover { color: var(--rs-text); background: rgba(255,255,255,.06); }

/* TABLE */
.rs-table { width: 100%; min-width: 1050px; border-collapse: collapse; }
.rs-table thead tr { background: var(--rs-surface2); border-bottom: 1px solid var(--rs-border); }
.rs-table th { padding: 13px 17px; text-align: left; color: var(--rs-muted); font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; white-space: nowrap; }
.rs-table td { padding: 15px 17px; border-bottom: 1px solid var(--rs-border); font-size: .79rem; vertical-align: middle; color: var(--rs-text); }
.rs-table tbody tr:hover { background: rgba(255,255,255,.02); }
.rs-table tbody tr:last-child td { border-bottom: none; }

.rs-avatar { width: 38px; height: 38px; border-radius: 50%; background: rgba(99,102,241,.12); color: #a5b4fc; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.rs-stars { color: var(--rs-gold); font-size: .78rem; }
.rs-review-text { max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block; color: #cbd5e1; }

.rs-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 9px; border-radius: 7px; font-size: .68rem; font-weight: 700; }
.rs-badge-success { background: rgba(34,197,94,.1); color: #86efac; border: 1px solid rgba(34,197,94,.18); }
.rs-badge-warning { background: rgba(245,158,11,.1); color: #fcd34d; border: 1px solid rgba(245,158,11,.18); }

.rs-view-btn { display: inline-flex; align-items: center; gap: 6px; border: 1px solid rgba(99,102,241,.3); background: rgba(99,102,241,.1); color: #a5b4fc; border-radius: 8px; padding: 7px 13px; font-size: .74rem; font-weight: 600; text-decoration: none; }
.rs-view-btn:hover { background: rgba(99,102,241,.2); color: #c7d2fe; }

.rs-empty { text-align: center; padding: 70px 20px; color: var(--rs-muted); }
.rs-empty i { font-size: 2.4rem; opacity: .25; margin-bottom: 12px; display: block; }
.rs-empty-title { color: var(--rs-text); font-size: .95rem; font-weight: 700; margin-bottom: 6px; }

@media (max-width: 900px) { .rs-stats { grid-template-columns: 1fr 1fr; } }
@media (max-width: 600px) { .rs-stats { grid-template-columns: 1fr; } }

</style>


<div class="rs-wrap">


    {{-- HEADER --}}

    <div class="rs-header">
        <div class="rs-header-content">
            <div class="rs-title"><i class="bi bi-chat-left-text me-2"></i> Customer Reviews</div>
            <div class="rs-subtitle">View and monitor reviews submitted by customers for your tours.</div>
        </div>
    </div>


    {{-- MESSAGES --}}

    @if(session('success'))
        <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-1"></i> {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4"><i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}</div>
    @endif


    {{-- STATISTICS --}}

    <div class="rs-stats">

        <div class="rs-stat rs-stat-total">
            <div class="rs-stat-icon"><i class="bi bi-chat-left-text"></i></div>
            <div>
                <div class="rs-stat-label">Total Reviews</div>
                <div class="rs-stat-value">{{ $totalReviews }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-rating">
            <div class="rs-stat-icon"><i class="bi bi-star-fill"></i></div>
            <div>
                <div class="rs-stat-label">Average Rating</div>
                <div class="rs-stat-value">{{ number_format($averageRating, 1) }} <small>/ 5</small></div>
                <div class="rs-stars-mini">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi {{ $i <= round($averageRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                    @endfor
                </div>
            </div>
        </div>

        <div class="rs-stat rs-stat-approved">
            <div class="rs-stat-icon"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="rs-stat-label">Approved</div>
                <div class="rs-stat-value" style="color:#86efac;">{{ $approvedReviews }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-pending">
            <div class="rs-stat-icon"><i class="bi bi-clock"></i></div>
            <div>
                <div class="rs-stat-label">Pending</div>
                <div class="rs-stat-value" style="color:#fcd34d;">{{ $pendingReviews }}</div>
            </div>
        </div>

    </div>


    {{-- RATING OVERVIEW --}}

    <div class="rs-card">

        <div class="rs-card-head">
            <h2>Rating Overview</h2>
            <span>Customer rating distribution.</span>
        </div>

        <div class="rs-card-body">

            @for($rating = 5; $rating >= 1; $rating--)

                @php
                    $count = $ratingCounts[$rating] ?? 0;
                    $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
                @endphp

                <div class="rs-rating-row">
                    <div class="rs-rating-label">{{ $rating }} <i class="bi bi-star-fill"></i></div>
                    <div class="rs-rating-track"><div class="rs-rating-fill" style="width:{{ $percentage }}%;"></div></div>
                    <div class="rs-rating-count">{{ $count }} reviews</div>
                </div>

            @endfor

        </div>

    </div>


    {{-- FILTER --}}

    <div class="rs-card">
        <div class="rs-card-body">

            <form method="GET" action="{{ route('vendor.reviews.index') }}">

                <div class="rs-filter-row">

                    <div>
                        <label class="rs-label">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="rs-input" placeholder="Customer, tour or review...">
                    </div>

                    <div>
                        <label class="rs-label">Rating</label>
                        <select name="rating" class="rs-select">
                            <option value="">All Ratings</option>
                            @for($rating = 5; $rating >= 1; $rating--)
                                <option value="{{ $rating }}" @selected(request('rating') == $rating)>{{ $rating }} Star</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="rs-label">Status</label>
                        <select name="status" class="rs-select">
                            <option value="">All</option>
                            <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        </select>
                    </div>

                    <div class="rs-filter-btns">
                        <button type="submit" class="rs-btn-primary"><i class="bi bi-search"></i> Filter</button>
                        <a href="{{ route('vendor.reviews.index') }}" class="rs-btn-reset" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>

                </div>

            </form>

        </div>
    </div>


    {{-- REVIEWS TABLE --}}

    <div class="rs-card mb-0">

        <div class="rs-card-head">
            <h2>Customer Reviews</h2>
            <span>{{ $reviews->count() }} reviews found</span>
        </div>

        @if($reviews->isNotEmpty())

            <div class="table-responsive">

                <table class="rs-table">

                    <thead>
                        <tr>
                            <th class="ps-4">Customer</th>
                            <th>Tour</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($reviews as $review)

                            <tr>

                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rs-avatar"><i class="bi bi-person"></i></div>
                                        <div>
                                            <div class="fw-semibold">{{ $review->user?->name ?? 'Unknown User' }}</div>
                                            @if($review->user?->email)
                                                <small style="color:var(--rs-muted);">{{ $review->user->email }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if($review->tour)
                                        <span class="fw-semibold">{{ $review->tour->title }}</span>
                                    @else
                                        <span style="color:var(--rs-muted);">Tour unavailable</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="rs-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= (int) $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                    <small style="color:var(--rs-muted);">{{ $review->rating }}/5</small>
                                </td>

                                <td><span class="rs-review-text" title="{{ $review->review }}">{{ $review->review }}</span></td>

                                <td>
                                    @if($review->is_approved)
                                        <span class="rs-badge rs-badge-success"><i class="bi bi-check-circle"></i> Approved</span>
                                    @else
                                        <span class="rs-badge rs-badge-warning"><i class="bi bi-clock"></i> Pending</span>
                                    @endif
                                </td>

                                <td style="color:var(--rs-muted);">{{ $review->created_at?->format('d M Y') }}</td>

                                <td class="text-end pe-4">
                                    <a href="{{ route('vendor.reviews.show', $review->id) }}" class="rs-view-btn"><i class="bi bi-eye"></i> View</a>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="rs-empty">
                <i class="bi bi-chat-square-text"></i>
                <div class="rs-empty-title">No Reviews Found</div>
                <div style="font-size:.78rem;">There are currently no customer reviews for your tours.</div>
            </div>

        @endif

    </div>

</div>

@endsection