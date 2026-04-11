<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Panel')</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap/dist/css/jsvectormap.min.css">
  <link rel="stylesheet" href="{{ asset('contents/admin') }}/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('contents/admin') }}/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('contents/admin') }}/css/datatables.min.css">
  <link rel="stylesheet" href="{{ asset('contents/admin') }}/css/style.css">

  <style>
    :root{
      --sidebar-w: 260px;
      --topbar-h: 68px;
      --border: rgba(0,0,0,.06);
      --bg: #f6f7fb;
    }

    body{ background: var(--bg); }

    .admin-layout{ min-height:100vh; }

    /* Sidebar fixed */
    .admin-sidebar{
      width: var(--sidebar-w);
      height: 100vh;
      position: fixed !important;
      top: 0;
      left: 0;
      z-index: 1000;
      display:flex;
      flex-direction:column;
      transition: transform .25s ease;
    }

    .sidebar-brand{
      height: 84px;
      flex: 0 0 auto;
    }

    .sidebar-body{
      flex: 1 1 auto;
      overflow-y:auto;
      padding: .5rem;
    }
    .sidebar-body::-webkit-scrollbar{ width:8px; }
    .sidebar-body::-webkit-scrollbar-thumb{ background: rgba(255,255,255,.18); border-radius: 999px; }

    /* Main area offset */
    .admin-main{
      margin-left: var(--sidebar-w);
      min-height: 100vh;
      transition: margin-left .25s ease;
    }

    /* Topbar fixed */
    .admin-topbar{
      height: var(--topbar-h);
      position: fixed !important;
      top: 0;
      left: var(--sidebar-w);
      right: 0;
      z-index: 900;
      border-bottom:1px solid var(--border);
      background:#fff;
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding: 0 16px;
      transition: left .25s ease;
    }

    .page-wrap{
      padding: calc(var(--topbar-h) + 25px) 18px 0px 18px;
    }

    .nav-link.active{background: rgba(255,255,255,.12); border-radius:10px;}

    .btn-icon{
      width:40px; height:40px;
      display:inline-flex;
      align-items:center; justify-content:center;
      border-radius:10px;
      border:1px solid var(--border);
      background:#fff;
    }

    .user-dd-btn{
      border:0;
      background: transparent;
      display:flex;
      align-items:center;
      gap:10px;
      padding:6px 10px;
      border-radius: 12px;
    }
    .user-dd-btn:hover{ background: rgba(0,0,0,.04); }

    .avatar{
      width:38px; height:38px;
      border-radius: 999px;
      display:inline-flex;
      align-items:center; justify-content:center;
      background:#111827;
      color:#fff;
      flex: 0 0 auto;
    }
    .user-meta{ line-height:1.1; text-align:left; }
    .user-meta .name{ font-weight:700; font-size:13px; color:#111827; }
    .user-meta .role{ font-size:12px; color:#6b7280; }

    /* Desktop collapsed */
    body.sidebar-collapsed .admin-sidebar{
      transform: translateX(-100%) !important;
    }
    body.sidebar-collapsed .admin-main{ margin-left: 0 !important; }
    body.sidebar-collapsed .admin-topbar{ left: 0 !important; }

    /* Backdrop (mobile overlay) */
    #sidebarBackdrop{
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.35);
      z-index: 950;
      display: none;
    }
    body.sidebar-open #sidebarBackdrop{ display:block; }

    /* Mobile overlay behavior */
    @media (max-width: 991.98px){
      .admin-main{ margin-left: 0 !important; }
      .admin-topbar{ left: 0 !important; }

      .admin-sidebar{ transform: translateX(-100%);     position: fixed !important;
    top: 66px !important;
    left: 0 !important;}
      body.sidebar-open .admin-sidebar{ transform: translateX(0); }
    }
    /* Light (default) */
:root{
  --bg: #f6f7fb;
  --card: #ffffff;
  --text: #111827;
  --muted: #6b7280;
  --border: rgba(0,0,0,.06);
  --topbar: #ffffff;
  --sidebar: #111827;
}

