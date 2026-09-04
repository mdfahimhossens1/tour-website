@extends('layouts.admin')

@section('title', 'Notifications')

@section('page')

<style>
    .notification-page {
        padding-bottom: 30px;
    }

    .notification-stat-card {
        border: 0;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
        height: 100%;
        transition: all 0.2s ease;
    }

    .notification-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .notification-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
    }

    .notification-stat-title {
        font-size: 13px;
        color: #7a8494;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .notification-stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1;
    }

    .notification-toolbar {
        background: #fff;
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
        margin-bottom: 18px;
    }

    .notification-toolbar .form-control,
    .notification-toolbar .form-select {
        min-height: 42px;
        border-radius: 9px;
        border-color: #e3e7ed;
        font-size: 14px;
    }

    .notification-toolbar .form-control:focus,
    .notification-toolbar .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.08);
    }

    .notification-list-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .notification-list-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f4;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .notification-list-title {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #1f2937;
    }

    .notification-item {
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f4;
        transition: background 0.2s ease;
    }

    .notification-item:last-child {
        border-bottom: 0;
    }

    .notification-item:hover {
        background: #fafbfc;
    }

    .notification-item.unread {
        background: #f5f9ff;
    }

    .notification-item.unread:hover {
        background: #eef6ff;
    }

    .notification-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: #0d6efd;
    }

    .notification-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }

    .notification-icon-info {
        background: #e8f1ff;
        color: #0d6efd;
    }

    .notification-icon-success {
        background: #e9f8ef;
        color: #198754;
    }

    .notification-icon-warning {
        background: #fff5df;
        color: #d99000;
    }

    .notification-icon-danger {
        background: #fdebec;
        color: #dc3545;
    }

    .notification-icon-primary {
        background: #eeeafd;
        color: #6f42c1;
    }

    .notification-icon-secondary {
        background: #eef0f2;
        color: #6c757d;
    }

    .notification-icon-dark {
        background: #e9ecef;
        color: #212529;
    }

    .notification-icon-default {
        background: #eef4f8;
        color: #4b6475;
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-title-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 5px;
    }

    .notification-title {
        margin: 0;
        color: #1f2937;
        font-size: 15px;
        font-weight: 600;
        line-height: 1.4;
        text-decoration: none;
    }

    .notification-item.unread .notification-title {
        font-weight: 700;
    }

    .notification-message {
        color: #6b7280;
        font-size: 13px;
        line-height: 1.6;
        margin: 0 0 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        color: #8993a4;
        font-size: 12px;
    }

    .notification-meta i {
        margin-right: 4px;
    }

    .notification-type-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .type-info {
        background: #e8f1ff;
        color: #0d6efd;
    }

    .type-success {
        background: #e9f8ef;
        color: #198754;
    }

    .type-warning {
        background: #fff5df;
        color: #b57900;
    }

    .type-danger {
        background: #fdebec;
        color: #dc3545;
    }

    .type-primary {
        background: #eeeafd;
        color: #6f42c1;
    }

    .type-secondary {
        background: #eef0f2;
        color: #6c757d;
    }

    .type-dark {
        background: #e9ecef;
        color: #212529;
    }

    .type-default {
        background: #eef4f8;
        color: #4b6475;
    }

    .notification-actions {
        display: flex;
        align-items: center;
        gap: 5px;
        flex-shrink: 0;
    }

    .notification-action-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: 1px solid #e4e8ed;
        background: #fff;
        color: #6b7280;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .notification-action-btn:hover {
        background: #f5f7fa;
        color: #212529;
        border-color: #d8dde4;
    }

    .notification-action-btn.read:hover {
        color: #198754;
        border-color: #b7e4c7;
    }

    .notification-action-btn.unread:hover {
        color: #0d6efd;
        border-color: #b6d4fe;
    }

    .notification-action-btn.delete:hover {
        color: #dc3545;
        border-color: #f1b0b7;
        background: #fff5f5;
    }

    .notification-unread-dot {
        width: 8px;
        height: 8px;
        background: #0d6efd;
        border-radius: 50%;
        display: inline-block;
    }

    .notification-empty {
        padding: 70px 20px;
        text-align: center;
    }

    .notification-empty-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 18px;
        border-radius: 18px;
        background: #f1f4f7;
        color: #9aa4b2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .notification-empty h5 {
        margin-bottom: 7px;
        font-weight: 700;
        color: #374151;
    }

    .notification-empty p {
        margin: 0;
        color: #8a94a6;
        font-size: 14px;
    }

    .notification-pagination {
        padding: 16px 20px;
        border-top: 1px solid #edf0f4;
    }

    .notification-pagination .pagination {
        margin-bottom: 0;
        justify-content: flex-end;
    }

    .notification-pagination .page-link {
        border-radius: 7px;
        margin: 0 2px;
        border-color: #e3e7ed;
        color: #495057;
        font-size: 13px;
    }

    .notification-pagination .page-item.active .page-link {
        color: #fff;
    }

    .notification-user {
        font-weight: 600;
        color: #4b5563;
    }

    .notification-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    @media (max-width: 767.98px) {

        .notification-toolbar {
            padding: 14px;
        }

        .notification-list-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .notification-header-actions {
            width: 100%;
        }

        .notification-item {
            padding: 15px;
            gap: 11px;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            font-size: 15px;
            border-radius: 10px;
        }

        .notification-actions {
            position: absolute;
            right: 12px;
            top: 14px;
        }

        .notification-content {
            padding-right: 72px;
        }

        .notification-stat-value {
            font-size: 21px;
        }
    }
