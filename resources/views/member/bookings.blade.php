@extends('layouts.app')

@section('title', 'Booking Saya')
@section('header_title', 'Booking Saya')

@section('content')
<div class="space-y-5">
    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif
    <div class="card overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="text-base font-semibold text-gray-900">Keranjang Booking Disetujui</h2>
            <p class="mt-1 text-xs text-gray-500">Buku-buku yang sudah disetujui petugas digabungkan dalam satu keranjang.</p>
        </div>
        @if($approvedBookings->isNotEmpty())
            <div class="divide-y divide-gray-100">
                @foreach($approvedBookings as $booking)
                    <div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $booking->book->judul ?? 'Buku tidak tersedia' }}</p>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $booking->book->kode_buku ?? '' }}
                                · Batas pengambilan: {{ $booking->expires_at?->format('d M Y H:i') ?? '-' }}
                            </p>
                        </div>
                        <form action="{{ route('member.booking.cancel', $booking) }}" method="POST" onsubmit="return confirm('Batalkan booking buku ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action btn-action--danger">Batalkan Buku</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="px-5 py-6 text-center text-sm text-gray-500">Belum ada booking yang disetujui.</p>
        @endif
    </div>

    <div class="card overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="text-base font-semibold text-gray-900">Riwayat Booking</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500">Buku</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500">Tanggal Booking</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500">Batas Pengambilan</th>
                        <th class="px-5 py-3 text-right text-xs font-medium uppercase text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="px-5 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $booking->book->judul ?? 'Buku tidak tersedia' }}</div>
                                <div class="mt-1 text-xs text-gray-500">{{ $booking->book->kode_buku ?? '' }}</div>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">{{ $booking->created_at->format('d M Y H:i') }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold
                                    {{ $booking->status === 'disetujui' ? 'bg-green-50 text-green-700' : '' }}
                                    {{ $booking->status === 'menunggu' ? 'bg-amber-50 text-amber-700' : '' }}
                                    {{ in_array($booking->status, ['ditolak', 'kedaluwarsa', 'dibatalkan']) ? 'bg-gray-100 text-gray-600' : '' }}
                                    {{ $booking->status === 'diambil' ? 'bg-blue-50 text-blue-700' : '' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">{{ $booking->expires_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm">
                                @if(in_array($booking->status, ['menunggu', 'disetujui'], true))
                                    <form action="{{ route('member.booking.cancel', $booking) }}" method="POST" onsubmit="return confirm('Batalkan booking ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-action--danger">Batalkan</button>
                                    </form>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500">Belum ada riwayat booking.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4">{{ $bookings->links() }}</div>
    </div>
</div>
@endsection
