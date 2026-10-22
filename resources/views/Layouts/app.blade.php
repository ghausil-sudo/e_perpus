<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - E-Perpus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        :root {
            --color-primary: #2563eb;
            --color-indigo: #6366f1;
            --color-success: #22c55e;
            --color-info: #06b6d4;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;
            --color-text: #1f2937;
            --color-muted: #6b7280;
            --color-border: #eef0f6;
            --color-background: #f4f5fa;
            --color-surface: #ffffff;
            --radius-card: 14px;
            --radius-control: 10px;
            --shadow-card: 0 1px 2px rgba(16, 24, 40, .04), 0 8px 24px rgba(16, 24, 40, .06);
            --shadow-card-hover: 0 3px 8px rgba(16, 24, 40, .06), 0 12px 30px rgba(16, 24, 40, .09);
        }
        * { scrollbar-color: #cbd5e1 transparent; }
        body { font-family: 'Inter', sans-serif; color: var(--color-text); background: var(--color-background); overflow-x: hidden; }
        .nav-link { position: relative; display: flex; align-items: center; padding: 0.65rem 0.75rem; font-size: 0.875rem; font-weight: 500; color: var(--color-muted); border-radius: 9px; transition: background-color .16s ease, color .16s ease, transform .16s ease; text-decoration: none; }
        .nav-link:hover { background-color: #f6f8fc; color: var(--color-text); }
        .nav-link.active { background-color: #eaf2ff; color: var(--color-primary); }
        .nav-link.active::before { content: ""; position: absolute; left: -0.75rem; top: 20%; bottom: 20%; width: 3px; border-radius: 0 4px 4px 0; background: var(--color-primary); }
        .nav-link.active:hover { background-color: #eaf2ff; color: #1d4ed8; }
        aside { background: var(--color-surface) !important; border-color: var(--color-border) !important; box-shadow: 4px 0 18px rgba(16, 24, 40, .025); }
        aside > div:first-child { border-color: var(--color-border) !important; }
        aside nav > div { color: #98a2b3 !important; font-size: .66rem !important; font-weight: 600 !important; letter-spacing: .13em !important; }
        aside nav svg { stroke-width: 1.6; }
        .card { background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-card); box-shadow: var(--shadow-card); }
        .btn { display: inline-flex; min-height: 40px; align-items: center; justify-content: center; padding: 0.55rem 1rem; font-size: 0.875rem; font-weight: 600; border-radius: var(--radius-control); transition: color .16s ease, background-color .16s ease, border-color .16s ease, box-shadow .16s ease, transform .16s ease; cursor: pointer; border: 1px solid transparent; text-decoration: none; }
        .btn-primary { background-color: var(--color-primary); color: #ffffff; box-shadow: 0 1px 2px rgba(37, 99, 235, .2); }
        .btn-primary:hover { background-color: #1d4ed8; box-shadow: 0 4px 10px rgba(37, 99, 235, .2); transform: translateY(-1px); }
        .btn-secondary { background-color: #ffffff; color: #344054; border: 1px solid #d0d5dd; }
        .btn-secondary:hover { background-color: #f9fafb; border-color: #98a2b3; }
        .btn-action {
            display: inline-flex;
            min-height: 32px;
            align-items: center;
            justify-content: center;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: 0.01em;
            text-decoration: none;
            transition: transform .16s ease, box-shadow .16s ease, background-color .16s ease, border-color .16s ease, color .16s ease;
            border: 1px solid transparent;
        }
        .btn-action:hover { transform: translateY(-1px); }
        .btn-action--primary {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #dbeafe;
            box-shadow: 0 2px 6px rgba(37, 99, 235, .08);
        }
        .btn-action--primary:hover {
            background: #dbeafe;
            border-color: #bfdbfe;
            color: #1e40af;
        }
        .btn-action--danger {
            background: #fff5f4;
            color: #b42318;
            border-color: #fee4e2;
            box-shadow: 0 2px 6px rgba(180, 35, 24, .06);
        }
        .btn-action--danger:hover {
            background: #fee4e2;
            border-color: #fecdca;
            color: #912018;
        }
        .btn-action:disabled { opacity: .55; cursor: not-allowed; pointer-events: none; }
        .input { width: 100%; min-height: 40px; padding: 0.55rem 0.75rem; font-size: 0.875rem; color: #344054; background-color: #fff; border: 1px solid #d0d5dd; border-radius: var(--radius-control); transition: border-color .16s ease, box-shadow .16s ease; }
        .input:focus { outline: none; border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, .14); }
        .select2-container { width: 100% !important; }
        .select2-container .select2-selection--single { height: 42px; border: 1px solid #d0d5dd; border-radius: var(--radius-control); }
        .select2-container--default .select2-selection--single .select2-selection__rendered { padding: 6px 12px; color: #344054; line-height: 28px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px; right: 8px; }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single { border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, .14); }
        :focus-visible { outline: 3px solid rgba(37, 99, 235, .45); outline-offset: 2px; }
        .label { display: block; font-size: 0.875rem; font-weight: 600; color: #344054; margin-bottom: 0.35rem; }
        table { border-collapse: separate; border-spacing: 0; }
        table thead { background-color: #f8f9fc !important; }
        table thead th { color: #667085 !important; letter-spacing: .07em; font-size: .68rem !important; font-weight: 600 !important; }
        table tbody tr { transition: background-color .15s ease; }
        table tbody tr:hover { background-color: #f8faff; }
        table td, table th { border-color: #eef0f6 !important; }
        table td a[href$="/edit"],
        table td form.inline button[type="submit"] {
            display: inline-flex;
            min-height: 32px;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.7rem;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: .75rem;
            font-weight: 600;
            line-height: 1;
            text-decoration: none;
            transition: color .16s ease, background-color .16s ease, border-color .16s ease, box-shadow .16s ease, transform .16s ease;
        }
        table td a[href$="/edit"] {
            color: #1d4ed8 !important;
            background: #eff6ff;
            border-color: #dbeafe;
        }
        table td a[href$="/edit"]:hover {
            color: #1e40af !important;
            background: #dbeafe;
            border-color: #bfdbfe;
            box-shadow: 0 2px 5px rgba(37, 99, 235, .12);
            transform: translateY(-1px);
        }
        table td form.inline button[type="submit"] {
            color: #b42318 !important;
            background: #fff5f4;
            border-color: #fee4e2;
        }
        table td form.inline button[type="submit"]:hover:not(:disabled) {
            color: #912018 !important;
            background: #fee4e2;
            border-color: #fecdca;
            box-shadow: 0 2px 5px rgba(180, 35, 24, .1);
            transform: translateY(-1px);
        }
        table td form.inline button[type="submit"]:disabled {
            cursor: not-allowed;
            opacity: .5;
        }
        .flex-1.ml-60 > header { min-height: 76px; height: auto; margin: 20px 28px 0; padding: 14px 24px; border: 1px solid var(--color-border); border-radius: var(--radius-card); box-shadow: var(--shadow-card); }
        .flex-1.ml-60 > header h1 { color: var(--color-text); font-size: 1.15rem; font-weight: 600; letter-spacing: -.02em; }
        .flex-1.ml-60 > header > div > div:nth-of-type(2) { position: relative; }
        .flex-1.ml-60 > header > div > div:nth-of-type(2)::after { content: ""; position: absolute; width: 9px; height: 9px; right: 0; bottom: 0; border: 2px solid white; border-radius: 50%; background: var(--color-success); }
        .flex-1.ml-60 > main { padding: 28px; background: var(--color-background); }
        .flex-1.ml-60 > main > div { gap: 24px; }
        .flex-1.ml-60 > main .card > .border-b { border-color: var(--color-border) !important; }
        .dashboard-stat { position: relative; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
        .dashboard-stat:hover { transform: translateY(-2px); box-shadow: var(--shadow-card-hover); }
        .dashboard-stat::after { content: ""; position: absolute; right: 0; bottom: 0; left: 0; height: 3px; background: var(--stat-accent, var(--color-primary)); }
        .dashboard-stat--indigo { --stat-accent: var(--color-indigo); }
        .dashboard-stat--success { --stat-accent: var(--color-success); }
        .dashboard-stat--info { --stat-accent: var(--color-info); }
        .dashboard-stat--danger { --stat-accent: var(--color-danger); }
        .dashboard-stat__top { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .dashboard-stat__icon { display: flex; width: 42px; height: 42px; flex: 0 0 auto; align-items: center; justify-content: center; border-radius: 12px; color: var(--stat-accent, var(--color-primary)); background: color-mix(in srgb, var(--stat-accent, var(--color-primary)) 11%, white); }
        .dashboard-stat__icon svg { width: 21px; height: 21px; stroke-width: 1.7; }
        .dashboard-stat h3 { margin-top: 0; font-size: 2rem; line-height: 1; font-weight: 700; letter-spacing: -.04em; font-variant-numeric: tabular-nums; color: var(--color-text); }
        .dashboard-stat > p:first-of-type { margin-top: 18px; color: #475467; font-size: .76rem; letter-spacing: .06em; }
        .dashboard-stat > p:last-child { color: #98a2b3; }
        .flex-1.ml-60 > main .card > .px-6.py-4.border-b h2,
        .flex-1.ml-60 > main .card > h2 { font-size: 1rem; font-weight: 600; color: var(--color-text); }
        .dashboard-activity-row { min-height: 60px; }
        .dashboard-activity-code { display: inline-flex; align-items: center; border: 1px solid #eaecf0; border-radius: 6px; padding: 3px 7px; background: #f8f9fc; color: #667085; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: .72rem; }
        @media (max-width: 900px) {
            .flex-1.ml-60 > header { margin: 14px 16px 0; }
            .flex-1.ml-60 > main { padding: 20px 16px; }
        }
        @media (max-width: 767px) {
            .flex.min-h-screen { display: block; }
            aside.w-60 { position: sticky; top: 0; z-index: 20; width: 100%; height: auto; border-right: 0; border-bottom: 1px solid var(--color-border); }
            aside > div:first-child { height: 56px; }
            aside nav { display: flex; gap: 4px; overflow-x: auto; padding: 8px 12px; }
            aside nav > div { display: none; }
            aside nav .nav-link { flex: 0 0 auto; white-space: nowrap; }
            .nav-link.active::before { top: auto; right: 18%; bottom: -8px; left: 18%; width: auto; height: 3px; border-radius: 4px 4px 0 0; }
            .flex-1.ml-60 { margin-left: 0; }
            .flex-1.ml-60 > header { position: relative; top: auto; min-height: 68px; margin: 14px 12px 0; padding: 12px 14px; }
            .flex-1.ml-60 > main { padding: 16px 12px; }
            .dashboard-stat-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 480px) {
            .flex-1.ml-60 > header { align-items: flex-start; gap: 10px; padding: 12px; }
            .flex-1.ml-60 > header h1 { max-width: 42%; font-size: .95rem; }
            .flex-1.ml-60 > header > div { gap: 8px; }
            .flex-1.ml-60 > header > div > div:first-child p:first-child { font-size: .75rem; }
            .flex-1.ml-60 > main { padding: 14px 10px; }
            .dashboard-stat-grid { grid-template-columns: minmax(0, 1fr); }
            .dashboard-stat { padding: 18px; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-60 border-r border-gray-200 flex flex-col bg-white fixed h-screen">
            <div class="h-14 flex items-center px-6 border-b border-gray-200">
                @php
                    $logo = \App\Models\Setting::where('key', 'logo')->first();
                @endphp
                <div class="flex items-center space-x-3">
                    @if($logo && $logo->value)
                        <img src="{{ asset('storage/' . $logo->value) }}" alt="Logo" class="h-8 w-8 object-contain">
                    @endif
                    <span class="text-base font-semibold tracking-tight">E-Perpus</span>
                </div>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                @if(auth()->user()->role === 'user')
                <a href="{{ route('member.dashboard') }}" class="nav-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10.5 12 3l9 7.5M5.5 9v11h13V9M9 20v-6h6v6"></path></svg>
                    Beranda
                </a>
                <a href="{{ route('member.books') }}" class="nav-link {{ request()->routeIs('member.books') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5.5A2.5 2.5 0 016.5 3H20v16H6.5A2.5 2.5 0 014 16.5v-11zM4 16.5A2.5 2.5 0 016.5 14H20"></path></svg>
                    Katalog Buku
                </a>
                <a href="{{ route('member.bookings') }}" class="nav-link {{ request()->routeIs('member.bookings') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"></path></svg>
                    Booking Saya
                </a>
                @else
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>

                <div class="pt-5 pb-1 px-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Operasional</div>
                <a href="{{ route('loans.index') }}" class="nav-link {{ request()->routeIs('loans.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Peminjaman
                </a>
                <a href="{{ route('returns.index') }}" class="nav-link {{ request()->routeIs('returns.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Pengembalian
                </a>
                <a href="{{ route('fines.index') }}" class="nav-link {{ request()->routeIs('fines.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v1H8a1 1 0 00-1 1v4a1 1 0 001 1h8a1 1 0 001-1v-4a1 1 0 00-1-1h-1v-1c0-1.657-1.343-3-3-3zm0-2a2 2 0 00-2 2v1h4v-1a2 2 0 00-2-2z"></path></svg>
                    Denda
                </a>
                <a href="{{ route('members.index') }}" class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Anggota
                </a>
                <a href="{{ route('books.index') }}" class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Buku
                </a>
                <a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 4h12v17l-6-3-6 3V4zM9 8h6M9 12h6"></path></svg>
                    Booking Anggota
                </a>
                @if(auth()->user()->role === 'admin')
                <div class="pt-5 pb-1 px-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Master Data</div>
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    Kategori
                </a>
                <a href="{{ route('authors.index') }}" class="nav-link {{ request()->routeIs('authors.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Penulis
                </a>
                <a href="{{ route('publishers.index') }}" class="nav-link {{ request()->routeIs('publishers.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Penerbit
                </a>
                <a href="{{ route('shelves.index') }}" class="nav-link {{ request()->routeIs('shelves.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Rak
                </a>
                
                <div class="pt-5 pb-1 px-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Sistem</div>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Pengguna
                </a>
                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Pengaturan
                </a>
                @endif
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 ml-60 flex flex-col">
            <!-- Topbar -->
            <header class="h-14 border-b border-gray-200 flex items-center justify-between px-8 bg-white sticky top-0 z-10">
                <h1 class="text-sm font-medium text-gray-700">@yield('header_title', 'Dashboard')</h1>
                
                <div class="flex items-center space-x-3">
                    @if(auth()->user()->role !== 'user')
                    <div class="flex min-h-9 items-center gap-2 rounded-lg border border-emerald-100 bg-emerald-50 px-3 text-emerald-800" aria-label="Saldo kas">
                        <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7.5A2.5 2.5 0 015.5 5h13A2.5 2.5 0 0121 7.5v9a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 16.5v-9zM3 9h18m-5 5h2"></path>
                        </svg>
                        <div class="leading-tight">
                            <span class="block text-[10px] font-semibold uppercase tracking-wide text-emerald-700">Kas</span>
                            <span class="block whitespace-nowrap text-xs font-bold tabular-nums">Rp {{ number_format($cashBalance, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('histories.index') }}" aria-label="Riwayat" title="Riwayat" class="flex h-9 w-9 items-center justify-center rounded-md border border-gray-200 {{ request()->routeIs('histories.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </a>
                    @endif
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-xs font-medium text-gray-600 overflow-hidden">
                        @if(auth()->user()->foto)
                            <img src="{{ asset('storage/' . auth()->user()->foto) }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-gray-500 hover:text-gray-900 border border-gray-200 px-3 py-1.5 rounded-md hover:bg-gray-50 transition">Keluar</button>
                    </form>
                </div>
            </header>

            <!-- Flash Message -->
            @if(session('success'))
                <div class="mx-8 mt-6 px-4 py-3 border border-green-200 bg-green-50 text-green-700 text-sm rounded-md">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mx-8 mt-6 px-4 py-3 border border-red-200 bg-red-50 text-red-600 text-sm rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-1 p-8 bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        (function () {
            const options = {
                width: '100%',
                minimumResultsForSearch: 0,
                placeholder: 'Ketik untuk mencari',
            };

            function initializeSelects(root) {
                const selects = root.matches && root.matches('select')
                    ? $(root)
                    : $(root).find('select');

                selects.filter(function () {
                    return !$(this).hasClass('select2-hidden-accessible')
                        && !$(this).is('[data-select2="false"]');
                }).select2(options);
            }

            initializeSelects(document);

            const observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    mutation.addedNodes.forEach(function (node) {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            initializeSelects(node);
                        }
                    });
                });
            });

            observer.observe(document.body, { childList: true, subtree: true });
        })();
    </script>
    @yield('page_scripts')
</body>
</html>
