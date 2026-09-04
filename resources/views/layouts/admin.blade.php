
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel')</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/jsvectormap/dist/css/jsvectormap.min.css">

    <link rel="stylesheet"
          href="{{ asset('contents/admin') }}/css/all.min.css">

    <link rel="stylesheet"
          href="{{ asset('contents/admin') }}/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="{{ asset('contents/admin') }}/css/datatables.min.css">

    <link rel="stylesheet"
          href="{{ asset('contents/admin') }}/css/style.css">


    <style>

        :root {
            --sidebar-w: 260px;
            --topbar-h: 68px;
            --border: rgba(0,0,0,.06);
            --bg: #f6f7fb;
            --card: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --topbar: #ffffff;
            --sidebar: #111827;
        }

        body {
            background: var(--bg);
            color: var(--text);
        }

        .admin-layout {
            min-height: 100vh;
        }

        /* Sidebar */

        .admin-sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            position: fixed !important;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform .25s ease;
        }

        .sidebar-brand {
            height: 84px;
            flex: 0 0 auto;
        }

        .sidebar-body {
            flex: 1 1 auto;
            overflow-y: auto;
            padding: .5rem;
        }

        .sidebar-body::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar-body::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,.18);
            border-radius: 999px;
        }

        /* Main */

        .admin-main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            transition: margin-left .25s ease;
        }

        /* Topbar */

        .admin-topbar {
            height: var(--topbar-h);
            position: fixed !important;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            z-index: 900;
            border-bottom: 1px solid var(--border);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            transition: left .25s ease;
        }

        .page-wrap {
            padding: calc(var(--topbar-h) + 25px) 18px 0 18px;
        }

        .nav-link.active {
            background: rgba(255,255,255,.12);
            border-radius: 10px;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fff;
        }

        .user-dd-btn {
            border: 0;
            background: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px;
            border-radius: 12px;
        }

        .user-dd-btn:hover {
            background: rgba(0,0,0,.04);
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #111827;
            color: #fff;
            flex: 0 0 auto;
        }

        .user-meta {
            line-height: 1.1;
            text-align: left;
        }

        .user-meta .name {
            font-weight: 700;
            font-size: 13px;
            color: #111827;
        }

        .user-meta .role {
            font-size: 12px;
            color: #6b7280;
        }

        /* Desktop collapsed */

        body.sidebar-collapsed .admin-sidebar {
            transform: translateX(-100%) !important;
        }

        body.sidebar-collapsed .admin-main {
            margin-left: 0 !important;
        }

        body.sidebar-collapsed .admin-topbar {
            left: 0 !important;
        }

        /* Backdrop */

        #sidebarBackdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.35);
            z-index: 950;
            display: none;
        }

        body.sidebar-open #sidebarBackdrop {
            display: block;
        }

        /* Mobile */

        @media (max-width: 991.98px) {

            .admin-main {
                margin-left: 0 !important;
            }

            .admin-topbar {
                left: 0 !important;
            }

            .admin-sidebar {
                transform: translateX(-100%);
                position: fixed !important;
                top: 66px !important;
                left: 0 !important;
            }

            body.sidebar-open .admin-sidebar {
                transform: translateX(0);
            }
        }

        /* Dark mode */

        body.dark-mode {
            --bg: #0b1220;
            --card: #0f172a;
            --text: #e5e7eb;
            --muted: #9ca3af;
            --border: rgba(255,255,255,.08);
            --topbar: #0f172a;
            --sidebar: #0b1220;
        }

        body {
            background: var(--bg);
            color: var(--text);
        }

        .admin-topbar {
            background: var(--topbar) !important;
            border-bottom: 1px solid var(--border) !important;
        }

        .admin-sidebar {
            background: var(--sidebar) !important;
        }

        .card {
            background: var(--card) !important;
            border-color: var(--border) !important;
        }

        .card-header {
            border-bottom: 1px solid var(--border) !important;
        }

        .text-dark {
            color: var(--text) !important;
        }

        .text-muted {
            color: var(--muted) !important;
        }

    </style>

</head>


<body>

@php

    $authUser = Auth::user();

    $roles = App\Models\Role::all();

    $role = str(optional(Auth::user()->role)->role_name ?? 'user')
                ->lower()
                ->replace([' ', '-'], '_')
                ->toString();

    $isSuperAdmin = $role === 'super_admin';
    $isAdmin      = $role === 'admin';
    $isManager    = $role === 'manager';
    $isVendor     = $role === 'vendor';

    $routeName = request()->route()?->getName() ?? '';

    $active = fn($name) =>
        $routeName === $name ? 'active' : '';

    $open = fn($prefix) =>
        str_starts_with($routeName, $prefix) ? 'show' : '';

    $aria = fn($prefix) =>
        str_starts_with($routeName, $prefix) ? 'true' : 'false';

@endphp


