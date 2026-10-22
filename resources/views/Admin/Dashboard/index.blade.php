@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('header_title', 'Dashboard Admin')

@section('content')
<div class="space-y-8">
    <div class="dashboard-stat-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card dashboard-stat dashboard-stat--indigo p-6">
            <div class="dashboard-stat__top">
                <div class="dashboard-stat__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 016.5 3H20v16H6.5A2.5 2.5 0 014 16.5v-11zM4 16.5A2.5 2.5 0 016.5 14H20M8 7h8M8 10h5"></path></svg></div>
                <h3>{{ $totalBooks ?? 0 }}</h3>
            </div>
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Total Buku</p>
            <p class="text-xs text-gray-400 mt-1">Keseluruhan judul</p>
        </div>
        <div class="card dashboard-stat dashboard-stat--success p-6">
            <div class="dashboard-stat__top">
                <div class="dashboard-stat__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16 20v-1.5a3.5 3.5 0 00-3.5-3.5h-5A3.5 3.5 0 004 18.5V20m6-9a4 4 0 100-8 4 4 0 000 8zm7-7.5a4 4 0 010 7.75M20 20v-1.5a3.5 3.5 0 00-2.5-3.35"></path></svg></div>
                <h3>{{ $totalMembers ?? 0 }}</h3>
            </div>
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Total Anggota</p>
            <p class="text-xs text-gray-400 mt-1">Terdaftar aktif</p>
        </div>
        <div class="card dashboard-stat dashboard-stat--info p-6">
            <div class="dashboard-stat__top">
                <div class="dashboard-stat__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 016.5 3H20v16H6.5A2.5 2.5 0 014 16.5v-11zM4 16.5A2.5 2.5 0 016.5 14H20m-4-7l-3 3-2-2-2 2"></path></svg></div>
                <h3>{{ $activeLoans ?? 0 }}</h3>
            </div>
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Sedang Dipinjam</p>
            <p class="text-xs text-gray-400 mt-1">Transaksi aktif</p>
        </div>
        <div class="card dashboard-stat dashboard-stat--danger p-6">
            <div class="dashboard-stat__top">
                <div class="dashboard-stat__icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m5-14H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"></path></svg></div>
                <h3>{{ $unpaidFines ?? 0 }}</h3>
            </div>
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Denda Tunggakan</p>
            <p class="text-xs text-gray-400 mt-1">Belum dibayar</p>
        </div>
    </div>

    <div class="card">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-sm font-medium text-gray-900">Aktivitas Terbaru</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($recentActivities ?? [] as $activity)
            <div class="dashboard-activity-row px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition">
                <div class="flex items-center">
                    <span class="text-sm text-gray-700">{{ $activity->member->nama ?? 'N/A' }}</span>
                    <span class="mx-2 text-gray-300">|</span>
                    <span class="dashboard-activity-code">{{ $activity->kode_pinjam }}</span>
                </div>
                <span class="text-xs text-gray-400">{{ $activity->tanggal_pinjam->format('d M Y') }}</span>
            </div>
            @endforeach
            @if(empty($recentActivities) || count($recentActivities) == 0)
            <div class="px-6 py-8 text-center text-sm text-gray-400">
                Belum ada aktivitas terbaru.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
