{{-- =========================================================
    VENDOR SIDEBAR
========================================================= --}}

<aside class="admin-sidebar bg-dark text-white" id="adminSidebar">

    <div class="sidebar-brand p-3 border-bottom border-secondary">
        <a href="{{ route('vendor.dashboard') }}"
           class="text-white text-decoration-none d-flex align-items-center gap-2">

            <img src="{{ asset('contents/admin/images/vromon-seba.png') }}" alt="Logo">

        </a>
    </div>

    <div class="sidebar-body">

        <ul class="nav flex-column gap-1">

            {{-- Dashboard --}}
            <li class="nav-item">
                <a href="{{ route('vendor.dashboard') }}"
                   class="nav-link text-white {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">

                    <i class="fas fa-home me-2"></i>
                    Dashboard

                </a>
            </li>

            {{-- ============================= --}}
            {{-- Resort Management --}}
            {{-- ============================= --}}

            <li class="nav-item mt-3 text-uppercase small text-secondary px-2">
                Resort Management
            </li>

            {{-- Resorts --}}
            <li class="nav-item">

                <a class="nav-link text-white d-flex justify-content-between align-items-center"
                   data-bs-toggle="collapse"
                   href="#vendorResortMenu">

                    <span>
                        <i class="fas fa-hotel me-2"></i>
                        Resorts
                    </span>

                    <i class="fas fa-chevron-down"></i>

                </a>

                <div class="collapse {{ request()->routeIs('vendor.resorts.*') ? 'show' : '' }}"
                     id="vendorResortMenu">

                    <ul class="nav flex-column ms-3">

                        <li class="nav-item">

                            <a href="{{ route('vendor.resorts.index') }}"
                               class="nav-link text-white-50">

                                <i class="far fa-circle me-2"></i>
                                My Resort

                            </a>

                        </li>

                        <li class="nav-item">

                            <a href="{{ route('vendor.resorts.create') }}"
                               class="nav-link text-white-50">

                                <i class="far fa-circle me-2"></i>
                                Add Resort

                            </a>

                        </li>

                    </ul>

                </div>

            </li>

            {{-- Rooms --}}
            <li class="nav-item">

                <a class="nav-link text-white d-flex justify-content-between align-items-center"
                   data-bs-toggle="collapse"
                   href="#vendorRoomMenu">

                    <span>

                        <i class="fas fa-bed me-2"></i>
                        Rooms

                    </span>

                    <i class="fas fa-chevron-down"></i>

                </a>

                <div class="collapse"
                     id="vendorRoomMenu">

                    <ul class="nav flex-column ms-3">

                        <li class="nav-item">

                            <a href="{{ route('vendor.rooms.index') }}"
                               class="nav-link text-white-50">

                                <i class="far fa-circle me-2"></i>
                                All Rooms

                            </a>

                        </li>

                        <li class="nav-item">

                            <a href="{{ route('vendor.rooms.create') }}"
                               class="nav-link text-white-50">

                                <i class="far fa-circle me-2"></i>
                                Add Room

                            </a>

                        </li>

                    </ul>

                </div>

            </li>

            {{-- Room Management --}}

            <li class="nav-item">

                <a class="nav-link text-white d-flex justify-content-between align-items-center"
                   data-bs-toggle="collapse"
                   href="#vendorRoomManagement">

                    <span>

                        <i class="fas fa-layer-group me-2"></i>
                        Room Management

                    </span>

                    <i class="fas fa-chevron-down"></i>

                </a>

                <div class="collapse"
                     id="vendorRoomManagement">

                    <ul class="nav flex-column ms-3">

                        <li>

                            <a href="{{ route('vendor.room-types.index') }}"
                               class="nav-link text-white-50">

                                <i class="far fa-circle me-2"></i>
                                Room Types

                            </a>

                        </li>

                        <li>

                            <a href="{{ route('vendor.room-prices.index') }}"
                               class="nav-link text-white-50">

                                <i class="far fa-circle me-2"></i>
                                Room Prices

                            </a>

                        </li>

                        <li>

                            <a href="{{ route('vendor.room-availabilities.index') }}"
                               class="nav-link text-white-50">

                                <i class="far fa-circle me-2"></i>
                                Room Availability

                            </a>

                        </li>

                        <li>

                            <a href="{{ route('vendor.facilities.index') }}"
                               class="nav-link text-white-50">

                                <i class="far fa-circle me-2"></i>
                                Facilities

                            </a>

                        </li>

                    </ul>

                </div>

            </li>

            {{-- Gallery --}}

            <li class="nav-item">

                <a href="{{ route('vendor.gallery.index') }}"
                   class="nav-link text-white">

                    <i class="fas fa-images me-2"></i>

                    Gallery

                </a>

            </li>

            {{-- ============================= --}}
            {{-- Bookings --}}
            {{-- ============================= --}}

            <li class="nav-item mt-3 text-uppercase small text-secondary px-2">

                Bookings

            </li>

            <li class="nav-item">

                <a href="{{ route('vendor.resort-bookings.index') }}"
                   class="nav-link text-white">

                    <i class="fas fa-book me-2"></i>

                    Resort Bookings

                </a>

            </li>

            {{-- ============================= --}}
            {{-- Finance --}}
            {{-- ============================= --}}

            <li class="nav-item mt-3 text-uppercase small text-secondary px-2">

                Finance

            </li>

            <li class="nav-item">

                <a href="{{ route('vendor.earnings.index') }}"
                   class="nav-link text-white">

                    <i class="fas fa-chart-line me-2"></i>

                    Earnings

                </a>

            </li>

            <li class="nav-item">

                <a href="{{ route('vendor.wallet.index') }}"
                   class="nav-link text-white">

                    <i class="fas fa-wallet me-2"></i>

                    Wallet

                </a>

            </li>

            <li class="nav-item">

                <a href="{{ route('vendor.withdrawals.index') }}"
                   class="nav-link text-white">

                    <i class="fas fa-money-bill-wave me-2"></i>

                    Withdraw

                </a>

            </li>

            {{-- ============================= --}}
            {{-- Account --}}
            {{-- ============================= --}}

            <li class="nav-item mt-3 text-uppercase small text-secondary px-2">

                Account

            </li>

            <li class="nav-item">

                <a href="{{ route('vendor.profile') }}"
                   class="nav-link text-white">

                    <i class="fas fa-user-circle me-2"></i>

                    My Profile

                </a>

            </li>

        </ul>

    </div>

</aside>