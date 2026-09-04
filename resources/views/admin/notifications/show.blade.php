@extends('layouts.admin')

@section('title', 'Notification Details')

@section('page')

<style>
    .notification-details-page {
        padding-bottom: 30px;
    }

    .notification-detail-card {
        background: #fff;
        border: 0;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .notification-detail-header {
        padding: 22px 24px;
        border-bottom: 1px solid #edf0f4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
    }

    .notification-detail-title {
        margin: 0;
        font-size: 19px;
        font-weight: 700;
        color: #1f2937;
    }

    .notification-detail-subtitle {
        margin: 5px 0 0;
        color: #8a94a6;
        font-size: 13px;
    }

    .notification-detail-body {
        padding: 28px 24px;
    }

    .notification-main-icon {
        width: 72px;
        height: 72px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
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
        color: #b57900;
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

    .notification-main-heading {
        font-size: 21px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 8px;
        line-height: 1.4;
    }

    .notification-type-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
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

    .notification-message-box {
        margin-top: 28px;
        padding: 22px;
        background: #f8fafc;
        border: 1px solid #edf0f4;
        border-radius: 12px;
    }

    .notification-message-label {
        font-size: 12px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 12px;
    }

    .notification-message {
        color: #374151;
        font-size: 15px;
        line-height: 1.8;
        white-space: pre-line;
        margin: 0;
    }

    .notification-meta-list {
        margin-top: 25px;
    }

    .notification-meta-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 0;
        border-bottom: 1px solid #edf0f4;
    }

    .notification-meta-item:last-child {
        border-bottom: 0;
    }

    .notification-meta-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: #f2f5f8;
        color: #667085;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .notification-meta-label {
        font-size: 11px;
        color: #8a94a6;
        margin-bottom: 2px;
    }

    .notification-meta-value {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .notification-sidebar-card {
        background: #fff;
        border: 0;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 18px;
    }

    .notification-sidebar-header {
        padding: 17px 18px;
        border-bottom: 1px solid #edf0f4;
        font-size: 15px;
        font-weight: 700;
        color: #374151;
    }

    .notification-sidebar-body {
        padding: 18px;
    }

    .status-box {
        padding: 16px;
        border-radius: 11px;
        margin-bottom: 15px;
    }

    .status-box.read {
        background: #edf9f1;
        border: 1px solid #d5f0df;
    }

    .status-box.unread {
        background: #eef6ff;
        border: 1px solid #d5e8ff;
    }

    .status-box-title {
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .status-box.read .status-box-title {
        color: #198754;
    }

    .status-box.unread .status-box-title {
        color: #0d6efd;
    }

    .status-box-text {
        font-size: 12px;
        color: #6b7280;
        margin: 0;
        line-height: 1.5;
    }

    .notification-action {
        width: 100%;
        min-height: 42px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 9px;
    }

    .notification-action:last-child {
        margin-bottom: 0;
    }

    .notification-user-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px;
        background: #f8fafc;
        border-radius: 11px;
        border: 1px solid #edf0f4;
    }

    .notification-user-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #e8f1ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
    }

    .notification-user-name {
        font-size: 13px;
        font-weight: 700;
        color: #374151;
    }

    .notification-user-id {
        font-size: 11px;
        color: #8a94a6;
        margin-top: 2px;
    }

    .notification-back-btn {
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
    }

    .notification-danger-zone {
        border: 1px solid #f1d1d4;
        background: #fffafa;
        border-radius: 11px;
        padding: 15px;
    }

    .notification-danger-zone-title {
        color: #dc3545;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .notification-danger-zone-text {
        color: #8a94a6;
        font-size: 11px;
        line-height: 1.5;
        margin-bottom: 12px;
    }

    @media (max-width: 767.98px) {

        .notification-detail-body {
            padding: 20px 15px;
        }

        .notification-detail-header {
            padding: 17px 15px;
        }

        .notification-main-icon {
            width: 58px;
            height: 58px;
            font-size: 21px;
        }

        .notification-main-heading {
            font-size: 18px;
        }

        .notification-message-box {
            padding: 17px;
        }
    }
</style>