/* Dark mode overrides */
body.dark-mode{
  --bg: #0b1220;
  --card: #0f172a;
  --text: #e5e7eb;
  --muted: #9ca3af;
  --border: rgba(255,255,255,.08);
  --topbar: #0f172a;
  --sidebar: #0b1220;
}

/* Apply variables */
body{ background: var(--bg); color: var(--text); }

.admin-topbar{ background: var(--topbar) !important; border-bottom: 1px solid var(--border) !important; }
.admin-sidebar{ background: var(--sidebar) !important; }

.card{ background: var(--card) !important; border-color: var(--border) !important; }
.card-header{ border-bottom: 1px solid var(--border) !important; }

.text-dark{ color: var(--text) !important; }
.text-muted{ color: var(--muted) !important; }

  </style>
</head>

<body>
@php
  $authUser = Auth::user();
  $roles = App\Models\Role::all();
  $role = strtolower(optional(Auth::user()->role)->role_name ?? 'user');
  $isSuperAdmin   = $role === 'super_admin';
  $isAdmin   = $role === 'admin';
  $isManager = $role === 'manager';
  $routeName = request()->route()?->getName() ?? '';

  $active = fn($name) => $routeName === $name ? 'active' : '';
  $open   = fn($prefix) => str_starts_with($routeName, $prefix) ? 'show' : '';
  $aria   = fn($prefix) => str_starts_with($routeName, $prefix) ? 'true' : 'false';
@endphp

