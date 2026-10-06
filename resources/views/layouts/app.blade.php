<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SIMADA</title>
    <meta name="description" content="Sistem Informasi Pemantauan Agenda - SIMADA">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    @stack('styles')
    
    <style>
        :root {
            --primary: #6366F1;
            --primary-dark: #4F46E5;
            --primary-light: #818CF8;
            --secondary: #0EA5E9;
            --accent: #F59E0B;
            --success: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
            --sidebar-width: 280px;
            --header-height: 65px;
            --bottom-nav-height: 64px;
            --bg-primary: #F8FAFC;
            --bg-card: #FFFFFF;
            --text-primary: #1E293B;
            --text-secondary: #64748B;
            --border-color: #E2E8F0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 10px 30px rgba(0,0,0,0.1);
            --gradient-primary: linear-gradient(135deg, #6366F1 0%, #8B5CF6 50%, #A78BFA 100%);
            --gradient-secondary: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);
            --gradient-success: linear-gradient(135deg, #10B981 0%, #34D399 100%);
            --gradient-danger: linear-gradient(135deg, #EF4444 0%, #F87171 100%);
            --gradient-warning: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
        }

        [data-bs-theme="dark"] {
            --bg-primary: #0F172A;
            --bg-card: #1E293B;
            --text-primary: #F1F5F9;
            --text-secondary: #94A3B8;
            --border-color: #334155;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* ======== DESKTOP SIDEBAR ======== */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1E1B4B 0%, #312E81 50%, #3730A3 100%);
            z-index: 1050;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }

        .sidebar-brand {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand .brand-icon {
            width: 42px; height: 42px;
            background: var(--gradient-secondary);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: white; flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.4);
        }

        .sidebar-brand .brand-text h5 { font-weight: 800; font-size: 18px; margin-bottom: 0; letter-spacing: 1px; color: white; }
        .sidebar-brand .brand-text small { font-size: 10px; color: rgba(255,255,255,0.6); letter-spacing: 0.5px; }

        .sidebar-nav { padding: 16px 12px; }

        .nav-section-title {
            color: rgba(255,255,255,0.4);
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.5px;
            padding: 12px 16px 8px;
        }

        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.65);
            padding: 10px 16px;
            border-radius: 10px;
            display: flex; align-items: center; gap: 12px;
            font-size: 13.5px; font-weight: 500;
            margin-bottom: 2px;
            transition: all 0.2s ease;
            position: relative;
            text-decoration: none;
        }

        .sidebar-nav .nav-link:hover { color: white; background: rgba(255,255,255,0.1); transform: translateX(4px); }

        .sidebar-nav .nav-link.active {
            color: white;
            background: rgba(99, 102, 241, 0.4);
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
        }

        .sidebar-nav .nav-link.active::before {
            content: '';
            position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 20px; background: white; border-radius: 0 3px 3px 0;
        }

        .sidebar-nav .nav-link i { font-size: 18px; width: 22px; text-align: center; }
        .sidebar-nav .nav-badge { background: var(--danger); color: white; font-size: 10px; padding: 2px 7px; border-radius: 10px; margin-left: auto; font-weight: 700; }

        /* ======== DESKTOP MAIN ======== */
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; transition: margin-left 0.3s; }

        /* ======== DESKTOP TOP HEADER ======== */
        .top-header {
            height: var(--header-height);
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px;
            position: sticky; top: 0; z-index: 1040;
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.85);
        }

        [data-bs-theme="dark"] .top-header { background: rgba(30, 41, 59, 0.85); }

        .header-left { display: flex; align-items: center; gap: 16px; }
        .header-left h4 { font-weight: 700; font-size: 18px; margin-bottom: 0; }
        .header-right { display: flex; align-items: center; gap: 12px; }

        .btn-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-secondary);
            transition: all 0.2s; cursor: pointer; position: relative;
        }

        .btn-icon:hover { background: var(--primary); color: white; border-color: var(--primary); transform: translateY(-1px); box-shadow: var(--shadow-md); }

        .notification-badge {
            position: absolute; top: -4px; right: -4px;
            width: 18px; height: 18px;
            background: var(--danger); color: white;
            font-size: 10px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-weight: 700;
        }

        .user-profile { display: flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 12px; cursor: pointer; transition: all 0.2s; }
        .user-profile:hover { background: rgba(99, 102, 241, 0.08); }

        .user-avatar {
            width: 36px; height: 36px; border-radius: 10px;
            background: var(--gradient-primary);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 14px;
        }

        .user-info .name { font-weight: 600; font-size: 13px; }
        .user-info .role { font-size: 11px; color: var(--text-secondary); text-transform: capitalize; }

        /* ======== CONTENT AREA ======== */
        .content-area { padding: 24px; }

        /* ======== CARDS ======== */
        .card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; box-shadow: var(--shadow-sm); transition: all 0.3s ease; }
        .card:hover { box-shadow: var(--shadow-md); }
        .card-header { background: transparent; border-bottom: 1px solid var(--border-color); padding: 16px 20px; font-weight: 600; }
        .card-body { padding: 20px; }

        .stat-card { border: none; border-radius: 16px; overflow: hidden; position: relative; }
        .stat-card .card-body { position: relative; z-index: 1; color: white; padding: 24px; }
        .stat-card .stat-icon { width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 16px; }
        .stat-card .stat-value { font-size: 32px; font-weight: 800; line-height: 1; margin-bottom: 4px; }
        .stat-card .stat-label { font-size: 13px; opacity: 0.85; font-weight: 500; }
        .stat-card .stat-bg-pattern { position: absolute; right: -20px; bottom: -20px; font-size: 100px; opacity: 0.1; z-index: 0; }
        .stat-card.gradient-primary { background: var(--gradient-primary); }
        .stat-card.gradient-secondary { background: var(--gradient-secondary); }
        .stat-card.gradient-success { background: var(--gradient-success); }
        .stat-card.gradient-danger { background: var(--gradient-danger); }
        .stat-card.gradient-warning { background: var(--gradient-warning); }

        .table { color: var(--text-primary); }
        .table thead th { background: rgba(99,102,241,0.05); border-bottom: 2px solid var(--border-color); font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); padding: 12px 16px; }
        .table tbody td { padding: 14px 16px; vertical-align: middle; border-bottom: 1px solid var(--border-color); }
        .table tbody tr:hover { background: rgba(99,102,241,0.03); }

        .btn-primary { background: var(--gradient-primary); border: none; border-radius: 10px; font-weight: 600; padding: 10px 20px; transition: all 0.3s; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99,102,241,0.4); background: var(--primary-dark); }
        .btn-outline-primary { border-color: var(--primary); color: var(--primary); border-radius: 10px; font-weight: 600; }
        .btn-outline-primary:hover { background: var(--primary); border-color: var(--primary); }
        .btn-success { background: var(--gradient-success); border: none; border-radius: 10px; font-weight: 600; }
        .btn-danger { background: var(--gradient-danger); border: none; border-radius: 10px; font-weight: 600; }
        .btn-warning { background: var(--gradient-warning); border: none; border-radius: 10px; font-weight: 600; }

        .badge { font-weight: 600; padding: 5px 10px; border-radius: 8px; font-size: 11px; }

        .form-control, .form-select { border-radius: 10px; border: 1.5px solid var(--border-color); padding: 10px 14px; font-size: 14px; transition: all 0.2s; background: var(--bg-card); color: var(--text-primary); }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .form-label { font-weight: 600; font-size: 13px; color: var(--text-secondary); margin-bottom: 6px; }

        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .page-header h1 { font-size: 24px; font-weight: 800; margin-bottom: 0; }
        .page-header .breadcrumb { margin-bottom: 0; font-size: 13px; }

        .alert { border: none; border-radius: 12px; padding: 14px 20px; font-weight: 500; }

        .sidebar-toggle { display: none; background: none; border: none; font-size: 22px; color: var(--text-primary); cursor: pointer; }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1045; }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-in { animation: fadeInUp 0.4s ease forwards; }
        .animate-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .animate-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .animate-delay-3 { animation-delay: 0.3s; opacity: 0; }

        .dropdown-menu { border: 1px solid var(--border-color); border-radius: 12px; box-shadow: var(--shadow-lg); padding: 8px; background: var(--bg-card); }
        .dropdown-item { border-radius: 8px; padding: 8px 14px; font-size: 13px; color: var(--text-primary); }
        .dropdown-item:hover { background: rgba(99,102,241,0.08); color: var(--primary); }

        .pagination .page-link { border-radius: 8px; margin: 0 2px; border: 1px solid var(--border-color); color: var(--text-primary); font-weight: 500; font-size: 13px; }
        .pagination .page-link:hover { background: var(--primary); border-color: var(--primary); color: white; }
        .pagination .active .page-link { background: var(--primary); border-color: var(--primary); }

        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 64px; color: var(--text-secondary); opacity: 0.3; margin-bottom: 16px; }
        .empty-state h5 { color: var(--text-secondary); font-weight: 600; }
        .empty-state p { color: var(--text-secondary); font-size: 14px; }

        .modal-content { border: none; border-radius: 16px; box-shadow: var(--shadow-lg); }
        .modal-header { border-bottom: 1px solid var(--border-color); padding: 16px 24px; }
        .modal-body { padding: 24px; }

        /* ================================================
           MOBILE LAYOUT (< 768px)
        ================================================ */
        @media (max-width: 767.98px) {
            /* Hide desktop sidebar & top header */
            .sidebar { display: none; }
            .top-header { display: none; }
            .main-content { margin-left: 0; }

            /* Show mobile header */
            .mobile-header { display: flex; }

            /* Content area padded for bottom nav */
            .content-area {
                padding: 12px 12px calc(var(--bottom-nav-height) + 16px);
            }

            .page-header h1 { font-size: 20px; }
        }

        /* ================================================
           MOBILE TOP HEADER
        ================================================ */
        .mobile-header {
            display: none; /* hidden on desktop */
            position: sticky; top: 0; z-index: 1040;
            background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
            padding: 14px 16px 50px;
            flex-direction: column;
            gap: 12px;
        }

        .mobile-header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mobile-greeting { color: white; }
        .mobile-greeting .sub { font-size: 12px; opacity: 0.8; font-weight: 400; }
        .mobile-greeting .name { font-size: 17px; font-weight: 700; }

        .mobile-header-actions { display: flex; gap: 8px; }

        .mobile-icon-btn {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 18px; text-decoration: none;
            position: relative;
            border: none; cursor: pointer;
        }

        .mobile-icon-btn .badge-dot {
            position: absolute; top: 5px; right: 5px;
            width: 10px; height: 10px;
            background: #F59E0B;
            border-radius: 50%;
            border: 2px solid #4F46E5;
        }

        /* Pull-up content area over header */
        .mobile-content-pull {
            margin-top: -36px;
            position: relative;
            z-index: 1;
        }

        /* ================================================
           MOBILE QUICK MENU GRID
        ================================================ */
        .quick-menu-card {
            background: white;
            border-radius: 20px;
            padding: 20px 16px 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: none;
            margin-bottom: 14px;
        }

        .quick-menu-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
        }

        .quick-menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 12px 4px 8px;
            text-decoration: none;
            border-radius: 14px;
            transition: all 0.15s;
            cursor: pointer;
        }

        .quick-menu-item:active {
            background: rgba(99,102,241,0.08);
            transform: scale(0.95);
        }

        .quick-menu-icon {
            width: 54px; height: 54px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            color: white;
            position: relative;
        }

        .quick-menu-label {
            font-size: 11px;
            font-weight: 600;
            color: #334155;
            text-align: center;
            line-height: 1.3;
        }

        .quick-menu-item .badge-count {
            position: absolute; top: -4px; right: -4px;
            background: #EF4444; color: white;
            font-size: 9px; font-weight: 700;
            width: 18px; height: 18px;
            border-radius: 50%; border: 2px solid white;
            display: flex; align-items: center; justify-content: center;
        }

        /* ================================================
           MOBILE STAT CARDS (horizontal scroll)
        ================================================ */
        .mobile-stats-scroll {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 4px 2px 8px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .mobile-stats-scroll::-webkit-scrollbar { display: none; }

        .mobile-stat-card {
            flex-shrink: 0;
            width: 150px;
            border-radius: 16px;
            padding: 16px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .mobile-stat-card .s-icon {
            width: 36px; height: 36px;
            background: rgba(255,255,255,0.25);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .mobile-stat-card .s-value { font-size: 26px; font-weight: 800; line-height: 1; margin-bottom: 4px; }
        .mobile-stat-card .s-label { font-size: 11px; opacity: 0.85; font-weight: 500; }
        .mobile-stat-card .s-bg { position: absolute; right: -10px; bottom: -10px; font-size: 70px; opacity: 0.1; }

        /* ================================================
           MOBILE AGENDA SECTION
        ================================================ */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            margin-top: 6px;
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #1E293B;
        }

        .section-link {
            font-size: 12px;
            color: #6366F1;
            font-weight: 600;
            text-decoration: none;
        }

        .mobile-agenda-item {
            background: white;
            border-radius: 14px;
            padding: 14px;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            display: flex;
            align-items: stretch;
            gap: 12px;
            text-decoration: none;
            color: inherit;
            border: 1px solid #F1F5F9;
            transition: all 0.15s;
        }

        .mobile-agenda-item:active { transform: scale(0.98); }

        .mobile-agenda-strip {
            width: 4px;
            border-radius: 4px;
            flex-shrink: 0;
        }

        .mobile-agenda-content { flex: 1; min-width: 0; }
        .mobile-agenda-time { font-size: 12px; color: #94A3B8; font-weight: 600; margin-bottom: 3px; }
        .mobile-agenda-title { font-size: 14px; font-weight: 700; color: #1E293B; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .mobile-agenda-loc { font-size: 11px; color: #64748B; display: flex; align-items: center; gap: 3px; }

        .mobile-agenda-badge {
            flex-shrink: 0;
            align-self: flex-start;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
        }

        .mobile-empty-agenda {
            background: white;
            border-radius: 14px;
            padding: 32px 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .mobile-empty-agenda i { font-size: 36px; color: #CBD5E1; display: block; margin-bottom: 8px; }
        .mobile-empty-agenda p { color: #94A3B8; font-size: 13px; margin: 0; }

        /* ================================================
           BOTTOM NAV BAR
        ================================================ */
        .bottom-nav {
            display: none; /* hidden on desktop */
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: var(--bottom-nav-height);
            background: white;
            border-top: 1px solid #E2E8F0;
            z-index: 1050;
            justify-content: space-around;
            align-items: center;
            padding: 0 4px;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            padding: 8px 12px;
            text-decoration: none;
            color: #94A3B8;
            border-radius: 12px;
            transition: all 0.2s;
            min-width: 56px;
            position: relative;
        }

        .bottom-nav-item.active { color: #6366F1; }

        .bottom-nav-item i { font-size: 22px; }

        .bottom-nav-item span {
            font-size: 10px;
            font-weight: 600;
            line-height: 1;
        }

        .bottom-nav-item .nav-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 8px; height: 8px;
            background: #EF4444;
            border-radius: 50%;
            border: 1.5px solid white;
        }

        /* Center FAB button */
        .bottom-nav-fab {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            text-decoration: none;
        }

        .fab-btn {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 24px;
            box-shadow: 0 4px 14px rgba(99,102,241,0.5);
            margin-top: -18px;
        }

        .bottom-nav-fab span {
            font-size: 10px;
            font-weight: 600;
            color: #94A3B8;
            line-height: 1;
        }

        @media (max-width: 767.98px) {
            .bottom-nav { display: flex; }
            .sidebar-overlay.show { display: block; }
        }

        @media (min-width: 768px) {
            .mobile-header { display: none !important; }
            .mobile-content-pull { margin-top: 0; }
        }

        /* Dark mode bottom nav */
        [data-bs-theme="dark"] .bottom-nav {
            background: #1E293B;
            border-color: #334155;
        }

        [data-bs-theme="dark"] .quick-menu-card,
        [data-bs-theme="dark"] .mobile-agenda-item,
        [data-bs-theme="dark"] .mobile-empty-agenda {
            background: #1E293B;
            border-color: #334155;
        }

        [data-bs-theme="dark"] .quick-menu-label,
        [data-bs-theme="dark"] .mobile-agenda-title { color: #F1F5F9; }
        [data-bs-theme="dark"] .section-title { color: #F1F5F9; }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- ===== DESKTOP SIDEBAR ===== --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-calendar2-check"></i></div>
            <div class="brand-text">
                <h5>SIMADA</h5>
                <small>Pemantauan Agenda</small>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-title">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('kegiatan.kalender') }}" class="nav-link {{ request()->routeIs('kegiatan.kalender') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i><span>Kalender Kegiatan</span>
            </a>
            <a href="{{ route('kegiatan.index') }}" class="nav-link {{ request()->routeIs('kegiatan.*') && !request()->routeIs('kegiatan.kalender') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-check"></i><span>Daftar Kegiatan</span>
            </a>
            <div class="nav-section-title">Manajemen</div>
            <a href="{{ route('pegawai.index') }}" class="nav-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i><span>Data Pegawai</span>
            </a>
            <a href="{{ route('disposisi.index') }}" class="nav-link {{ request()->routeIs('disposisi.*') ? 'active' : '' }}">
                <i class="bi bi-envelope-paper-fill"></i><span>Disposisi</span>
                @php $pendingDisposisi = 0; if (auth()->check() && auth()->user()->pegawai) { $pendingDisposisi = \App\Models\Disposisi::where('kepada_pegawai_id', auth()->user()->pegawai->id)->where('status', 'pending')->count(); } @endphp
                @if($pendingDisposisi > 0)<span class="nav-badge">{{ $pendingDisposisi }}</span>@endif
            </a>
            <div class="nav-section-title">Laporan</div>
            <a href="{{ route('rekapitulasi.index') }}" class="nav-link {{ request()->routeIs('rekapitulasi.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i><span>Rekapitulasi</span>
            </a>
            <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-pdf-fill"></i><span>Cetak Laporan</span>
            </a>
            <div class="nav-section-title">Lainnya</div>
            <a href="{{ route('notifikasi.index') }}" class="nav-link {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}">
                <i class="bi bi-bell-fill"></i><span>Notifikasi</span>
                @php $unreadCount = 0; if (auth()->check()) { $unreadCount = \App\Models\Notifikasi::where('user_id', auth()->id())->where('dibaca', false)->count(); } @endphp
                @if($unreadCount > 0)<span class="nav-badge">{{ $unreadCount }}</span>@endif
            </a>
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i><span>Pengaturan Profil</span>
            </a>
        </nav>
    </aside>

    {{-- ===== DESKTOP MAIN CONTENT ===== --}}
    <div class="main-content">

        {{-- ===== MOBILE TOP HEADER ===== --}}
        <div class="mobile-header">
            <div class="mobile-header-top">
                <div class="mobile-greeting">
                    <div class="sub">Selamat datang,</div>
                    <div class="name">{{ auth()->check() ? Str::limit(auth()->user()->name, 18) : 'User' }} 👋</div>
                </div>
                <div class="mobile-header-actions">
                    <button class="mobile-icon-btn" id="darkModeToggleMobile" title="Toggle Dark Mode">
                        <i class="bi bi-moon-stars-fill"></i>
                    </button>
                    <a href="{{ route('notifikasi.index') }}" class="mobile-icon-btn">
                        <i class="bi bi-bell-fill"></i>
                        @php $unreadCount = auth()->check() ? \App\Models\Notifikasi::where('user_id', auth()->id())->where('dibaca', false)->count() : 0; @endphp
                        @if($unreadCount > 0)<span class="badge-dot"></span>@endif
                    </a>
                    <div class="dropdown">
                        <div class="mobile-icon-btn" data-bs-toggle="dropdown">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i> Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        {{-- /mobile header --}}

        {{-- ===== DESKTOP TOP HEADER ===== --}}
        <header class="top-header">
            <div class="header-left">
                <h4>@yield('title', 'Dashboard')</h4>
            </div>
            <div class="header-right">
                <button class="btn-icon" id="darkModeToggle" title="Toggle Dark Mode">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>
                <a href="{{ route('notifikasi.index') }}" class="btn-icon">
                    <i class="bi bi-bell-fill"></i>
                    @if(isset($unreadCount) && $unreadCount > 0)
                        <span class="notification-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                </a>
                <div class="dropdown">
                    <div class="user-profile" data-bs-toggle="dropdown">
                        <div class="user-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'US' }}</div>
                        <div class="user-info">
                            <div class="name">{{ auth()->check() ? Str::limit(auth()->user()->name, 20) : 'User' }}</div>
                            <div class="role">{{ auth()->check() ? auth()->user()->role : '' }}</div>
                        </div>
                        <i class="bi bi-chevron-down ms-1" style="font-size: 12px; color: var(--text-secondary);"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i> Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        {{-- ===== CONTENT AREA ===== --}}
        <div class="content-area">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Terdapat kesalahan:</strong>
                    <ul class="mb-0 mt-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
        {{-- /content area --}}

    </div>
    {{-- /main content --}}

    {{-- ===== MOBILE BOTTOM NAV BAR ===== --}}
    <nav class="bottom-nav" id="bottomNav">
        <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('kegiatan.kalender') }}" class="bottom-nav-item {{ request()->routeIs('kegiatan.kalender') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i>
            <span>Kalender</span>
        </a>
        {{-- FAB Center Button --}}
        <a href="{{ route('kegiatan.create') }}" class="bottom-nav-fab">
            <div class="fab-btn">
                <i class="bi bi-plus-lg"></i>
            </div>
            <span>Tambah</span>
        </a>
        <a href="{{ route('disposisi.index') }}" class="bottom-nav-item {{ request()->routeIs('disposisi.*') ? 'active' : '' }}">
            <i class="bi bi-envelope-paper-fill"></i>
            <span>Disposisi</span>
            @php $pendingD = 0; if (auth()->check() && auth()->user()->pegawai) { $pendingD = \App\Models\Disposisi::where('kepada_pegawai_id', auth()->user()->pegawai->id)->where('status', 'pending')->count(); } @endphp
            @if($pendingD > 0)<span class="nav-dot"></span>@endif
        </a>
        <a href="{{ route('profile.edit') }}" class="bottom-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i>
            <span>Profil</span>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dark mode
        const html = document.documentElement;
        const applyTheme = (theme) => {
            html.setAttribute('data-bs-theme', theme);
            const icon = theme === 'dark' ? '<i class="bi bi-sun-fill"></i>' : '<i class="bi bi-moon-stars-fill"></i>';
            document.querySelectorAll('#darkModeToggle, #darkModeToggleMobile').forEach(btn => {
                if (btn) btn.innerHTML = icon;
            });
        };

        if (localStorage.getItem('theme') === 'dark') applyTheme('dark');

        document.getElementById('darkModeToggle')?.addEventListener('click', () => {
            const next = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', next);
            applyTheme(next);
        });

        document.getElementById('darkModeToggleMobile')?.addEventListener('click', () => {
            const next = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', next);
            applyTheme(next);
        });

        // Sidebar overlay for when we have sidebar on tablet
        document.getElementById('sidebarOverlay')?.addEventListener('click', () => {
            document.getElementById('sidebar')?.classList.remove('show');
            document.getElementById('sidebarOverlay')?.classList.remove('show');
        });
    </script>

    @stack('scripts')
</body>
</html>