@php

    /*
    |--------------------------------------------------------------------------
    | Notification Type
    |--------------------------------------------------------------------------
    */

    $notificationType = strtolower(
        $notification->type ?? 'info'
    );


    $typeClass = match ($notificationType) {

        'info'      => 'info',
        'success'   => 'success',
        'warning'   => 'warning',
        'danger'    => 'danger',
        'primary'   => 'primary',
        'secondary' => 'secondary',
        'dark'      => 'dark',

        default     => 'default',
    };


    $typeIcon = match ($notificationType) {

        'success' =>
            'fas fa-check-circle',

        'warning' =>
            'fas fa-exclamation-triangle',

        'danger' =>
            'fas fa-times-circle',

        'primary' =>
            'fas fa-star',

        'secondary' =>
            'fas fa-info-circle',

        'dark' =>
            'fas fa-bell',

        'info' =>
            'fas fa-info-circle',

        default =>
            'fas fa-bell',
    };


    $typeLabel = ucwords(
        str_replace(
            ['-', '_'],
            ' ',
            $notificationType
        )
    );


    /*
    |--------------------------------------------------------------------------
    | User Information
    |--------------------------------------------------------------------------
    */

    $userName = $notification->user?->name
        ?? 'System';


    $userInitial = strtoupper(
        substr(
            $userName,
            0,
            1
        )
    );

@endphp