</style>

@php

    /*
    |--------------------------------------------------------------------------
    | Helper: Notification Type
    |--------------------------------------------------------------------------
    */

    $getTypeClass = function ($type) {

        return match (strtolower($type ?? 'info')) {

            'info'      => 'info',
            'success'   => 'success',
            'warning'   => 'warning',
            'danger'    => 'danger',
            'primary'   => 'primary',
            'secondary' => 'secondary',
            'dark'      => 'dark',

            default     => 'default',
        };
    };


    $getTypeIcon = function ($type) {

        return match (strtolower($type ?? 'info')) {

            'success' => 'fas fa-check-circle',

            'warning' => 'fas fa-exclamation-triangle',

            'danger' => 'fas fa-times-circle',

            'primary' => 'fas fa-star',

            'secondary' => 'fas fa-info-circle',

            'dark' => 'fas fa-bell',

            'info' => 'fas fa-info-circle',

            default => 'fas fa-bell',
        };
    };

@endphp


<div class="container-fluid notification-page">

    {{-- ==========================================================
        PAGE HEADER
    =========================================================== --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                <i class="fas fa-bell me-2"></i>
                Notifications
            </h4>

            <p class="text-muted mb-0 small">
                Manage and monitor system notifications.
            </p>

        </div>

        <div class="notification-header-actions">

            @if($unreadNotifications > 0)

                <form action="{{ route('admin.notifications.mark-all-read') }}"
                      method="POST"
                      class="d-inline">

                    @csrf

                    <button type="submit"
                            class="btn btn-outline-success btn-sm">

                        <i class="fas fa-check-double me-1"></i>

                        Mark All as Read

                    </button>

                </form>

            @endif


            @if($readNotifications > 0)

                <form action="{{ route('admin.notifications.destroy-read') }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Are you sure you want to delete all read notifications?');">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-outline-danger btn-sm">

                        <i class="fas fa-trash-alt me-1"></i>

                        Delete Read

                    </button>

                </form>

            @endif

        </div>

    </div>


    {{-- ==========================================================
        SUCCESS / ERROR ALERTS
    =========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <i class="fas fa-exclamation-circle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif


    {{-- ==========================================================
        STATISTICS
    =========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}

        <div class="col-xl-3 col-md-6">

            <div class="notification-stat-card p-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="notification-stat-icon"
                         style="background:#e8f1ff;color:#0d6efd;">

                        <i class="fas fa-bell"></i>

                    </div>

                    <div>

                        <div class="notification-stat-title">
                            Total Notifications
                        </div>

                        <div class="notification-stat-value">
                            {{ number_format($totalNotifications) }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Unread --}}

        <div class="col-xl-3 col-md-6">

            <div class="notification-stat-card p-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="notification-stat-icon"
                         style="background:#fff5df;color:#d99000;">

                        <i class="fas fa-envelope"></i>

                    </div>

                    <div>

                        <div class="notification-stat-title">
                            Unread
                        </div>

                        <div class="notification-stat-value">
                            {{ number_format($unreadNotifications) }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Read --}}

        <div class="col-xl-3 col-md-6">

            <div class="notification-stat-card p-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="notification-stat-icon"
                         style="background:#e9f8ef;color:#198754;">

                        <i class="fas fa-envelope-open"></i>

                    </div>

                    <div>

                        <div class="notification-stat-title">
                            Read
                        </div>

                        <div class="notification-stat-value">
                            {{ number_format($readNotifications) }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Today --}}

        <div class="col-xl-3 col-md-6">

            <div class="notification-stat-card p-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="notification-stat-icon"
                         style="background:#eeeafd;color:#6f42c1;">

                        <i class="fas fa-calendar-day"></i>

                    </div>

                    <div>

                        <div class="notification-stat-title">
                            Today
                        </div>

                        <div class="notification-stat-value">
                            {{ number_format($todayNotifications) }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        FILTER / SEARCH
    =========================================================== --}}

    <div class="notification-toolbar">

        <form method="GET"
              action="{{ route('admin.notifications.index') }}">

            <div class="row g-2 align-items-end">

                {{-- Search --}}

                <div class="col-xl-4 col-lg-4 col-md-6">

                    <label class="form-label small fw-semibold">
                        Search
                    </label>

                    <div class="input-group">

                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Search title, message or type...">

                    </div>

                </div>


                {{-- Status --}}

                <div class="col-xl-2 col-lg-2 col-md-6">

                    <label class="form-label small fw-semibold">
                        Status
                    </label>

                    <select name="status"
                            class="form-select">

                        <option value="">
                            All Status
                        </option>

                        <option value="unread"
                            {{ request('status') === 'unread' ? 'selected' : '' }}>
                            Unread
                        </option>

                        <option value="read"
                            {{ request('status') === 'read' ? 'selected' : '' }}>
                            Read
                        </option>

                    </select>

                </div>


                {{-- Type --}}

                <div class="col-xl-2 col-lg-2 col-md-6">

                    <label class="form-label small fw-semibold">
                        Type
                    </label>

                    <select name="type"
                            class="form-select">

                        <option value="">
                            All Types
                        </option>

                        @foreach($types as $type)

                            @php
                                $typeLabel = ucwords(
                                    str_replace(
                                        ['-', '_'],
                                        ' ',
                                        $type
                                    )
                                );
                            @endphp

                            <option value="{{ $type }}"
                                {{ request('type') === $type ? 'selected' : '' }}>

                                {{ $typeLabel }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Date From --}}

                <div class="col-xl-1 col-lg-2 col-md-6">

                    <label class="form-label small fw-semibold">
                        From
                    </label>

                    <input type="date"
                           name="date_from"
                           value="{{ request('date_from') }}"
                           class="form-control">

                </div>


                {{-- Date To --}}

                <div class="col-xl-1 col-lg-2 col-md-6">

                    <label class="form-label small fw-semibold">
                        To
                    </label>

                    <input type="date"
                           name="date_to"
                           value="{{ request('date_to') }}"
                           class="form-control">

                </div>


                {{-- Buttons --}}

                <div class="col-xl-2 col-lg-2 col-md-6">

                    <div class="d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary flex-grow-1">

                            <i class="fas fa-filter me-1"></i>

                            Filter

                        </button>

                        <a href="{{ route('admin.notifications.index') }}"
                           class="btn btn-outline-secondary"
                           title="Reset Filters">

                            <i class="fas fa-sync-alt"></i>

                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>


    {{-- ==========================================================
        NOTIFICATION LIST
    =========================================================== --}}

    <div class="notification-list-card">

        <div class="notification-list-header">

            <div>

                <h5 class="notification-list-title">

                    <i class="fas fa-list-ul me-2"></i>

                    Notification Inbox

                </h5>

                <small class="text-muted">

                    Showing
                    {{ $notifications->firstItem() ?? 0 }}
                    –
                    {{ $notifications->lastItem() ?? 0 }}

                    of

                    {{ number_format($notifications->total()) }}

                    notifications

                </small>

            </div>


            @if(request()->hasAny([
                'search',
                'status',
                'type',
                'date_from',
                'date_to'
            ]))

                <a href="{{ route('admin.notifications.index') }}"
                   class="btn btn-light btn-sm">

                    <i class="fas fa-times me-1"></i>

                    Clear Filters

                </a>

            @endif

        </div>


        {{-- ======================================================
            ITEMS
        ======================================================= --}}

        @forelse($notifications as $notification)

            @php

                $typeClass = $getTypeClass(
                    $notification->type
                );

                $typeIcon = $getTypeIcon(
                    $notification->type
                );

                $typeLabel = ucwords(
                    str_replace(
                        ['-', '_'],
                        ' ',
                        $notification->type ?? 'info'
                    )
                );

            @endphp


            <div class="notification-item
                {{ !$notification->is_read ? 'unread' : '' }}">


                {{-- Icon --}}

                <div class="notification-icon notification-icon-{{ $typeClass }}">

                    <i class="{{ $typeIcon }}"></i>

                </div>


                {{-- Content --}}

                <div class="notification-content">

                    <div class="notification-title-row">

                        <a href="{{ route(
                            'admin.notifications.show',
                            $notification->id
                        ) }}"
                           class="notification-title">

                            {{ $notification->title }}

                        </a>


                        <span class="notification-type-badge type-{{ $typeClass }}">

                            {{ $typeLabel }}

                        </span>


                        @if(!$notification->is_read)

                            <span class="notification-unread-dot"
                                  title="Unread"></span>

                        @endif

                    </div>


                    <p class="notification-message">

                        {{ $notification->message }}

                    </p>


                    <div class="notification-meta">

                        {{-- User --}}

                        @if($notification->user)

                            <span>

                                <i class="fas fa-user"></i>

                                <span class="notification-user">

                                    {{ $notification->user->name ?? 'User' }}

                                </span>

                            </span>

                        @else

                            <span>

                                <i class="fas fa-user"></i>

                                System

                            </span>

                        @endif


                        {{-- Time --}}

                        <span>

                            <i class="far fa-clock"></i>

                            {{ $notification->created_at?->diffForHumans() }}

                        </span>


                        {{-- Date --}}

                        <span>

                            <i class="far fa-calendar"></i>

                            {{ $notification->created_at?->format('d M Y, h:i A') }}

                        </span>


                        {{-- Status --}}

                        <span>

                            <i class="fas
                                {{ $notification->is_read
                                    ? 'fa-envelope-open text-success'
                                    : 'fa-envelope text-primary'
                                }}"></i>

                            {{ $notification->is_read ? 'Read' : 'Unread' }}

                        </span>

                    </div>

                </div>


                {{-- Actions --}}

                <div class="notification-actions">

                    {{-- View --}}

                    <a href="{{ route(
                        'admin.notifications.show',
                        $notification->id
                    ) }}"
                       class="notification-action-btn"
                       title="View Notification">

                        <i class="fas fa-eye"></i>

                    </a>


                    {{-- Read / Unread --}}

                    @if($notification->is_read)

                        <form action="{{ route(
                            'admin.notifications.mark-unread',
                            $notification->id
                        ) }}"
                              method="POST"
                              class="d-inline">

                            @csrf

                            <button type="submit"
                                    class="notification-action-btn unread"
                                    title="Mark as Unread">

                                <i class="fas fa-envelope"></i>

                            </button>

                        </form>

                    @else

                        <form action="{{ route(
                            'admin.notifications.mark-read',
                            $notification->id
                        ) }}"
                              method="POST"
                              class="d-inline">

                            @csrf

                            <button type="submit"
                                    class="notification-action-btn read"
                                    title="Mark as Read">

                                <i class="fas fa-envelope-open"></i>

                            </button>

                        </form>

                    @endif


                    {{-- Delete --}}

                    <form action="{{ route(
                        'admin.notifications.destroy',
                        $notification->id
                    ) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this notification?');">

                        @csrf

                        @method('DELETE')

                        <button type="submit"
                                class="notification-action-btn delete"
                                title="Delete">

                            <i class="fas fa-trash-alt"></i>

                        </button>

                    </form>

                </div>

            </div>

        @empty

            {{-- ==================================================
                EMPTY STATE
            =================================================== --}}

            <div class="notification-empty">

                <div class="notification-empty-icon">

                    <i class="far fa-bell-slash"></i>

                </div>

                <h5>
                    No Notifications Found
                </h5>

                <p>
                    There are no notifications matching your current filters.
                </p>

                @if(request()->hasAny([
                    'search',
                    'status',
                    'type',
                    'date_from',
                    'date_to'
                ]))

                    <a href="{{ route('admin.notifications.index') }}"
                       class="btn btn-outline-primary btn-sm mt-3">

                        <i class="fas fa-sync-alt me-1"></i>

                        Clear Filters

                    </a>

                @endif

            </div>

        @endforelse


        {{-- ======================================================
            PAGINATION
        ======================================================= --}}

        @if($notifications->hasPages())

            <div class="notification-pagination">

                {{ $notifications->links() }}

            </div>

        @endif

    </div>

</div>

@endsection