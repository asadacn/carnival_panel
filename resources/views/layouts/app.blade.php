<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title') | {{ isp_name(config('app.name')) }}</title>
    <link rel="shortcut icon" href="{{ isp_favicon() }}" type="image/x-icon">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 5.1.3 -->
    {{-- <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">


    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous"> --}}

    <!-- Ionicons -->
    <link href="//fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/@fortawesome/fontawesome-free/css/all.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/iziToast.min.css') }}">
    <link href="{{ asset('assets/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">
    @yield('page_css')
<!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('web/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('web/css/components.css')}}">
    @yield('page_css')


    @yield('css')

    @livewireStyles

    <style>
        body {
            overflow-x: hidden;
        }

        .main-content {
            animation: pageFadeIn 0.22s ease-out;
            transform-origin: top;
        }

        @keyframes pageFadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .main-sidebar .sidebar-menu li a {
            transition: transform 0.2s ease, background-color 0.2s ease, color 0.2s ease;
        }

        .main-sidebar .sidebar-menu li a:hover {
            transform: translateX(2px);
        }

        :root {
            --navbar-height: 64px;
            --navbar-bg: linear-gradient(135deg, #f0f4ff 0%, #e8f0ff 50%, #f5f3ff 100%);
            --navbar-accent: #667eea;
            --navbar-text: #1a1a2e;
            --navbar-text-muted: #6b7280;
            --navbar-border: rgba(100, 113, 139, 0.08);
        }

        .navbar-bg {
            height: var(--navbar-height);
            background: var(--navbar-bg);
        }

        .main-navbar {
            height: var(--navbar-height);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--navbar-border);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .main-content {
            padding-top: var(--navbar-height);
        }

        .navbar-inner {
            height: var(--navbar-height);
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }

        .navbar-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(106, 102, 235, 0.06);
            color: var(--navbar-text) !important;
            border: 1px solid rgba(106, 102, 235, 0.1);
        }

        .navbar-icon-btn:hover {
            background: rgba(106, 102, 235, 0.12);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(106, 102, 235, 0.1);
        }

        .navbar-icon-btn:focus {
            box-shadow: 0 0 0 3px rgba(106, 102, 235, 0.15);
            color: var(--navbar-text) !important;
        }

         .navbar-page-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--navbar-text);
        }

        .navbar-page-title span {
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.03);
        }

        .nav-link.nav-link-user {
            padding: 4px 12px;
            border-radius: 14px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            height: auto;
            background: rgba(106, 102, 235, 0.06);
            border: 1px solid rgba(106, 102, 235, 0.1);
            margin-right: 4px;
        }

        .nav-link.nav-link-user:hover {
            background: rgba(106, 102, 235, 0.12);
            box-shadow: 0 4px 12px rgba(106, 102, 235, 0.08);
        }

        .user-avatar {
            width: 32px !important;
            height: 32px !important;
            object-fit: cover;
            border: 2px solid rgba(106, 102, 235, 0.12);
        }

        .user-avatar-lg {
            width: 56px !important;
            height: 56px !important;
            object-fit: cover;
            border: 3px solid rgba(106, 102, 235, 0.15);
        }

        .navbar-dropdown {
            width: 240px;
            border-radius: 16px;
            padding: 10px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            background: #ffffff;
        }

        .navbar-dropdown .dropdown-header {
            padding: 0 12px 8px;
            border-bottom: 1px solid #f1f1f1;
            margin-bottom: 8px;
        }

        .navbar-dropdown .dropdown-header:not(.text-center) {
            padding-top: 12px;
        }

        .navbar-dropdown .dropdown-item {
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 500;
            font-size: 13px;
            line-height: 1.4;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            color: #333;
        }

        .navbar-dropdown .dropdown-item:hover {
            background-color: #f0f2f5;
            color: var(--navbar-accent);
            transform: translateX(2px);
        }

        .navbar-dropdown .dropdown-item.text-danger:hover {
            background-color: #fef2f2;
            color: #dc2626 !important;
        }

        .navbar-dropdown .dropdown-divider {
            border-top-color: #f1f1f1;
            margin: 8px 0;
        }

        .lang-switch-wrapper {
            border-radius: 10px;
            background: #f8f9fa;
            margin: 8px 0 12px;
            padding: 8px 10px;
        }

        .lang-switch-btn {
            border-radius: 8px !important;
            padding: 6px 12px !important;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #e0e0e0;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            color: #495057;
            box-shadow: none;
            background: transparent;
        }

        .lang-switch-btn-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border-color: var(--navbar-accent);
            box-shadow: 0 2px 6px rgba(106, 102, 235, 0.25);
        }

        .lang-switch-btn-inactive:hover {
            background: #e9ecef;
            color: #333;
            transform: translateY(-1px);
        }

         @media (max-width: 575.98px) {
            .navbar-page-title {
                display: none;
            }
        }

        /* ─── Section Page Header ────────────────────────────── */
        .section-page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }

        .section-page-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .section-page-header .header-actions {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .btn-create,
        .btn-report,
        .btn-export,
        .btn-back {
            border: none;
            border-radius: 10px;
            padding: 0.55rem 1.1rem;
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px -4px rgba(0, 0, 0, 0.1);
        }

        .btn-create {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
            box-shadow: 0 4px 12px -4px rgba(99, 102, 241, 0.35);
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -4px rgba(99, 102, 241, 0.45);
            color: #fff;
        }

        .btn-report {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            box-shadow: 0 4px 12px -4px rgba(59, 130, 246, 0.35);
        }

        .btn-report:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -4px rgba(59, 130, 246, 0.45);
            color: #fff;
        }

        .btn-export {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            box-shadow: 0 4px 12px -4px rgba(16, 185, 129, 0.35);
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -4px rgba(16, 185, 129, 0.45);
            color: #fff;
        }

        .btn-back {
            background: #fff;
            color: #475569;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .btn-back:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
        }

        .btn-filter {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.82rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px -3px rgba(99, 102, 241, 0.3);
        }

        .btn-filter:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px -3px rgba(99, 102, 241, 0.4);
            color: #fff;
        }

        .btn-reset {
            background: #f1f5f9;
            color: #475569;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.82rem;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .btn-reset:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .btn-xs {
            padding: 3px 10px;
            font-size: 0.75rem;
        }

        /* ─── Dashboard Metric Cards ────────────────────────── */
        .db-kpi-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 14px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: box-shadow .22s, transform .22s;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            cursor: pointer;
        }

        .db-kpi-card:hover {
            box-shadow: 0 4px 18px rgba(0,0,0,.10);
            transform: translateY(-2px);
            border-color: var(--kpi-accent, #6366f1);
        }

        .db-kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .db-kpi-body {
            flex: 1;
            min-width: 0;
        }

        .db-kpi-label {
            display: block;
            font-size: .73rem;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .db-kpi-value {
            display: block;
            font-size: 1.45rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.2;
            margin-top: 2px;
        }

        .db-kpi-arrow {
            flex-shrink: 0;
            opacity: .5;
        }

        /* ─── Dashboard Cards ──────────────────────────────── */
        .db-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 16px;
            box-shadow: 0 1px 6px rgba(0,0,0,.05);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .db-card-header {
            padding: 18px 22px 14px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .db-card-tabs {
            padding: 0 22px;
            border-bottom: 1px solid #f1f5f9;
            background: #fafbfc;
        }

        .db-card-tabs .nav-link {
            font-size: .82rem;
            font-weight: 600;
            color: #64748b;
            padding: 8px 14px;
            border-radius: 8px;
            border: none;
            margin: 6px 2px;
        }

        .db-card-tabs .nav-link.active {
            background: rgba(99,102,241,.12);
            color: #6366f1;
        }

        .db-card-body {
            padding: 18px 22px;
            flex: 1;
        }

        .db-card-body.p-0 {
            padding: 0;
        }

        .db-card-body.pt-2 {
            padding-top: 8px;
        }

        .db-card-title {
            font-size: .93rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 2px;
        }

        /* ─── Stat Pills ───────────────────────────────────── */
        .db-stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: .78rem;
        }

        .db-badge-pill {
            display: inline-flex;
            align-items: center;
            font-size: .7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            letter-spacing: .02em;
        }

        .db-count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            height: 30px;
            border-radius: 50%;
            font-size: .8rem;
            font-weight: 700;
            color: #fff;
            padding: 0 8px;
        }

        /* ─── Mini Stats ───────────────────────────────────── */
        .db-mini-stat {
            border: 1px solid;
            border-radius: 10px;
            padding: 8px 10px;
            text-align: center;
        }

        .db-mini-label {
            display: block;
            font-size: .65rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .db-mini-val {
            display: block;
            font-size: 1.1rem;
            font-weight: 800;
            margin-top: 2px;
        }

        /* ─── Tables ───────────────────────────────────────── */
        .db-table {
            width: 100%;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            font-size: .84rem;
            margin: 0;
        }

        .db-table thead th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0 !important;
            color: #64748b;
            font-weight: 700;
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 10px 14px;
            border-top: none;
            vertical-align: middle;
        }

        .db-table tbody td {
            padding: 10px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        .db-table tbody tr:last-child td {
            border-bottom: none;
        }

        .db-table tbody tr:hover td {
            background: #f8faff;
        }

        .db-table tfoot td {
            padding: 10px 14px;
            border-top: 2px solid #e9ecef;
        }

        /* ─── Misc ─────────────────────────────────────────── */
        .db-link-id {
            font-family: 'SFMono-Regular', Consolas, monospace;
            font-size: .8rem;
            font-weight: 700;
            color: #6366f1;
            text-decoration: none;
        }

        .db-link-id:hover {
            text-decoration: underline;
        }

        .db-pkg-badge {
            background: rgba(99,102,241,.08);
            color: #6366f1;
            font-size: .72rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .db-danger-text {
            color: #ef4444;
        }

        .db-btn-call {
            background: rgba(16,185,129,.1);
            color: #10b981;
            border: 1px solid rgba(16,185,129,.2);
            border-radius: 6px;
            font-size: .75rem;
            font-weight: 600;
            padding: 3px 10px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .db-btn-call:hover {
            background: #10b981;
            color: #fff;
        }

        .db-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            text-align: center;
        }

        .db-commission-box {
            background: #f8fafc;
            border-radius: 14px;
            padding: 24px;
        }

        .db-section-divider {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .db-section-label {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: .8rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .06em;
            white-space: nowrap;
        }

        .db-section-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, #e2e8f0, transparent);
        }

        .hover-primary:hover {
            color: #6366f1 !important;
        }

        /* ─── Expense Badges ──────────────────────────────── */
        .expense-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .category-office_rent { background: rgba(99, 102, 241, 0.1); color: #4338ca; }
        .category-electricity { background: rgba(245, 158, 11, 0.1); color: #92400e; }
        .category-internet { background: rgba(59, 130, 246, 0.1); color: #1e40af; }
        .category-salary { background: rgba(16, 185, 129, 0.1); color: #166534; }
        .category-equipment { background: rgba(244, 63, 94, 0.1); color: #9d174d; }
        .category-maintenance { background: rgba(245, 158, 11, 0.1); color: #b45309; }
        .category-transport { background: rgba(99, 102, 241, 0.1); color: #3730a3; }
        .category-food { background: rgba(245, 158, 11, 0.1); color: #b45309; }
        .category-marketing { background: rgba(139, 92, 246, 0.1); color: #6b21a8; }
        .category-software { background: rgba(59, 130, 246, 0.1); color: #1e40af; }
        .category-bank_charge { background: rgba(239, 68, 68, 0.1); color: #991b1b; }
        .category-other { background: rgba(100, 116, 139, 0.1); color: #475569; }

        .method-cash { background: rgba(245, 158, 11, 0.1); color: #92400e; }
        .method-bkash { background: rgba(16, 185, 129, 0.1); color: #166534; }
        .method-nagad { background: rgba(239, 68, 68, 0.1); color: #991b1b; }
        .method-bank { background: rgba(59, 130, 246, 0.1); color: #1e40af; }
        .method-card { background: rgba(139, 92, 246, 0.1); color: #6b21a8; }
        .method-other { background: rgba(100, 116, 139, 0.1); color: #475569; }

        .expense-amount {
            font-weight: 700;
            color: #0f172a;
        }

        /* ─── Report Cards (kpi aliases) ───────────────────── */
        .kpi-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .kpi-card:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .kpi-body {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .kpi-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #64748b;
            margin-bottom: 2px;
        }

        .kpi-value {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            line-height: 1.1;
            white-space: nowrap;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        /* ─── Chart Canvas ─────────────────────────────────── */
        .chart-canvas {
            width: 100% !important;
            height: auto !important;
        }
    </style>

</head>
<body>

<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            @include('layouts.header')

        </nav>
        <div class="main-sidebar main-sidebar-postion">
            @include('layouts.sidebar')
        </div>
        <!-- Main Content -->
        <div class="main-content">
            @include('flash::message')
            @yield('content')
            <head>
    @livewireStyles
</head>

    {{ $slot ?? '' }}
    @livewireScripts

        </div>
        <footer class="main-footer">
            @include('layouts.footer')
        </footer>
    </div>
</div>

@include('profile.change_password')
@include('profile.edit_profile')

</body>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/iziToast.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>

<!-- Template JS File -->
<script src="{{ asset('web/js/stisla.js') }}"></script>
<script src="{{ asset('web/js/scripts.js') }}"></script>
<script src="{{ mix('assets/js/profile.js') }}"></script>
<script src="{{ mix('assets/js/custom/custom.js') }}"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
@yield('page_js')
@yield('scripts')
@stack('scripts')
<script>
    let loggedInUser =@json(\Illuminate\Support\Facades\Auth::user());
    let loginUrl = '{{ route('login') }}';
    // Loading button plugin (removed from BS4)
    (function ($) {
        $.fn.button = function (action) {
            if (action === 'loading' && this.data('loading-text')) {
                this.data('original-text', this.html()).html(this.data('loading-text')).prop('disabled', true);
            }
            if (action === 'reset' && this.data('original-text')) {
                this.html(this.data('original-text')).prop('disabled', false);
            }
        };
    }(jQuery));
    document.addEventListener('DOMContentLoaded', function () {
        const content = document.querySelector('.main-content');
        if (!content) return;

        const applySubtleTransition = () => {
            content.style.transition = 'opacity 0.14s ease, transform 0.14s ease';
            content.style.opacity = '0.96';
            content.style.transform = 'translateY(1px)';
            setTimeout(() => {
                content.style.opacity = '1';
                content.style.transform = 'translateY(0)';
            }, 50);
        };

        document.querySelectorAll('.main-sidebar .sidebar-menu a').forEach(function (link) {
            link.addEventListener('click', function (event) {
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#')) return;
                if (href.startsWith('http')) return;
                applySubtleTransition();
            });
        });

        const fullscreenBtn = document.getElementById('fullscreenBtn');
        if (fullscreenBtn) {
            fullscreenBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    document.exitFullscreen();
                }
            });
        }
    });
</script>

</html>