<div class="container-fluid notification-details-page">

    {{-- ==========================================================
        PAGE HEADER
    =========================================================== --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <div>

            <h4 class="mb-1 fw-bold">

                <i class="fas fa-bell me-2"></i>

                Notification Details

            </h4>

            <p class="text-muted mb-0 small">

                View complete notification information.

            </p>

        </div>


        <a href="{{ route('admin.notifications.index') }}"
           class="btn btn-outline-secondary notification-back-btn">

            <i class="fas fa-arrow-left me-1"></i>

            Back to Notifications

        </a>

    </div>


    {{-- ==========================================================
        ALERTS
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


    <div class="row g-4">

        {{-- ======================================================
            MAIN CONTENT
        ======================================================= --}}

        <div class="col-xl-8 col-lg-8">

            <div class="notification-detail-card">

                {{-- Header --}}

                <div class="notification-detail-header">

                    <div>

                        <h5 class="notification-detail-title">

                            Notification Information

                        </h5>

                        <p class="notification-detail-subtitle">

                            Notification #{{ $notification->id }}

                        </p>

                    </div>


                    <span class="notification-type-badge type-{{ $typeClass }}">

                        <i class="{{ $typeIcon }} me-1"></i>

                        {{ $typeLabel }}

                    </span>

                </div>


                {{-- Body --}}

                <div class="notification-detail-body">

                    {{-- Main title --}}

                    <div class="d-flex align-items-start gap-3">

                        <div class="notification-main-icon notification-icon-{{ $typeClass }}">

                            <i class="{{ $typeIcon }}"></i>

                        </div>


                        <div class="flex-grow-1">

                            <h1 class="notification-main-heading">

                                {{ $notification->title }}

                            </h1>


                            <div class="d-flex align-items-center gap-2 flex-wrap">

                                @if($notification->is_read)

                                    <span class="badge bg-success-subtle text-success">

                                        <i class="fas fa-envelope-open me-1"></i>

                                        Read

                                    </span>

                                @else

                                    <span class="badge bg-primary-subtle text-primary">

                                        <i class="fas fa-envelope me-1"></i>

                                        Unread

                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Message --}}

                    <div class="notification-message-box">

                        <div class="notification-message-label">

                            <i class="fas fa-align-left me-1"></i>

                            Message

                        </div>

                        <p class="notification-message">

                            {{ $notification->message }}

                        </p>

                    </div>


                    {{-- Metadata --}}

                    <div class="notification-meta-list">

                        {{-- Created At --}}

                        <div class="notification-meta-item">

                            <div class="notification-meta-icon">

                                <i class="far fa-calendar-alt"></i>

                            </div>

                            <div>

                                <div class="notification-meta-label">
                                    Created At
                                </div>

                                <div class="notification-meta-value">

                                    {{ $notification->created_at?->format('d M Y, h:i A') }}

                                </div>

                            </div>

                        </div>


                        {{-- Relative Time --}}

                        <div class="notification-meta-item">

                            <div class="notification-meta-icon">

                                <i class="far fa-clock"></i>

                            </div>

                            <div>

                                <div class="notification-meta-label">
                                    Time
                                </div>

                                <div class="notification-meta-value">

                                    {{ $notification->created_at?->diffForHumans() }}

                                </div>

                            </div>

                        </div>


                        {{-- Type --}}

                        <div class="notification-meta-item">

                            <div class="notification-meta-icon">

                                <i class="fas fa-tag"></i>

                            </div>

                            <div>

                                <div class="notification-meta-label">
                                    Notification Type
                                </div>

                                <div class="notification-meta-value">

                                    {{ $typeLabel }}

                                </div>

                            </div>

                        </div>


                        {{-- ID --}}

                        <div class="notification-meta-item">

                            <div class="notification-meta-icon">

                                <i class="fas fa-hashtag"></i>

                            </div>

                            <div>

                                <div class="notification-meta-label">
                                    Notification ID
                                </div>

                                <div class="notification-meta-value">

                                    #{{ $notification->id }}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ======================================================
            SIDEBAR
        ======================================================= --}}

        <div class="col-xl-4 col-lg-4">

            {{-- ==================================================
                STATUS
            =================================================== --}}

            <div class="notification-sidebar-card">

                <div class="notification-sidebar-header">

                    <i class="fas fa-info-circle me-2"></i>

                    Notification Status

                </div>


                <div class="notification-sidebar-body">

                    @if($notification->is_read)

                        <div class="status-box read">

                            <div class="status-box-title">

                                <i class="fas fa-check-circle me-1"></i>

                                Notification Read

                            </div>

                            <p class="status-box-text">

                                This notification has already been viewed.

                            </p>

                        </div>


                        {{-- Mark Unread --}}

                        <form action="{{ route(
                            'admin.notifications.mark-unread',
                            $notification->id
                        ) }}"
                              method="POST">

                            @csrf

                            <button type="submit"
                                    class="btn btn-outline-primary notification-action">

                                <i class="fas fa-envelope me-1"></i>

                                Mark as Unread

                            </button>

                        </form>

                    @else

                        <div class="status-box unread">

                            <div class="status-box-title">

                                <i class="fas fa-envelope me-1"></i>

                                Unread Notification

                            </div>

                            <p class="status-box-text">

                                This notification has not been marked as read yet.

                            </p>

                        </div>


                        {{-- Mark Read --}}

                        <form action="{{ route(
                            'admin.notifications.mark-read',
                            $notification->id
                        ) }}"
                              method="POST">

                            @csrf

                            <button type="submit"
                                    class="btn btn-success notification-action">

                                <i class="fas fa-check me-1"></i>

                                Mark as Read

                            </button>

                        </form>

                    @endif

                </div>

            </div>


            {{-- ==================================================
                USER
            =================================================== --}}

            <div class="notification-sidebar-card">

                <div class="notification-sidebar-header">

                    <i class="fas fa-user me-2"></i>

                    Notification Recipient

                </div>


                <div class="notification-sidebar-body">

                    <div class="notification-user-box">

                        <div class="notification-user-avatar">

                            {{ $userInitial }}

                        </div>


                        <div>

                            <div class="notification-user-name">

                                {{ $userName }}

                            </div>

                            <div class="notification-user-id">

                                @if($notification->user)

                                    User ID:
                                    #{{ $notification->user->id }}

                                @else

                                    System Notification

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ==================================================
                ACTIONS
            =================================================== --}}

            <div class="notification-sidebar-card">

                <div class="notification-sidebar-header">

                    <i class="fas fa-cogs me-2"></i>

                    Actions

                </div>


                <div class="notification-sidebar-body">

                    {{-- Back --}}

                    <a href="{{ route('admin.notifications.index') }}"
                       class="btn btn-light notification-action">

                        <i class="fas fa-list me-1"></i>

                        All Notifications

                    </a>


                    {{-- Delete --}}

                    <div class="notification-danger-zone mt-2">

                        <div class="notification-danger-zone-title">

                            <i class="fas fa-trash-alt me-1"></i>

                            Delete Notification

                        </div>

                        <div class="notification-danger-zone-text">

                            Deleting this notification is permanent and cannot be undone.

                        </div>


                        <form action="{{ route(
                            'admin.notifications.destroy',
                            $notification->id
                        ) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to permanently delete this notification?');">

                            @csrf

                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-outline-danger btn-sm w-100">

                                <i class="fas fa-trash-alt me-1"></i>

                                Delete Notification

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection