<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Vendor Panel')</title>

    {{-- Admin/Vendor Assets --}}
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

            --bg: #f6f7fb;
            --card: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --border: rgba(0,0,0,.06);

            --topbar: #ffffff;
            --sidebar: #111827;
        }


        body {
            background: var(--bg);
            color: var(--text);
        }


        .vendor-layout {
            min-height: 100vh;
        }


        /* ==============================
           SIDEBAR
        ============================== */

        .vendor-sidebar {

            width: var(--sidebar-w);

            height: 100vh;

            position: fixed;

            top: 0;
            left: 0;

            z-index: 1000;

            display: flex;

            flex-direction: column;

            background: var(--sidebar);

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


        /* ==============================
           MAIN
        ============================== */

        .vendor-main {

            margin-left: var(--sidebar-w);

            min-height: 100vh;

            transition: margin-left .25s ease;
        }


        /* ==============================
           TOPBAR
        ============================== */

        .vendor-topbar {

            height: var(--topbar-h);

            position: fixed;

            top: 0;

            left: var(--sidebar-w);

            right: 0;

            z-index: 900;

            border-bottom: 1px solid var(--border);

            background: var(--topbar);

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 16px;

            transition: left .25s ease;
        }


        .page-wrap {

            padding:

                calc(var(--topbar-h) + 25px)

                18px

                20px

                18px;
        }


        /* ==============================
           ACTIVE MENU
        ============================== */

        .nav-link.active {

            background: rgba(255,255,255,.12);

            border-radius: 10px;
        }


        /* ==============================
           BUTTON
        ============================== */

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


        /* ==============================
           USER
        ============================== */

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


        /* ==============================
           DESKTOP COLLAPSE
        ============================== */

        body.sidebar-collapsed .vendor-sidebar {

            transform: translateX(-100%) !important;
        }


        body.sidebar-collapsed .vendor-main {

            margin-left: 0 !important;
        }


        body.sidebar-collapsed .vendor-topbar {

            left: 0 !important;
        }


        /* ==============================
           BACKDROP
        ============================== */

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


        /* ==============================
           DARK MODE
        ============================== */

        body.dark-mode {

            --bg: #0b1220;

            --card: #0f172a;

            --text: #e5e7eb;

            --muted: #9ca3af;

            --border: rgba(255,255,255,.08);

            --topbar: #0f172a;

            --sidebar: #0b1220;
        }


        .vendor-topbar {

            background: var(--topbar) !important;

            border-bottom: 1px solid var(--border) !important;
        }


        .vendor-sidebar {

            background: var(--sidebar) !important;
        }


        .card {

            background: var(--card) !important;

            border-color: var(--border) !important;
        }


        .text-dark {

            color: var(--text) !important;
        }


        .text-muted {

            color: var(--muted) !important;
        }


        /* ==============================
           MOBILE
        ============================== */

        @media (max-width: 991.98px) {

            .vendor-main {

                margin-left: 0 !important;
            }


            .vendor-topbar {

                left: 0 !important;
            }


            .vendor-sidebar {

                transform: translateX(-100%);

                top: 66px;

                left: 0;
            }


            body.sidebar-open .vendor-sidebar {

                transform: translateX(0);
            }

        }

    </style>

</head>


<body>

@php

    $authUser = Auth::user();

    $routeName = request()->route()?->getName() ?? '';

    $active = fn($name) =>
        $routeName === $name ? 'active' : '';

@endphp


<div class="vendor-layout">


    {{-- Mobile Backdrop --}}

    <div id="sidebarBackdrop"></div>

{{-- ==========================================================
VENDOR SIDEBAR
=========================================================== --}}

<aside class="vendor-sidebar text-white" id="vendorSidebar">

{{-- ======================================================
     BRAND
======================================================= --}}

<div class="sidebar-brand p-3 border-bottom border-secondary">

    <a
        href="{{ route('vendor.dashboard') }}"
        class="text-white text-decoration-none d-flex align-items-center gap-2"
    >

        <img
            src="{{ asset('contents/admin/images/vromon-seba.png') }}"
            alt="Vromon Seba"
            style="max-width:180px;"
        >

    </a>

</div>


{{-- ======================================================
     SIDEBAR BODY
======================================================= --}}

<div class="sidebar-body">

    <ul class="nav flex-column gap-1">


        {{-- ==================================================
             OVERVIEW
        =================================================== --}}

        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">
            Overview
        </li>


        {{-- Dashboard --}}

        <li class="nav-item">

            <a
                href="{{ route('vendor.dashboard') }}"
                class="nav-link text-white
                {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}"
            >

                <i class="fas fa-home me-2"></i>

                Dashboard

            </a>

        </li>



        {{-- ==================================================
             PROPERTY MANAGEMENT
        =================================================== --}}

        <li class="nav-item mt-3 text-uppercase small text-secondary px-2">
            Property Management
        </li>


        {{-- My Resorts --}}

        <li class="nav-item">

            <a
                href="{{ route('vendor.resorts.index') }}"
                class="nav-link text-white
                {{ request()->routeIs('vendor.resorts.*')
                    || request()->routeIs('vendor.resort-images.*')
                    ? 'active'
                    : '' }}"
            >

                <i class="fas fa-hotel me-2"></i>

                My Resorts

            </a>

        </li>


        {{-- Facilities --}}

        <li class="nav-item">

            <a
                href="{{ route('vendor.facilities.index') }}"
                class="nav-link text-white
                {{ request()->routeIs('vendor.facilities.*') ? 'active' : '' }}"
            >

                <i class="fas fa-concierge-bell me-2"></i>

                Facilities

            </a>

        </li>



        {{-- ==================================================
             ROOM MANAGEMENT
        =================================================== --}}

        <li class="nav-item mt-3 text-uppercase small text-secondary px-2">
            Room Management
        </li>


        @php

            $roomManagementActive =
                request()->routeIs('vendor.rooms.*') ||
                request()->routeIs('vendor.room-types.*') ||
                request()->routeIs('vendor.room-prices.*') ||
                request()->routeIs('vendor.room-availabilities.*') ||
                request()->routeIs('vendor.room-images.*');

        @endphp


        <li class="nav-item">

            <a
                class="nav-link text-white d-flex align-items-center justify-content-between
                {{ $roomManagementActive ? 'active' : '' }}"

                data-bs-toggle="collapse"

                href="#roomManagementMenu"

                role="button"

                aria-expanded="{{ $roomManagementActive ? 'true' : 'false' }}"

                aria-controls="roomManagementMenu"
            >

                <span>

                    <i class="fas fa-bed me-2"></i>

                    Room Management

                </span>

                <i class="fas fa-chevron-down small transition-icon"></i>

            </a>


            <div
                class="collapse {{ $roomManagementActive ? 'show' : '' }}"
                id="roomManagementMenu"
            >

                <ul class="nav flex-column ms-3 mt-1">


                    {{-- Rooms --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('vendor.rooms.index') }}"
                            class="nav-link text-white small
                            {{ request()->routeIs('vendor.rooms.*') ? 'active' : '' }}"
                        >

                            <i class="fas fa-door-open me-2"></i>

                            Rooms

                        </a>

                    </li>


                    {{-- Room Types --}}

                    <li class="nav-item">

                        <a
                            href="{{ route('vendor.room-types.index') }}"
                            class="nav-link text-white small
                            {{ request()->routeIs('vendor.room-types.*') ? 'active' : '' }}"
                        >

                            <i class="fas fa-layer-group me-2"></i>

                            Room Types

                        </a>

                    </li>

                </ul>

            </div>

        </li>



        {{-- ==================================================
             BOOKING MANAGEMENT
        =================================================== --}}

        <li class="nav-item mt-3 text-uppercase small text-secondary px-2">
            Booking Management
        </li>


        {{-- Room Bookings --}}

        <li class="nav-item">

            <a
                href="{{ route('vendor.room-bookings.index') }}"
                class="nav-link text-white
                {{ request()->routeIs('vendor.room-bookings.*') ? 'active' : '' }}"
            >

                <i class="fas fa-calendar-check me-2"></i>

                Room Bookings

            </a>

        </li>



      {{-- ==================================================
     FINANCE
=================================================== --}}

<li class="nav-item mt-3 text-uppercase small text-secondary px-2">
    Finance
</li>


{{-- Earnings --}}

<li class="nav-item">

    <a
        href="{{ route('vendor.earnings.index') }}"
        class="nav-link text-white
        {{ request()->routeIs('vendor.earnings.*') ? 'active' : '' }}"
    >

        <i class="fas fa-chart-line me-2"></i>

        Earnings

    </a>

</li>


{{-- Commissions --}}

<li class="nav-item">

    <a
        href="{{ route('vendor.commissions.index') }}"
        class="nav-link text-white
        {{ request()->routeIs('vendor.commissions.*') ? 'active' : '' }}"
    >

        <i class="fas fa-hand-holding-usd me-2"></i>

        Commissions

    </a>

</li>


{{-- Payment Methods --}}

<li class="nav-item">

    <a
        href="{{ route('vendor.payment-methods.index') }}"
        class="nav-link text-white
        {{ request()->routeIs('vendor.payment-methods.*') ? 'active' : '' }}"
    >

        <i class="fas fa-credit-card me-2"></i>

        Payment Methods

    </a>

</li>


{{-- Wallet --}}

<li class="nav-item">

    <a
        href="{{ route('vendor.wallet.index') }}"
        class="nav-link text-white
        {{ request()->routeIs('vendor.wallet.*') ? 'active' : '' }}"
    >

        <i class="fas fa-wallet me-2"></i>

        Wallet

    </a>

</li>


{{-- Withdrawals --}}

<li class="nav-item">

    <a
        href="{{ route('vendor.withdrawals.index') }}"
        class="nav-link text-white
        {{ request()->routeIs('vendor.withdrawals.*') ? 'active' : '' }}"
    >

        <i class="fas fa-money-bill-wave me-2"></i>

        Withdrawals

    </a>

</li>



        {{-- ==================================================
             CUSTOMER & REVIEWS
        =================================================== --}}

        <li class="nav-item mt-3 text-uppercase small text-secondary px-2">
            Customer & Reviews
        </li>


        {{-- Reviews --}}

        <li class="nav-item">

            <a
                href="{{ route('vendor.reviews.index') }}"
                class="nav-link text-white
                {{ request()->routeIs('vendor.reviews.*') ? 'active' : '' }}"
            >

                <i class="fas fa-star me-2"></i>

                Reviews

            </a>

        </li>



        {{-- ==================================================
             REPORTS
        =================================================== --}}

        <li class="nav-item mt-3 text-uppercase small text-secondary px-2">
            Reports
        </li>


        {{-- Reports --}}

        <li class="nav-item">

            <a
                href="{{ route('vendor.reports.index') }}"
                class="nav-link text-white
                {{ request()->routeIs('vendor.reports.*') ? 'active' : '' }}"
            >

                <i class="fas fa-chart-pie me-2"></i>

                Reports

            </a>

        </li>



        {{-- ==================================================
             INVOICES
        =================================================== --}}

        <li class="nav-item">

            <a
                href="#"
                class="nav-link text-white"
            >

                <i class="fas fa-file-invoice me-2"></i>

                Invoices

                <span class="badge bg-secondary ms-auto">
                    Booking
                </span>

            </a>

        </li>



        {{-- ==================================================
             MARKETING
        =================================================== --}}

        <li class="nav-item mt-3 text-uppercase small text-secondary px-2">
            Marketing
        </li>


        {{-- Promotions --}}

        <li class="nav-item">

            <a
                href="#"
                class="nav-link text-white"
            >

                <i class="fas fa-bullhorn me-2"></i>

                Promotions

                <span class="badge bg-secondary ms-auto">
                    Soon
                </span>

            </a>

        </li>


        {{-- Coupons --}}

        <li class="nav-item">

            <a
                href="#"
                class="nav-link text-white"
            >

                <i class="fas fa-ticket-alt me-2"></i>

                Coupons

                <span class="badge bg-secondary ms-auto">
                    Soon
                </span>

            </a>

        </li>



        {{-- ==================================================
             ACCOUNT
        =================================================== --}}

        <li class="nav-item mt-3 text-uppercase small text-secondary px-2">
            Account
        </li>


        {{-- Profile --}}

        <li class="nav-item">

            <a
                href="{{ route('vendor.profile.index') }}"
                class="nav-link text-white
                {{ request()->routeIs('vendor.profile.*') ? 'active' : '' }}"
            >

                <i class="fas fa-user me-2"></i>

                My Profile

            </a>

        </li>


    </ul>

</div>


</aside>



    {{-- ==========================================
         MAIN
    =========================================== --}}

    <div class="vendor-main"
         id="vendorMain">


        {{-- TOPBAR --}}

        <div class="vendor-topbar">


            <div class="d-flex align-items-center gap-2">


                <button
                    class="btn-icon"
                    type="button"
                    id="sidebarToggle"
                    title="Toggle menu"
                >

                    <i class="fas fa-bars"></i>

                </button>


                <h6 class="mb-0 fw-bold text-dark">

                    @yield('title', 'Dashboard')

                </h6>


            </div>


            {{-- RIGHT SIDE --}}

            <div class="d-flex align-items-center gap-2">


                {{-- Theme --}}

                <button
                    class="btn-icon me-2"
                    id="themeToggle"
                    type="button"
                    title="Toggle theme"
                >

                    <i class="fas fa-moon"
                       id="themeMoon"></i>

                    <i class="fas fa-sun d-none"
                       id="themeSun"></i>

                </button>


                {{-- User --}}

                <div class="dropdown">


                    <button
                        class="user-dd-btn"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >


                        <span class="avatar p-0 overflow-hidden">

                            @if(!empty($authUser->photo))

                                <img
                                    src="{{ asset('uploads/users/'.$authUser->photo) }}"
                                    alt="User"
                                    style="width:100%;height:100%;object-fit:cover;border-radius:50%;"
                                >

                            @else

                                <i class="fas fa-user"></i>

                            @endif

                        </span>


                        <span class="user-meta d-none d-md-block">

                            <span class="name">

                                {{ $authUser->name ?? 'Vendor' }}

                            </span>

                            <br>

                            <span class="role">

                                Vendor

                            </span>

                        </span>


                        <i class="fas fa-chevron-down text-muted ms-1"></i>


                    </button>


                    <ul class="dropdown-menu dropdown-menu-end">


                        <li>

                            <a
                                class="dropdown-item"
                                href="{{ route('vendor.profile.index') }}"
                            >

                                <i class="fas fa-user me-2"></i>

                                My Profile

                            </a>

                        </li>


                        <li>

                            <hr class="dropdown-divider">

                        </li>


                        <li>

                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="dropdown-item"
                                >

                                    <i class="fas fa-sign-out-alt me-2"></i>

                                    Logout

                                </button>

                            </form>

                        </li>


                    </ul>


                </div>


            </div>


        </div>


        {{-- PAGE CONTENT --}}

        <div class="page-wrap">

            @yield('page')

        </div>


    </div>


</div>


{{-- ==========================================
     JAVASCRIPT
=========================================== --}}

<script src="{{ asset('contents/admin') }}/js/jquery-3.6.0.min.js"></script>

<script src="{{ asset('contents/admin') }}/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('contents/admin') }}/js/datatables.min.js"></script>

<script src="{{ asset('contents/admin') }}/js/chart.js"></script>

<script src="{{ asset('contents/admin') }}/js/custom.js"></script>


<script>

(function () {

    const body = document.body;

    const btn = document.getElementById('sidebarToggle');

    const backdrop = document.getElementById('sidebarBackdrop');


    const isMobile = () =>

        window.matchMedia('(max-width: 991.98px)').matches;


    /* ==========================
       SIDEBAR
    ========================== */

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


    /* ==========================
       THEME
    ========================== */

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

    applyTheme(savedTheme === 'dark' ? 'dark' : 'light');


    themeBtn?.addEventListener('click', function () {

        const isDark = body.classList.contains('dark-mode');

        applyTheme(isDark ? 'light' : 'dark');

    });


})();

</script>


@stack('scripts')

</body>

</html>