<div class="admin-layout">


    {{-- =========================================================
         SIDEBAR BACKDROP
    ========================================================== --}}

    <div id="sidebarBackdrop"></div>


    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}

    <aside class="admin-sidebar bg-dark text-white" id="adminSidebar">


        {{-- BRAND --}}

        <div class="sidebar-brand p-3 border-bottom border-secondary">

            <a href="{{ route('admin.dashboard') }}"
               class="text-white text-decoration-none d-flex align-items-center gap-2">

                <img src="{{ asset('contents/admin') }}/images/logo.png"
                     alt="Logo">

            </a>

        </div>


        {{-- SIDEBAR BODY --}}

        <div class="sidebar-body">

            <ul class="nav flex-column gap-1">


                {{-- =====================================================
                     ADMIN / MANAGER / SUPER ADMIN
                ====================================================== --}}

                @if($isSuperAdmin || $isAdmin || $isManager)


                    {{-- =================================================
                         DASHBOARD
                    ================================================== --}}

                    <li class="nav-item">

                        <a class="nav-link text-white {{ $active('admin.dashboard') }}"
                           href="{{ route('admin.dashboard') }}">

                            <i class="fas fa-home me-2"></i>

                            Dashboard

                        </a>

                    </li>


                    {{-- =================================================
                         USER MANAGEMENT
                    ================================================== --}}

                    @if($isSuperAdmin || $isAdmin || $isManager)

                        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">

                            User Management

                        </li>


                        <li class="nav-item">

                            <a class="nav-link text-white d-flex align-items-center justify-content-between"
                               data-bs-toggle="collapse"
                               data-bs-target="#menuUsers"
                               role="button"
                               aria-expanded="{{ $aria('admin.users.') }}"
                               aria-controls="menuUsers">

                                <span>

                                    <i class="fas fa-users me-2"></i>

                                    Users

                                </span>

                                <span class="dropdown-icon">

                                    <i class="fas fa-chevron-down"></i>

                                </span>

                            </a>


                            <div class="collapse {{ $open('admin.users.') }}"
                                 id="menuUsers">

                                <ul class="nav flex-column ms-3 mt-1">


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50 {{ $active('admin.users.index') }}"
                                           href="{{ route('admin.users.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            All Users

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50 {{ $active('admin.users.staff') }}"
                                           href="{{ route('admin.users.staff') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Staff Members

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50 {{ request()->routeIs('admin.vendors.index') ? 'active' : '' }}"
                                           href="{{ route('admin.vendors.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Vendors

                                        </a>

                                    </li>


                                </ul>

                            </div>

                        </li>

                    @endif



                    {{-- =================================================
                         TOUR MANAGEMENT
                    ================================================== --}}

                    <li class="nav-item mt-2 text-uppercase small text-secondary px-2">

                        Tour Management

                    </li>


                    <li class="nav-item">

                        <a class="nav-link text-white d-flex align-items-center justify-content-between"
                           data-bs-toggle="collapse"
                           data-bs-target="#menuTours"
                           role="button"
                           aria-expanded="{{ $aria('admin.tours.') }}"
                           aria-controls="menuTours">

                            <span>

                                <i class="fas fa-map-marked-alt me-2"></i>

                                Tour Packages

                            </span>

                            <span class="dropdown-icon">

                                <i class="fas fa-chevron-down"></i>

                            </span>

                        </a>


                        <div class="collapse {{ $open('admin.tours.') }}"
                             id="menuTours">

                            <ul class="nav flex-column ms-3 mt-1">


                                <li class="nav-item">

                                    <a class="nav-link text-white-50 {{ $active('admin.tours.index') }}"
                                       href="{{ route('admin.tours.index') }}">

                                        <i class="far fa-circle me-2"></i>

                                        All Packages

                                    </a>

                                </li>


                                <li class="nav-item">

                                    <a class="nav-link text-white-50 {{ $active('admin.tours.create') }}"
                                       href="{{ route('admin.tours.create') }}">

                                        <i class="far fa-circle me-2"></i>

                                        Add Package

                                    </a>

                                </li>


                                <li class="nav-item">

                                    <a href="{{ route('admin.tour-types.index') }}"
                                       class="nav-link">

                                        <i class="fas fa-map-signs me-2"></i>

                                        Tour Types

                                    </a>

                                </li>


                                <li class="nav-item">

                                    <a class="nav-link text-white-50 {{ $active('admin.tour.dates.index') }}"
                                       href="{{ route('admin.tour.dates.index') }}">

                                        <i class="far fa-circle me-2"></i>

                                        Tour Dates

                                    </a>

                                </li>


                            </ul>

                        </div>

                    </li>



                    {{-- =================================================
                         BOOKING MANAGEMENT
                    ================================================== --}}

                    <li class="nav-item mt-2 text-uppercase small text-secondary px-2">

                        Booking Management

                    </li>


                    <li class="nav-item">

                        <a class="nav-link text-white d-flex align-items-center justify-content-between"
                           data-bs-toggle="collapse"
                           data-bs-target="#menuBookings"
                           role="button">

                            <span>

                                <i class="fas fa-calendar-check me-2"></i>

                                Bookings

                            </span>

                            <span class="dropdown-icon">

                                <i class="fas fa-chevron-down"></i>

                            </span>

                        </a>


                        <div class="collapse" id="menuBookings">

                            <ul class="nav flex-column ms-3 mt-1">


                                <li class="nav-item">

                                <a class="nav-link text-white-50 {{ $active('admin.bookings.index') }}"
                                href="{{ route('admin.bookings.index') }}">
                                    <i class="far fa-circle me-2"></i>
                                    All Bookings
                                </a>

                                </li>


                                <li class="nav-item">

                                    <a class="nav-link text-white-50"
                                       href="{{ route('admin.bookings.pending') }}">

                                        <i class="far fa-circle me-2"></i>

                                        Pending Bookings

                                    </a>

                                </li>


                                <li class="nav-item">

                                    <a class="nav-link text-white-50"
                                       href="{{ route('admin.bookings.confirmed') }}">

                                        <i class="far fa-circle me-2"></i>

                                        Confirmed Bookings

                                    </a>

                                </li>


                                <li class="nav-item">

                                    <a class="nav-link text-white-50"
                                       href="{{ route('admin.bookings.processing') }}">

                                        <i class="far fa-circle me-2"></i>

                                        Processing Bookings

                                    </a>

                                </li>


<li class="nav-item">
    <a class="nav-link text-white-50"
       href="{{ route('admin.bookings.completed') }}">

        <i class="far fa-circle me-2"></i>
        Completed Bookings

    </a>
</li>

<li class="nav-item">
    <a class="nav-link text-white-50"
       href="{{ route('admin.bookings.cancelled') }}">

        <i class="far fa-circle me-2"></i>
        Cancelled Bookings

    </a>
</li>



                                <li class="nav-item">

                                    <a class="nav-link text-white-50"
                                       href="{{ route('admin.bookings.refund-requests') }}">

                                        <i class="fas fa-undo me-2"></i>

                                        Refund Requests

                                    </a>

                                </li>


                            </ul>

                        </div>

                    </li>



                    {{-- =================================================
                         DESTINATIONS
                    ================================================== --}}

                    <li class="nav-item">

                        <a class="nav-link text-white {{ $active('admin.destinations.index') }}"
                           href="{{ route('admin.destinations.index') }}">

                            <i class="fas fa-plane-departure me-2"></i>

                            Destinations

                        </a>

                    </li>



                    {{-- =================================================
                         TRAVELERS
                    ================================================== --}}

                    <li class="nav-item">

                        <a class="nav-link text-white {{ $active('admin.travelers.index') }}"
                           href="{{ route('admin.travelers.index') }}">

                            <i class="fas fa-user-friends me-2"></i>

                            Travelers

                        </a>

                    </li>



                    {{-- =================================================
                         PAYMENT / FINANCE
                    ================================================== --}}

                    @if($isSuperAdmin || $isAdmin)

                        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">

                            Finance

                        </li>


                        <li class="nav-item">

                            <a class="nav-link text-white d-flex justify-content-between align-items-center"
                               data-bs-toggle="collapse"
                               data-bs-target="#menuPayment"
                               role="button">

                                <span>

                                    <i class="fas fa-wallet me-2"></i>

                                    Finance

                                </span>

                                <i class="fas fa-chevron-down"></i>

                            </a>


                            <div class="collapse" id="menuPayment">

                                <ul class="nav flex-column ms-3">


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.transactions.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Payment Transactions

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.payment_methods.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Payment Methods

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.commissions.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Commissions

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a
                                            href="{{ route('admin.refunds.index') }}"
                                            class="nav-link text-white-50 {{ request()->routeIs('admin.refunds.*') ? 'active' : '' }}"
                                        >

                                            <i class="fas fa-undo me-2"></i>

                                            Refunds

                                        </a>

                                    </li>


                                    <li class="nav-item">

    <a href="{{ route('admin.vendor-payouts.index') }}"
   class="nav-link text-white-50 {{ request()->routeIs('admin.vendor-payouts.*') ? 'active' : '' }}">

                                            <i class="fas fa-money-check-alt me-2"></i>

                                            Vendor Payouts

                                        </a>

                                    </li>


                                    <li class="nav-item">

    <a href="{{ route('admin.tax-rules.index') }}"
       class="nav-link text-white-50 {{ request()->routeIs('admin.tax-rules.*') ? 'active' : '' }}">

                                            <i class="fas fa-percent me-2"></i>

                                            Tax Management

                                        </a>

                                    </li>


                                </ul>

                            </div>

                        </li>

                    @endif



                    {{-- =================================================
                         COMMUNICATION
                    ================================================== --}}

                    @if($isSuperAdmin || $isAdmin)

                        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">

                            Communication

                        </li>


                        <li class="nav-item">

                            <a class="nav-link text-white d-flex justify-content-between align-items-center"
                               data-bs-toggle="collapse"
                               data-bs-target="#menuCommunication">

                                <span>

                                    <i class="fas fa-envelope me-2"></i>

                                    Communication

                                </span>

                                <i class="fas fa-chevron-down"></i>

                            </a>


                            <div class="collapse" id="menuCommunication">

                                <ul class="nav flex-column ms-3">


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.contact.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Contact Messages

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.subscribers.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Subscribers

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50 {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}"
                                           href="{{ route('admin.notifications.index') }}">

                                            <i class="far fa-bell me-2"></i>

                                            Notifications

                                        </a>

                                    </li>


                                </ul>

                            </div>

                        </li>

                    @endif



                    {{-- =================================================
                         CONTENT MANAGEMENT
                    ================================================== --}}

                    @if($isSuperAdmin || $isAdmin)

                        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">

                            Content Management

                        </li>


                        <li class="nav-item">

                            <a class="nav-link text-white d-flex justify-content-between align-items-center"
                               data-bs-toggle="collapse"
                               data-bs-target="#menuContent">

                                <span>

                                    <i class="fas fa-folder me-2"></i>

                                    Content Management

                                </span>

                                <i class="fas fa-chevron-down"></i>

                            </a>


                            <div class="collapse" id="menuContent">

                                <ul class="nav flex-column ms-3">


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.blogs.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Blog Management

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.faqs.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            FAQs Management

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.blog.categories.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Blog Categories

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.faq.categories.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            FAQ Categories

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.gallery.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Gallery

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.testimonials.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Testimonials

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.policies.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Policies

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.team-members.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Team Members

                                        </a>

                                    </li>


                                </ul>

                            </div>

                        </li>

                    @endif



                    {{-- =================================================
                         RESORT MANAGEMENT
                    ================================================== --}}

                    @if($isSuperAdmin || $isAdmin)

                        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">

                            Resort Management

                        </li>


                        <li class="nav-item">

                            <a class="nav-link text-white d-flex justify-content-between align-items-center"
                               data-bs-toggle="collapse"
                               data-bs-target="#menuRoomResort">

                                <span>

                                    <i class="fas fa-hotel me-2"></i>

                                    Resort Management

                                </span>

                                <i class="fas fa-chevron-down"></i>

                            </a>


                            <div class="collapse" id="menuRoomResort">

                                <ul class="nav flex-column ms-3">


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.resorts.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Resorts

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.resort-bookings.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Resort Bookings

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.rooms.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Rooms

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.room-types.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Room Types

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.room-prices.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Room Prices

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.room-availabilities.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Room Availability

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.facilities.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Facilities

                                        </a>

                                    </li>


                                </ul>

                            </div>

                        </li>

                    @endif



                    {{-- =================================================
                         TRANSPORT
                    ================================================== --}}

                    @if($isSuperAdmin || $isAdmin)

                        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">

                            Transport Management

                        </li>


                        <li class="nav-item">

                            @php

                                $transportActive =
                                    request()->routeIs('admin.transport-bookings.*')
                                    ||
                                    request()->routeIs('admin.transport-vehicles.*');

                            @endphp


                            <a class="nav-link text-white d-flex justify-content-between align-items-center
                                      {{ $transportActive ? 'active' : '' }}"
                               href="#menuTransport"
                               data-bs-toggle="collapse"
                               role="button"
                               aria-expanded="{{ $transportActive ? 'true' : 'false' }}"
                               aria-controls="menuTransport">

                                <span>

                                    <i class="fas fa-car me-2"></i>

                                    Transport Management

                                </span>

                                <i class="fas fa-chevron-down"></i>

                            </a>


                            <div class="collapse {{ $transportActive ? 'show' : '' }}"
                                 id="menuTransport">

                                <ul class="nav flex-column ms-3">


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.transport-bookings.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Transport Bookings

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.transport-vehicles.index') }}">

                                            <i class="fas fa-car-side me-2"></i>

                                            Transport Vehicles

                                        </a>

                                    </li>


                                </ul>

                            </div>

                        </li>

                    @endif



                    {{-- =================================================
                         MARKETING
                    ================================================== --}}

                    @if($isSuperAdmin || $isAdmin)

                        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">

                            Marketing

                        </li>


                        <li class="nav-item">

                            <a class="nav-link text-white d-flex align-items-center justify-content-between"
                               data-bs-toggle="collapse"
                               data-bs-target="#menuMarketing"
                               role="button">

                                <span>

                                    <i class="fas fa-bullhorn me-2"></i>

                                    Marketing

                                </span>

                                <span class="dropdown-icon">

                                    <i class="fas fa-chevron-down"></i>

                                </span>

                            </a>


                            <div class="collapse" id="menuMarketing">

                                <ul class="nav flex-column ms-3 mt-1">


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.coupons.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Coupons

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.ads.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Advertisements

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="#">

                                            <i class="fas fa-percentage me-2"></i>

                                            Promotions

                                        </a>

                                    </li>


                                </ul>

                            </div>

                        </li>

                    @endif



                    {{-- =================================================
                         REPORTS
                    ================================================== --}}

                    <li class="nav-item mt-2 text-uppercase small text-secondary px-2">

                        Reports

                    </li>


                    <li class="nav-item">

                        <a class="nav-link text-white d-flex align-items-center justify-content-between"
                           data-bs-toggle="collapse"
                           data-bs-target="#menuReports"
                           role="button">

                            <span>

                                <i class="fas fa-chart-pie me-2"></i>

                                Reports

                            </span>

                            <span class="dropdown-icon">

                                <i class="fas fa-chevron-down"></i>

                            </span>

                        </a>


                        <div class="collapse" id="menuReports">

                            <ul class="nav flex-column ms-3 mt-1">


                                <li class="nav-item">

                                    <a class="nav-link text-white-50"
                                       href="{{ route('admin.reports.bookings') }}">

                                        <i class="far fa-circle me-2"></i>

                                        Booking Reports

                                    </a>

                                </li>


                                @if($isSuperAdmin || $isAdmin)

                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.reports.revenue') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Revenue Reports

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="#">

                                            <i class="fas fa-store me-2"></i>

                                            Vendor Reports

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="#">

                                            <i class="fas fa-users me-2"></i>

                                            Customer Reports

                                        </a>

                                    </li>

                                @endif


                            </ul>

                        </div>

                    </li>



                    {{-- =================================================
                         COMMISSIONS
                    ================================================== --}}

                    @if($isSuperAdmin || $isAdmin)

                        <li class="nav-item">

                            <a href="{{ route('admin.commissions.index') }}"
                               class="nav-link text-white">

                                <i class="fas fa-hand-holding-usd me-2"></i>

                                <span>

                                    Commissions

                                </span>

                            </a>

                        </li>

                    @endif



                    {{-- =================================================
                         SAAS MANAGEMENT
                    ================================================== --}}

                    @if($isSuperAdmin)

                        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">

                            SaaS Management

                        </li>


                        <li class="nav-item">

                            <a class="nav-link text-white d-flex align-items-center justify-content-between"
                               data-bs-toggle="collapse"
                               data-bs-target="#menuSaaS"
                               role="button"
                               aria-expanded="false">

                                <span>

                                    <i class="fas fa-layer-group me-2"></i>

                                    SaaS Management

                                </span>

                                <span class="dropdown-icon">

                                    <i class="fas fa-chevron-down"></i>

                                </span>

                            </a>


                            <div class="collapse" id="menuSaaS">

                                <ul class="nav flex-column ms-3 mt-1">


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="#">

                                            <i class="fas fa-tags me-2"></i>

                                            Subscription Plans

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="#">

                                            <i class="fas fa-sync-alt me-2"></i>

                                            Subscriptions

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="#">

                                            <i class="fas fa-file-invoice-dollar me-2"></i>

                                            Billing History

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="#">

                                            <i class="fas fa-clock me-2"></i>

                                            Trial Settings

                                        </a>

                                    </li>


                                </ul>

                            </div>

                        </li>

                    @endif



                    {{-- =================================================
                         SEO
                    ================================================== --}}

                    @if($isSuperAdmin || $isAdmin)

                        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">

                            SEO Settings

                        </li>


                        <li class="nav-item">

                            <a class="nav-link text-white d-flex justify-content-between align-items-center"
                               data-bs-toggle="collapse"
                               data-bs-target="#menuSEO">

                                <span>

                                    <i class="fas fa-search me-2"></i>

                                    SEO Settings

                                </span>

                                <i class="fas fa-chevron-down"></i>

                            </a>


                            <div class="collapse" id="menuSEO">

                                <ul class="nav flex-column ms-3">


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.seo.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Manage SEO

                                        </a>

                                    </li>


                                </ul>

                            </div>

                        </li>

                    @endif



                    {{-- =================================================
                         SYSTEM SETTINGS
                    ================================================== --}}

                    @if($isSuperAdmin || $isAdmin)

                        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">

                            System Settings

                        </li>


                        {{-- SETTINGS --}}

                        <li class="nav-item">

                            <a class="nav-link text-white d-flex align-items-center justify-content-between"
                               href="#menuSettings"
                               data-bs-toggle="collapse"
                               role="button"
                               aria-expanded="{{ $aria('admin.settings.') }}"
                               aria-controls="menuSettings">

                                <span>

                                    <i class="fas fa-cogs me-2"></i>

                                    Settings

                                </span>

                                <span class="dropdown-icon">

                                    <i class="fas fa-chevron-down small"></i>

                                </span>

                            </a>


                            <div class="collapse {{ $open('admin.settings.') }}"
                                 id="menuSettings">

                                <ul class="nav flex-column ms-3 mt-1">


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.settings.index') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Manage Settings

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.settings.general') }}">

                                            <i class="far fa-circle me-2"></i>

                                            General Settings

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="{{ route('admin.settings.payment') }}">

                                            <i class="far fa-circle me-2"></i>

                                            Payment Settings

                                        </a>

                                    </li>


                                </ul>

                            </div>

                        </li>



                        {{-- SYSTEM --}}

                        <li class="nav-item">

                            <a class="nav-link text-white d-flex justify-content-between align-items-center"
                               data-bs-toggle="collapse"
                               data-bs-target="#menuSystem">

                                <span>

                                    <i class="fas fa-server me-2"></i>

                                    System

                                </span>

                                <i class="fas fa-chevron-down"></i>

                            </a>


                            <div class="collapse" id="menuSystem">

                                <ul class="nav flex-column ms-3">


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="#">

                                            <i class="far fa-clock me-2"></i>

                                            Activity Logs

                                        </a>

                                    </li>


                                    <li class="nav-item">

                                        <a class="nav-link text-white-50"
                                           href="#">

                                            <i class="fas fa-bell me-2"></i>

                                            Notification Settings

                                        </a>

                                    </li>


                                    {{-- API --}}

                                    @if($isSuperAdmin)

                                        <li class="nav-item">

                                            <a class="nav-link text-white-50"
                                               href="{{ route('admin.api.keys.index') }}">

                                                <i class="fas fa-key me-2"></i>

                                                API System

                                            </a>

                                        </li>

                                    @endif


                                </ul>

                            </div>

                        </li>

                    @endif


                @endif



            </ul>

        </div>

    </aside>



    {{-- =========================================================
         MAIN AREA
    ========================================================== --}}

    <div class="admin-main" id="adminMain">


        {{-- =====================================================
             TOPBAR
        ====================================================== --}}

        <div class="admin-topbar">


            <div class="d-flex align-items-center gap-2">


                <button class="btn-icon"
                        type="button"
                        id="sidebarToggle"
                        title="Toggle menu">

                    <i class="fas fa-bars"></i>

                </button>


                <h6 class="mb-0 fw-bold text-dark">

                    @yield('title', 'Dashboard')

                </h6>


            </div>



            <div class="d-flex align-items-center gap-2">


{{-- =================================================
     NOTIFICATIONS
================================================== --}}

<div class="dropdown">

    <button class="btn-icon position-relative"
            id="notiBtn"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false">

        <i class="far fa-bell"></i>

        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
              id="notiBadge"
              style="display:none;">

            0

        </span>

    </button>


    <ul class="dropdown-menu dropdown-menu-end p-0"
        style="width:360px; max-width:calc(100vw - 30px);"
        id="notiMenu">


        {{-- Header --}}

        <li class="notification_topbar d-flex justify-content-between align-items-center px-3 py-2">

            <span class="fw-bold">

                <i class="far fa-bell me-1"></i>

                Notifications

            </span>


            <button class="btn btn-link p-0 text-decoration-none small"
                    type="button"
                    id="clearAllBtn">

                Clear All

            </button>

        </li>


        {{-- Notification List --}}

        <li id="notificationList">

            <div class="px-3 py-4 text-center text-muted">

                <i class="far fa-bell-slash fa-lg mb-2"></i>

                <div>

                    No notifications

                </div>

            </div>

        </li>


        {{-- Footer --}}

        <li>

            <hr class="dropdown-divider m-0">

        </li>


        <li>

            <a class="dropdown-item text-center small py-2"
               href="{{ route('admin.notifications.index') }}">

                View all notifications

            </a>

        </li>

    </ul>

