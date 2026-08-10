@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('header_title', 'Dashboard Admin')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card p-6">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Total Buku</p>
            <h3 class="text-3xl font-light text-gray-900 mt-3">{{ $totalBooks ?? 0 }}</h3>
            <p class="text-xs text-gray-400 mt-1">Keseluruhan judul</p>
        </div>
        <div class="card p-6">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Total Anggota</p>
            <h3 class="text-3xl font-light text-gray-900 mt-3">{{ $totalMembers ?? 0 }}</h3>
            <p class="text-xs text-gray-400 mt-1">Terdaftar aktif</p>
        </div>
        <div class="card p-6">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Sedang Dipinjam</p>
            <h3 class="text-3xl font-light text-gray-900 mt-3">{{ $activeLoans ?? 0 }}</h3>
            <p class="text-xs text-gray-400 mt-1">Transaksi aktif</p>
        </div>
        <div class="card p-6">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Denda Tunggakan</p>
            <h3 class="text-3xl font-light text-gray-900 mt-3">{{ $unpaidFines ?? 0 }}</h3>
            <p class="text-xs text-gray-400 mt-1">Belum dibayar</p>
        </div>
    </div>

    <div class="card">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-sm font-medium text-gray-900">Aktivitas Terbaru</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($recentActivities ?? [] as $activity)
            <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition">
                <div class="flex items-center">
                    <span class="text-sm text-gray-700">{{ $activity->member->nama ?? 'N/A' }}</span>
                    <span class="mx-2 text-gray-300">|</span>
                    <span class="text-xs text-gray-500">{{ $activity->kode_pinjam }}</span>
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
