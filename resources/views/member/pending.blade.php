@extends('layouts.app')

@section('title', 'Akun Anggota')
@section('header_title', 'Akun Anggota')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="card p-8 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4m0 4h.01M10.3 3.86l-8 14A2 2 0 004 21h16a2 2 0 001.73-3.14l-8-14a2 2 0 00-3.46 0z"></path>
            </svg>
        </div>
        <h2 class="mt-5 text-xl font-semibold text-gray-900">Menunggu verifikasi petugas</h2>
        <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-gray-600">
            Akun Anda sudah dibuat. Petugas akan memeriksa data pendaftaran, membuat kode anggota otomatis, dan menghubungkan akun Anda sebelum booking dapat dilakukan.
        </p>
        <p class="mt-5 text-xs text-gray-500">Silakan hubungi petugas perpustakaan jika verifikasi belum dilakukan.</p>
    </div>
</div>
@endsection
