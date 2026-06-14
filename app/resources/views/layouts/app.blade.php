<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NhàTroMana') - Quản Lý Nhà Trọ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1a56db;
            --primary-dark: #1245a8;
            --success: #0ea472;
            --danger: #e02424;
            --warning: #e3a008;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-active: #1a56db;
            --card-shadow: 0 1px 3px rgba(0,0,0,.08), 0 8px 24px rgba(0,0,0,.04);
        }
        * { font-family: 'Be Vietnam Pro', sans-serif; }
        body { background: #f8fafc; color: #1e293b; }

        /* Sidebar */
        .sidebar {
            width: 260px; min-height: 100vh; background: var(--sidebar-bg);
            position: fixed; top: 0; left: 0; z-index: 1000;
            transition: transform .3s ease;
        }
        .sidebar-brand {
            padding: 1.5rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-brand .brand-title {
            font-size: 1.3rem; font-weight: 800; color: #fff;
            background: linear-gradient(135deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .sidebar-brand .brand-sub { font-size: .7rem; color: var(--sidebar-text); letter-spacing: .1em; text-transform: uppercase; }
        .nav-section { padding: .5rem 1rem .25rem; font-size: .65rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: .12em; }
        .sidebar .nav-link {
            color: var(--sidebar-text); padding: .6rem 1.25rem; border-radius: 8px;
            margin: 2px .75rem; font-size: .875rem; font-weight: 500;
            transition: all .2s; display: flex; align-items: center; gap: .75rem;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff; background: rgba(26,86,219,.25);
        }
        .sidebar .nav-link.active { background: var(--sidebar-active); color: #fff; }
        .sidebar .nav-link .bi { font-size: 1rem; opacity: .8; }

        /* Main content */
        .main-content { margin-left: 260px; min-height: 100vh; }
        .topbar {
            background: #fff; padding: .875rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100; box-shadow: 0 1px 3px rgba(0,0,0,.05);
        }
        .page-content { padding: 1.5rem; }

        /* Cards */
        .card { border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: var(--card-shadow); }
        .card-header { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 1rem 1.25rem; font-weight: 600; }

        /* Stat cards */
        .stat-card {
            border-radius: 14px; padding: 1.25rem; color: #fff;
            position: relative; overflow: hidden;
        }
        .stat-card::after {
            content: ''; position: absolute; right: -20px; top: -20px;
            width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,.1);
        }
        .stat-card .stat-icon { font-size: 2rem; opacity: .9; }
        .stat-card .stat-value { font-size: 2rem; font-weight: 800; line-height: 1; }
        .stat-card .stat-label { font-size: .8rem; opacity: .85; margin-top: .25rem; }
        .stat-blue { background: linear-gradient(135deg, #1a56db, #3b82f6); }
        .stat-green { background: linear-gradient(135deg, #057a55, #0ea472); }
        .stat-orange { background: linear-gradient(135deg, #b43403, #f05252); }
        .stat-purple { background: linear-gradient(135deg, #5521b5, #8b5cf6); }

        /* Badge */
        .badge-available { background: #d1fae5; color: #065f46; }
        .badge-rented { background: #fee2e2; color: #991b1b; }
        .badge-maintenance { background: #fef3c7; color: #92400e; }

        /* Property card */
        .property-card {
            border-radius: 14px; overflow: hidden; transition: all .25s;
            border: 1px solid #e2e8f0; background: #fff;
        }
        .property-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,.12); }
        .property-card .prop-img {
            height: 180px; background: linear-gradient(135deg, #dbeafe, #ede9fe);
            display: flex; align-items: center; justify-content: center;
            font-size: 4rem; position: relative;
        }
        .property-card .prop-img .prop-badge {
            position: absolute; top: 10px; left: 10px;
            font-size: .7rem; font-weight: 600; padding: .25rem .6rem; border-radius: 6px;
        }
        .property-card .price-tag { color: var(--primary); font-weight: 800; font-size: 1.15rem; }
        .amenity-tag { background: #f1f5f9; color: #64748b; font-size: .72rem; padding: .2rem .5rem; border-radius: 5px; }

        /* Forms */
        .form-control, .form-select {
            border: 1.5px solid #e2e8f0; border-radius: 8px; padding: .55rem .85rem;
            font-size: .875rem; transition: all .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,86,219,.1);
        }
        .form-label { font-size: .825rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }

        /* Buttons */
        .btn { border-radius: 8px; font-size: .875rem; font-weight: 600; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); }

        /* Alert */
        .alert { border-radius: 10px; border: none; font-size: .875rem; }

        /* Tables */
        .table th { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #64748b; background: #f8fafc; }
        .table td { vertical-align: middle; font-size: .875rem; }

        /* Search bar */
        .search-wrapper { position: relative; }
        .search-wrapper .bi-search { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .search-wrapper input { padding-left: 2.5rem; }

        /* Status indicator */
        .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: .4rem; }
        .status-dot.available { background: #0ea472; }
        .status-dot.rented { background: #e02424; }
        .status-dot.maintenance { background: #e3a008; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-title">🏠 NhàTroMana</div>
        <div class="brand-sub">Hệ thống quản lý nhà trọ</div>
    </div>
    <div class="pt-2">
        <div class="nav-section">Tổng quan</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section">Bất động sản</div>
        <a href="{{ route('properties.index') }}" class="nav-link {{ request()->routeIs('properties.*') ? 'active' : '' }}">
            <i class="bi bi-buildings"></i> Danh sách phòng/nhà
        </a>
        <a href="{{ route('properties.create') }}" class="nav-link">
            <i class="bi bi-plus-circle"></i> Đăng tin mới
        </a>

        <div class="nav-section">Quản lý</div>
        <a href="{{ route('tenants.index') }}" class="nav-link {{ request()->routeIs('tenants.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Khách thuê
        </a>
        <a href="{{ route('contracts.index') }}" class="nav-link {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
            <i class="bi bi-file-text"></i> Hợp đồng
        </a>

        <div class="nav-section">Hệ thống</div>
        <a href="http://localhost:8080" target="_blank" class="nav-link">
            <i class="bi bi-database"></i> Quản lý DB
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-gear"></i> Cài đặt
        </a>
    </div>
</nav>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-md-none" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <nav aria-label="breadcrumb" class="mb-0">
                <ol class="breadcrumb mb-0" style="font-size:.8rem">
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success">🟢 Online</span>
            <span style="font-size:.875rem; font-weight:600; color:#374151">Admin</span>
        </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });
</script>
@stack('scripts')
</body>
</html>
