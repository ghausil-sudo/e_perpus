@extends('layouts.app')

@section('title', 'Dashboard Petugas')
@section('header_title', 'Dashboard Petugas')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('loans.create') }}" class="card p-6 hover:bg-gray-50 transition flex items-center justify-between group">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Transaksi</p>
                <p class="text-sm font-medium text-gray-900 mt-1">Peminjaman</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-900 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
        <a href="{{ route('returns.index') }}" class="card p-6 hover:bg-gray-50 transition flex items-center justify-between group">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Transaksi</p>
                <p class="text-sm font-medium text-gray-900 mt-1">Pengembalian</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-900 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
        <a href="{{ route('members.create') }}" class="card p-6 hover:bg-gray-50 transition flex items-center justify-between group">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Daftar</p>
                <p class="text-sm font-medium text-gray-900 mt-1">Anggota Baru</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-900 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card p-6">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Buku Dipinjam</p>
            <h3 class="text-3xl font-light text-gray-900 mt-3">{{ $activeLoans ?? 0 }}</h3>
        </div>
        <div class="card p-6">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Pengembalian Hari Ini</p>
            <h3 class="text-3xl font-light text-gray-900 mt-3">{{ $returnsToday ?? 0 }}</h3>
        </div>
        <div class="card p-6">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Stok Tersedia</p>
            <h3 class="text-3xl font-light text-gray-900 mt-3">{{ $availableBooks ?? 0 }}</h3>
        </div>
        <div class="card p-6">
            <p class="text-xs text-red-500 uppercase tracking-wider font-medium">Denda Belum Lunas</p>
            <h3 class="text-3xl font-light text-gray-900 mt-3">{{ $unpaidFines ?? 0 }}</h3>
        </div>
    </div>

    <div class="card p-6">
        <h2 class="text-sm font-medium text-gray-900 mb-4">Cari Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" placeholder="Cari Kode/Nama Anggota..." class="input">
            <input type="text" placeholder="Cari Kode/Judul Buku..." class="input">
        </div>
    </div>
</div>
@endsection