</div>


                {{-- =================================================
                     THEME TOGGLE
                ================================================== --}}

                <button class="btn-icon me-2"
                        id="themeToggle"
                        type="button"
                        title="Toggle theme">

                    <i class="fas fa-moon"
                       id="themeMoon"></i>

                    <i class="fas fa-sun d-none"
                       id="themeSun"></i>

                </button>



                {{-- =================================================
                     USER DROPDOWN
                ================================================== --}}

                <div class="dropdown">


                    <button class="user-dd-btn"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">


                        <span class="avatar p-0 overflow-hidden">


                            @if(!empty($authUser->photo))

                                <img src="{{ asset('uploads/users/'.$authUser->photo) }}"
                                     alt="User"
                                     style="width:100%; height:100%; object-fit:cover; border-radius:50%;">

                            @else

                                <i class="fas fa-user"></i>

                            @endif


                        </span>


                        <span class="user-meta d-none d-md-block">

                            <span class="name">

                                {{ $authUser->name ?? 'User' }}

                            </span>

                            <br>

                            <span class="role">

                                {{ ucfirst($role) }}

                            </span>

                        </span>


                        <i class="fas fa-chevron-down text-muted ms-1"></i>


                    </button>



                    <ul class="dropdown-menu dropdown-menu-end">


                        <li>

                            <a class="dropdown-item"
                               href="{{ route('admin.profile') }}">

                                <i class="fas fa-user me-2"></i>

                                Profile

                            </a>

                        </li>


                        <li>

                            <a class="dropdown-item"
                               href="{{ route('admin.settings.index') }}">

                                <i class="fas fa-cog me-2"></i>

                                Manage Settings

                            </a>

                        </li>


                        <li>

                            <hr class="dropdown-divider">

                        </li>


                        <li>

                            <form method="POST"
                                  action="{{ route('logout') }}">

                                @csrf

                                <button type="submit"
                                        class="dropdown-item">

                                    <i class="fas fa-sign-out-alt me-2"></i>

                                    Logout

                                </button>

                            </form>

                        </li>


                    </ul>

                </div>


            </div>

        </div>



        {{-- =====================================================
             PAGE CONTENT
        ====================================================== --}}

        <div class="page-wrap">

            @yield('page')

        </div>


    </div>

