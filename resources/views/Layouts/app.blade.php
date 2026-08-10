<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - E-Perpus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .nav-link { display: flex; align-items: center; padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #6b7280; border-radius: 0.375rem; transition: all 0.15s ease; text-decoration: none; }
        .nav-link:hover { background-color: #f9fafb; color: #111827; }
        .nav-link.active { background-color: #111827; color: #ffffff; }
        .nav-link.active:hover { background-color: #111827; color: #ffffff; }
        .card { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 0.5rem; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; transition: all 0.15s ease; cursor: pointer; border: none; text-decoration: none; }
        .btn-primary { background-color: #111827; color: #ffffff; }
        .btn-primary:hover { background-color: #374151; }
        .btn-secondary { background-color: #ffffff; color: #111827; border: 1px solid #d1d5db; }
        .btn-secondary:hover { background-color: #f9fafb; }
        .input { width: 100%; padding: 0.5rem 0.75rem; font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 0.375rem; transition: border-color 0.15s ease; }
        .input:focus { outline: none; border-color: #111827; }
        .label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem; }
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
                <a href="{{ route('members.index') }}" class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Anggota
                </a>
                <a href="{{ route('books.index') }}" class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Buku
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
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 ml-60 flex flex-col">
            <!-- Topbar -->
            <header class="h-14 border-b border-gray-200 flex items-center justify-between px-8 bg-white sticky top-0 z-10">
                <h1 class="text-sm font-medium text-gray-700">@yield('header_title', 'Dashboard')</h1>
                
                <div class="flex items-center space-x-3">
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
</body>
</html>
