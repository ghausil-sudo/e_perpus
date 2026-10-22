@extends('layouts.app')

@section('title', 'Beranda Anggota')
@section('header_title', 'Beranda Anggota')

@section('content')
<div class="space-y-6">
    <div class="card flex flex-col gap-5 p-7 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600">Halo, {{ $member->nama }}</p>
            <h2 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Siap menemukan bacaan berikutnya?</h2>
            <p class="mt-2 text-sm text-gray-500">Kode anggota: {{ $member->kode_member }}. Ajukan booking hingga tiga buku berbeda dan tunggu persetujuan petugas.</p>
        </div>
        <a href="{{ route('member.books') }}" class="btn btn-primary shrink-0">Cari Buku</a>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div class="card p-6">
            <h3 class="text-base font-semibold text-gray-900">Booking Buku</h3>
            <p class="mt-2 text-sm leading-6 text-gray-600">Pilih hingga tiga buku berbeda dari katalog. Booking menunggu persetujuan petugas. Setelah disetujui, setiap buku ditahan selama 24 jam untuk diambil.</p>
            <a href="{{ route('member.books') }}" class="mt-4 inline-flex text-sm font-semibold text-blue-600 hover:text-blue-700">Buka katalog buku</a>
        </div>
        <div class="card p-6">
            <h3 class="text-base font-semibold text-gray-900">Booking Saya</h3>
            @if($activeBookings->isNotEmpty())
                <div class="mt-2 space-y-2">
                    @foreach($activeBookings as $activeBooking)
                        <div class="text-sm text-gray-600">
                            {{ $activeBooking->book->judul }} — <span class="font-medium capitalize">{{ $activeBooking->status }}</span>
                            @if($activeBooking->expires_at)
                                <p class="mt-1 text-xs text-gray-500">Batas pengambilan: {{ $activeBooking->expires_at->format('d M Y H:i') }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="mt-2 text-sm leading-6 text-gray-600">Belum ada booking aktif. Anda dapat mengajukan hingga tiga buku melalui katalog.</p>
            @endif
            <a href="{{ route('member.bookings') }}" class="mt-4 inline-flex text-sm font-semibold text-blue-600 hover:text-blue-700">Lihat riwayat booking</a>
        </div>
    </div>
</div>
@endsection
