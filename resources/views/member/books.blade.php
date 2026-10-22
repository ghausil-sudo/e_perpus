@extends('layouts.app')

@section('title', 'Katalog Buku')
@section('header_title', 'Katalog Buku')

@section('content')
<div class="space-y-6">
    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-600">Booking maksimal tiga buku berbeda. Booking diproses setelah disetujui petugas.</p>
        <form method="GET" class="flex w-full gap-2 sm:max-w-md">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari judul, kode, atau penulis..." class="input">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
    </div>

    @if($activeBookingCount >= 3 || $hasActiveLoan)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
            @if($activeBookingCount >= 3)
                Anda sudah mencapai batas tiga booking aktif. Batalkan atau tunggu proses booking sebelum mengajukan booking lagi.
            @else
                Anda masih memiliki peminjaman aktif. Kembalikan buku terlebih dahulu sebelum mengajukan booking baru.
            @endif
        </div>
    @endif

    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($books as $book)
            <article class="card flex flex-col overflow-hidden">
                @if($book->cover)
                    <img src="{{ asset('storage/' . $book->cover) }}" alt="Sampul {{ $book->judul }}" class="h-52 w-full bg-gray-50 object-contain p-4">
                @else
                    <div class="flex h-52 items-center justify-center bg-gray-50 text-sm text-gray-400">Sampul tidak tersedia</div>
                @endif
                <div class="flex flex-1 flex-col p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">{{ $book->category->nama_kategori ?? 'Buku' }}</p>
                    <h2 class="mt-2 text-base font-semibold text-gray-900">{{ $book->judul }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $book->author->nama_penulis ?? 'Penulis tidak tersedia' }}</p>
                    <p class="mt-3 text-xs text-gray-500">Kode: {{ $book->kode_buku }} · Tersedia: {{ $book->tersedia }}</p>
                    <form action="{{ route('member.booking.store', $book) }}" method="POST" class="mt-auto pt-5" onsubmit="return confirm('Ajukan booking untuk buku ini?')">
                        @csrf
                        @php($alreadyBooked = in_array((string) $book->id, array_map('strval', $activeBookIds), true))
                        <button type="submit" class="btn {{ $book->tersedia > 0 && !$alreadyBooked && $activeBookingCount < 3 && !$hasActiveLoan ? 'btn-primary' : 'btn-secondary' }} w-full" {{ $book->tersedia < 1 || $alreadyBooked || $activeBookingCount >= 3 || $hasActiveLoan ? 'disabled' : '' }}>
                            {{ $book->tersedia < 1 ? 'Tidak Tersedia' : ($alreadyBooked ? 'Sudah Dibooking' : 'Booking Buku') }}
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="card p-8 text-center text-sm text-gray-500 sm:col-span-2 xl:col-span-3">Buku tidak ditemukan.</div>
        @endforelse
    </div>
    <div>{{ $books->links() }}</div>
</div>
@endsection