<div class="admin-layout">

  {{-- Backdrop for mobile --}}
  <div id="sidebarBackdrop"></div>

  {{-- SIDEBAR --}}
  <aside class="admin-sidebar bg-dark text-white" id="adminSidebar">

    <div class="sidebar-brand p-3 border-bottom border-secondary">
      <a href="{{ route('dashboard') }}" class="text-white text-decoration-none d-flex align-items-center gap-2">
       <img src="{{ asset('contents/admin') }}/images/ShopOps.png" alt="">
      </a>
      <div class="text-secondary small mt-1">E-commerce Dashboard</div>
    </div>

    <div class="sidebar-body">
      <ul class="nav flex-column gap-1">

        <li class="nav-item">
          <a class="nav-link text-white {{ $active('dashboard') }}" href="{{ route('dashboard') }}">
            <i class="fas fa-home me-2"></i> Dashboard
          </a>
        </li>

        <li>
          <a href="{{ route('dashboard.user.index') }}" class="nav-link text-white {{ $active('dashboard.user.index') }}">
            <i class="fas fa-user me-2"></i> User
          </a>
        </li>

        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">Store</li>

        {{-- Catalog --}}
        <li class="nav-item">
          <a class="nav-link text-white d-flex align-items-center justify-content-between"
             data-bs-toggle="collapse"
             data-bs-target="#menuCatalog"
             role="button"
             aria-expanded="{{ (str_starts_with($routeName,'dashboard.categories') || str_starts_with($routeName,'dashboard.products')) ? 'true' : 'false' }}"
             aria-controls="menuCatalog">
            <span><i class="fas fa-boxes me-2"></i> Catalog</span>
            <i class="fas fa-chevron-down small"></i>
          </a>

          <div class="collapse {{ (str_starts_with($routeName,'dashboard.categories') || str_starts_with($routeName,'dashboard.products')) ? 'show' : '' }}"
               id="menuCatalog">
            <ul class="nav flex-column ms-3 mt-1">
              <li class="nav-item">
                <a class="nav-link text-white-50 {{ $active('dashboard.categories.index') }}"
                   href="{{ route('dashboard.categories.index') }}">
                  <i class="far fa-circle me-2"></i> Categories
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white-50 {{ $active('dashboard.products.index') }}"
                   href="{{ route('dashboard.products.index') }}">
                  <i class="far fa-circle me-2"></i> Products
                </a>
              </li>
            </ul>
          </div>
        </li>

        {{-- Orders --}}
        <li class="nav-item">
          <a class="nav-link text-white d-flex align-items-center justify-content-between"
             data-bs-toggle="collapse"
             data-bs-target="#menuOrders"
             role="button"
             aria-expanded="{{ $aria('dashboard.orders.') }}"
             aria-controls="menuOrders">
            <span><i class="fas fa-receipt me-2"></i> Orders</span>
            <i class="fas fa-chevron-down small"></i>
          </a>

          <div class="collapse {{ $open('dashboard.orders.') }}" id="menuOrders">
            <ul class="nav flex-column ms-3 mt-1">
              <li class="nav-item">
                <a class="nav-link text-white-50 {{ $active('dashboard.orders.index') }}"
                   href="{{ route('dashboard.orders.index') }}">
                  <i class="far fa-circle me-2"></i> All Orders
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white-50 {{ $active('dashboard.orders.pending') }}"
                   href="{{ route('dashboard.orders.pending') }}">
                  <i class="far fa-circle me-2"></i> Pending
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white-50 {{ $active('dashboard.orders.completed') }}"
                   href="{{ route('dashboard.orders.completed') }}">
                  <i class="far fa-circle me-2"></i> Completed
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white {{ $active('dashboard.customers.index') }}"
             href="{{ route('dashboard.customers.index') }}">
            <i class="fas fa-users me-2"></i> Customers
          </a>
        </li>

        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">Marketing</li>
        <li class="nav-item">
          <a class="nav-link text-white {{ str_starts_with($routeName,'dashboard.coupons') ? 'active' : '' }}"
             href="{{ (Route::has('dashboard.coupons.index')) ? route('dashboard.coupons.index') : url('#') }}">
            <i class="fas fa-bullhorn me-2"></i> Coupons
          </a>
        </li>

        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">Operations</li>

        <li class="nav-item">
          <a class="nav-link text-white {{ $active('dashboard.shipping.index') }}"
             href="{{ route('dashboard.shipping.index') }}">
            <i class="fas fa-truck me-2"></i> Shipping
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white {{ $active('dashboard.inventory.index') }}"
             href="{{ route('dashboard.inventory.index') }}">
            <i class="fas fa-warehouse me-2"></i> Inventory
          </a>
        </li>

        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">Reports</li>
        <li class="nav-item">
          <a class="nav-link text-white {{ $active('dashboard.reports.sales') }}"
             href="{{ route('dashboard.reports.sales') }}">
            <i class="fas fa-chart-pie me-2"></i> Sales Report
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white {{ $active('dashboard.reports.products') }}"
             href="{{ route('dashboard.reports.products') }}">
            <i class="fas fa-chart-bar me-2"></i> Product Report
          </a>
        </li>
        @if(!$isManager)
        <li class="nav-item mt-2 text-uppercase small text-secondary px-2">System</li>
        <li class="nav-item">
          <a class="nav-link text-white {{ $active('dashboard.settings.general') }}"
             href="{{ route('dashboard.settings.general') }}">
            <i class="fas fa-cog me-2"></i> Settings
          </a>
        </li>
        @endif
      </ul>
    </div>
  </aside>

  {{-- TOPBAR + MAIN --}}
  <div class="admin-main" id="adminMain">

    <div class="admin-topbar">
      <div class="d-flex align-items-center gap-2">
        <button class="btn-icon" type="button" id="sidebarToggle" title="Toggle menu">
          <i class="fas fa-bars"></i>
        </button>
        <h6 class="mb-0 fw-bold text-dark">@yield('title', 'Dashboard')</h6>
      </div>

      <div class="d-flex align-items-center gap-2">
        
        {{-- Notifications --}}
        <div class="dropdown">
          <button class="btn-icon position-relative" id="notiBtn" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="far fa-bell"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                  id="notiBadge" style="display:none;">0</span>
          </button>

          <ul class="dropdown-menu dropdown-menu-end p-0" style="width:320px;" id="notiMenu">
            <li class="notification_topbar d-flex justify-content-between align-items-center px-3 py-2">
              <span class="fw-bold">Notification</span>
              <button class="btn btn-link p-0 text-decoration-none" type="button" id="clearAllBtn">Clear All</button>
            </li>
            <li><div class="px-3 py-3 text-muted">No notifications</div></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-center small" href="{{ url('dashboard/notifications') }}">View all</a></li>
          </ul>
        </div>

        {{-- Theme Toggle --}}