</div>



{{-- =============================================================
     CORE JS
============================================================== --}}

<script src="{{ asset('contents/admin') }}/js/jquery-3.6.0.min.js"></script>

<script src="{{ asset('contents/admin') }}/js/bootstrap.bundle.min.js"></script>



{{-- =============================================================
     PLUGINS
============================================================== --}}

<script src="{{ asset('contents/admin') }}/js/datatables.min.js"></script>

<script src="{{ asset('contents/admin') }}/js/chart.js"></script>



{{-- =============================================================
     MAP
============================================================== --}}

<script src="{{ asset('contents/admin') }}/js/jsvectormap.js"></script>

<script src="{{ asset('contents/admin') }}/js/world-merc.js"></script>



{{-- =============================================================
     APP
============================================================== --}}

<script src="{{ asset('contents/admin') }}/js/custom.js"></script>



{{-- =============================================================
     GLOBAL LAYOUT JS
============================================================== --}}

<script>

(function () {


    const body = document.body;


    function ready(fn) {

        if (document.readyState === 'loading') {

            document.addEventListener('DOMContentLoaded', fn);

        } else {

            fn();

        }

    }


    ready(function () {


        /* =========================================================
           SIDEBAR TOGGLE
        ========================================================== */


        const btn = document.getElementById('sidebarToggle');

        const backdrop = document.getElementById('sidebarBackdrop');


        const isMobile = () =>
            window.matchMedia('(max-width: 991.98px)').matches;


        if (localStorage.getItem('sidebarCollapsed') === '1') {

            body.classList.add('sidebar-collapsed');

        }


        btn?.addEventListener('click', function (e) {

            e.preventDefault();


            if (isMobile()) {

                body.classList.toggle('sidebar-open');

            } else {

                body.classList.toggle('sidebar-collapsed');


                localStorage.setItem(
                    'sidebarCollapsed',
                    body.classList.contains('sidebar-collapsed')
                        ? '1'
                        : '0'
                );

            }

        });


        backdrop?.addEventListener('click', function () {

            body.classList.remove('sidebar-open');

        });


/* =========================================================
   NOTIFICATION SYSTEM
========================================================== */

(function () {

    const notiBtn = document.getElementById('notiBtn');
    const notiBadge = document.getElementById('notiBadge');
    const notiMenu = document.getElementById('notiMenu');
    const notificationList = document.getElementById('notificationList');
    const clearAllBtn = document.getElementById('clearAllBtn');

    /*
    |--------------------------------------------------------------------------
    | Stop if notification elements do not exist
    |--------------------------------------------------------------------------
    */
    if (!notiBtn || !notiBadge || !notificationList) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Laravel Generated URLs
    |--------------------------------------------------------------------------
    */
    const notificationTopbarUrl =
        @json(route('admin.notifications.topbar'));

    const notificationClearAllUrl =
        @json(route('admin.notifications.clear-all'));

    /*
    |--------------------------------------------------------------------------
    | Notification Details URL
    |--------------------------------------------------------------------------
    |
    | Individual notification URL is returned by backend.
    |
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | CSRF Token
    |--------------------------------------------------------------------------
    */
    const csrfToken =
        document.querySelector(
            'meta[name="csrf-token"]'
        )?.getAttribute('content');


    /*
    |--------------------------------------------------------------------------
    | Notification Icon
    |--------------------------------------------------------------------------
    */
    function getNotificationIcon(type) {

        switch ((type || 'info').toLowerCase()) {

            case 'success':
                return 'fas fa-check-circle';

            case 'warning':
                return 'fas fa-exclamation-triangle';

            case 'danger':
                return 'fas fa-times-circle';

            case 'primary':
                return 'fas fa-star';

            case 'secondary':
                return 'fas fa-info-circle';

            case 'dark':
                return 'fas fa-bell';

            case 'info':
            default:
                return 'fas fa-info-circle';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Notification Type Class
    |--------------------------------------------------------------------------
    */
    function getNotificationTypeClass(type) {

        switch ((type || 'info').toLowerCase()) {

            case 'success':
                return 'success';

            case 'warning':
                return 'warning';

            case 'danger':
                return 'danger';

            case 'primary':
                return 'primary';

            case 'secondary':
                return 'secondary';

            case 'dark':
                return 'dark';

            case 'info':
            default:
                return 'info';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Escape HTML
    |--------------------------------------------------------------------------
    */
    function escapeHtml(value) {

        if (
            value === null ||
            value === undefined
        ) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }


    /*
    |--------------------------------------------------------------------------
    | Update Badge
    |--------------------------------------------------------------------------
    */
    function updateNotificationBadge(count) {

        count = Number(count || 0);

        if (count > 0) {

            notiBadge.textContent =
                count > 99
                    ? '99+'
                    : count;

            notiBadge.style.display =
                'inline-block';

        } else {

            notiBadge.textContent = '0';

            notiBadge.style.display = 'none';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Empty State
    |--------------------------------------------------------------------------
    */
    function renderEmptyNotifications() {

        notificationList.innerHTML = `

            <div class="px-3 py-4 text-center text-muted">

                <i class="far fa-bell-slash fa-lg mb-2"></i>

                <div>
                    No notifications
                </div>

            </div>

        `;
    }


    /*
    |--------------------------------------------------------------------------
    | Render Notifications
    |--------------------------------------------------------------------------
    */
    function renderNotifications(notifications) {

        if (
            !notifications ||
            notifications.length === 0
        ) {

            renderEmptyNotifications();

            return;
        }

        let html = '';

        notifications.forEach(function (notification) {

            const typeClass =
                getNotificationTypeClass(
                    notification.type
                );

            const icon =
                getNotificationIcon(
                    notification.type
                );

            const unreadClass =
                notification.is_read
                    ? ''
                    : 'bg-light';

            const notificationUrl =
                notification.url || '#';

            html += `

                <div
                    class="notification-topbar-item
                           px-3 py-3
                           border-bottom
                           ${unreadClass}"
                    data-notification-id="${notification.id}"
                >

                    <div class="d-flex gap-2">

                        <div
                            class="flex-shrink-0
                                   d-flex
                                   align-items-center
                                   justify-content-center
                                   rounded-circle"
                            style="
                                width:38px;
                                height:38px;
                                background:#f1f4f8;
                            "
                        >

                            <i class="${icon} text-${typeClass}"></i>

                        </div>


                        <div
                            class="flex-grow-1"
                            style="min-width:0;"
                        >

                            <a
                                href="${escapeHtml(notificationUrl)}"
                                class="text-decoration-none text-dark"
                            >

                                <div class="fw-semibold small">

                                    ${escapeHtml(
                                        notification.title
                                    )}

                                </div>


                                <div
                                    class="text-muted small mt-1"
                                    style="
                                        display:-webkit-box;
                                        -webkit-line-clamp:2;
                                        -webkit-box-orient:vertical;
                                        overflow:hidden;
                                    "
                                >

                                    ${escapeHtml(
                                        notification.message
                                    )}

                                </div>


                                <div
                                    class="text-muted mt-1"
                                    style="font-size:11px;"
                                >

                                    <i class="far fa-clock me-1"></i>

                                    ${escapeHtml(
                                        notification.time_ago || ''
                                    )}

                                </div>

                            </a>

                        </div>


                        ${
                            !notification.is_read
                                ? `
                                    <span
                                        class="
                                            flex-shrink-0
                                            rounded-circle
                                            bg-primary
                                        "
                                        style="
                                            width:7px;
                                            height:7px;
                                            margin-top:6px;
                                        "
                                    ></span>
                                  `
                                : ''
                        }

                    </div>

                </div>

            `;
        });

        notificationList.innerHTML = html;
    }


    /*
    |--------------------------------------------------------------------------
    | Load Notifications
    |--------------------------------------------------------------------------
    */
    function loadNotifications() {

        fetch(
            notificationTopbarUrl,
            {
                method: 'GET',

                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },

                credentials: 'same-origin'
            }
        )

        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    `Notification request failed: ${response.status}`
                );
            }

            return response.json();
        })

        .then(function (data) {

            console.log(
                'Notification Response:',
                data
            );

            if (!data.success) {

                updateNotificationBadge(0);

                renderEmptyNotifications();

                return;
            }

            updateNotificationBadge(
                data.unread_count
            );

            renderNotifications(
                data.notifications
            );
        })

        .catch(function (error) {

            console.error(
                'Notification Error:',
                error
            );
        });
    }


// ============================================================
// CLEAR ALL NOTIFICATIONS
// ============================================================

if (clearAllBtn) {
    clearAllBtn.addEventListener('click', async function (e) {
        e.preventDefault();
        e.stopPropagation();

        try {
            const response = await fetch(notificationClearAllUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (response.ok && data.success) {

                // Badge hide
                updateNotificationBadge(0);

                // Notification list empty
                notificationList.innerHTML = `
                    <div class="px-3 py-4 text-center text-muted">
                        <i class="far fa-bell-slash fa-lg mb-2"></i>
                        <div>No notifications</div>
                    </div>
                `;

                // কোনো alert / popup নেই
            }

        } catch (error) {
            console.error('Failed to clear notifications:', error);
        }
    });
}

    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    */
    loadNotifications();


    /*
    |--------------------------------------------------------------------------
    | Auto Refresh Every 30 Seconds
    |--------------------------------------------------------------------------
    */
    setInterval(
        loadNotifications,
        30000
    );

})();


        /* =========================================================
           THEME TOGGLE
        ========================================================== */


        const themeBtn = document.getElementById('themeToggle');

        const moonIcon = document.getElementById('themeMoon');

        const sunIcon = document.getElementById('themeSun');


        function applyTheme(mode) {


            if (mode === 'dark') {


                body.classList.add('dark-mode');


                moonIcon?.classList.add('d-none');

                sunIcon?.classList.remove('d-none');


                localStorage.setItem('theme', 'dark');


            } else {


                body.classList.remove('dark-mode');


                moonIcon?.classList.remove('d-none');

                sunIcon?.classList.add('d-none');


                localStorage.setItem('theme', 'light');

            }

        }


        const savedTheme = localStorage.getItem('theme');


        applyTheme(
            savedTheme === 'dark'
                ? 'dark'
                : 'light'
        );


        themeBtn?.addEventListener('click', function () {

            const isDark =
                body.classList.contains('dark-mode');


            applyTheme(
                isDark
                    ? 'light'
                    : 'dark'
            );

        });


    });


})();

</script>



{{-- =============================================================
     PAGE-WISE SCRIPTS
============================================================== --}}

@stack('scripts')


</body>

</html>