<button class="btn-icon me-2" id="themeToggle" type="button" title="Toggle theme">
  <i class="fas fa-moon" id="themeMoon"></i>
  <i class="fas fa-sun d-none" id="themeSun"></i>
</button>

{{-- User Dropdown --}}
<div class="dropdown">

  <button class="user-dd-btn" data-bs-toggle="dropdown" aria-expanded="false">

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
      <span class="name">{{ $authUser->name ?? 'User' }}</span><br>
      <span class="role">{{ ucfirst($role) }}</span>
    </span>

    <i class="fas fa-chevron-down text-muted ms-1"></i>
  </button>

  <ul class="dropdown-menu dropdown-menu-end">
    <li>
      <a class="dropdown-item" href="{{ route('dashboard.profile') }}">
        <i class="fas fa-user me-2"></i> Profile
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="{{ route('dashboard.settings.index') }}">
        <i class="fas fa-cog me-2"></i> Manage Settings
      </a>
    </li>
    <li><hr class="dropdown-divider"></li>
    <li>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="dropdown-item">
          <i class="fas fa-sign-out-alt me-2"></i> Logout
        </button>
      </form>
    </li>
  </ul>

</div>


      </div>
    </div>

    <div class="page-wrap">
      @yield('page')
    </div>

  </div>
</div>


{{-- Core --}}
<script src="{{ asset('contents/admin') }}/js/jquery-3.6.0.min.js"></script>
<script src="{{ asset('contents/admin') }}/js/bootstrap.bundle.min.js"></script>

{{-- Plugins --}}
<script src="{{ asset('contents/admin') }}/js/datatables.min.js"></script>
<script src="{{ asset('contents/admin') }}/js/chart.js"></script>

{{-- Map --}}
<script src="{{ asset('contents/admin') }}/js/jsvectormap.js"></script>
<script src="{{ asset('contents/admin') }}/js/world-merc.js"></script>

{{-- App (clean custom.js) --}}
<script src="{{ asset('contents/admin') }}/js/custom.js"></script>

{{-- Global layout JS (sidebar + notifications) --}}
<script>
(function () {

  const body = document.body;

  function ready(fn){
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  ready(function () {

    /* ===============================
       SIDEBAR TOGGLE
    =============================== */

    const btn = document.getElementById('sidebarToggle');
    const backdrop = document.getElementById('sidebarBackdrop');

    const isMobile = () => window.matchMedia('(max-width: 991.98px)').matches;

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
          body.classList.contains('sidebar-collapsed') ? '1' : '0'
        );
      }
    });

    backdrop?.addEventListener('click', function () {
      body.classList.remove('sidebar-open');
    });

    /* ===============================
       THEME TOGGLE
    =============================== */

    const themeBtn  = document.getElementById('themeToggle');
    const moonIcon  = document.getElementById('themeMoon');
    const sunIcon   = document.getElementById('themeSun');

    function applyTheme(mode){
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

    // Load saved theme
    const savedTheme = localStorage.getItem('theme');
    applyTheme(savedTheme === 'dark' ? 'dark' : 'light');

    themeBtn?.addEventListener('click', function(){
      const isDark = body.classList.contains('dark-mode');
      applyTheme(isDark ? 'light' : 'dark');
    });

  });

})();
</script>


{{-- Page-wise scripts --}}
@stack('scripts')

</body>
</html>
