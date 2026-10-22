@extends('layouts.app')

@section('title', 'Booking Anggota')
@section('header_title', 'Booking Anggota')

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
            <h2 class="text-base font-semibold text-gray-900">Verifikasi Akun Anggota</h2>
            <p class="mt-1 text-xs text-gray-500">Periksa data pendaftar. Satu klik akan membuat kode anggota dan menghubungkannya ke akun.</p>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($pendingAccounts as $account)
                <div class="flex flex-col gap-4 px-5 py-4 md:flex-row md:items-center md:justify-between">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900">{{ $account->name }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ $account->email }} · {{ $account->member_telepon ?? 'Telepon belum tersedia' }}</p>
                        <p class="mt-1 text-xs text-gray-500">
                            {{ $account->member_jenis_kelamin === 'L' ? 'Laki-laki' : ($account->member_jenis_kelamin === 'P' ? 'Perempuan' : 'Jenis kelamin belum tersedia') }}
                            · {{ $account->member_alamat ?? 'Alamat belum tersedia' }}
                        </p>
                        @if($account->member_nis_nip)
                            <p class="mt-1 text-xs text-gray-500">NIS / NIP: {{ $account->member_nis_nip }}</p>
                        @endif
                    </div>
                    <form action="{{ route('users.member-link', $account) }}" method="POST" class="w-full md:w-auto" onsubmit="return confirm('Verifikasi data dan buat atau hubungkan data anggota untuk akun ini?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-action btn-action--primary whitespace-nowrap">Verifikasi & Buat Anggota</button>
                    </form>
                </div>
            @empty
                <p class="px-5 py-6 text-center text-sm text-gray-500">Tidak ada akun yang menunggu verifikasi.</p>
            @endforelse
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500">Anggota</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500">Buku</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500">Diajukan</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500">Status / Tenggat</th>
                        <th class="px-5 py-3 text-right text-xs font-medium uppercase text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="px-5 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $booking->member->nama ?? 'Anggota tidak tersedia' }}</div>
                                <div class="mt-1 text-xs text-gray-500">{{ $booking->member->kode_member ?? '' }}</div>
                            </td>
                            <td class="px-5 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $booking->book->judul ?? 'Buku tidak tersedia' }}</div>
                                <div class="mt-1 text-xs text-gray-500">{{ $booking->book->kode_buku ?? '' }}</div>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">{{ $booking->created_at->format('d M Y H:i') }}</td>
                            <td class="px-5 py-4 text-sm">
                                <span class="font-medium capitalize text-gray-800">{{ $booking->status }}</span>
                                @if($booking->expires_at)
                                    <div class="mt-1 text-xs text-gray-500">Sampai {{ $booking->expires_at->format('d M Y H:i') }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    @if($booking->status === 'menunggu')
                                        <form action="{{ route('bookings.approve', $booking) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button class="btn-action btn-action--primary" type="submit">Setujui</button>
                                        </form>
                                        <form action="{{ route('bookings.reject', $booking) }}" method="POST" onsubmit="return confirm('Tolak booking ini?')">
                                            @csrf @method('PATCH')
                                            <button class="btn-action btn-action--danger" type="submit">Tolak</button>
                                        </form>
                                    @elseif($booking->status === 'disetujui')
                                        <a href="{{ route('loans.create', ['booking_id' => $booking->id]) }}" class="btn-action btn-action--primary whitespace-nowrap">Ambil & Buat Peminjaman</a>
                                    @else
                                        <span class="text-xs text-gray-400">{{ $booking->approver->name ?? '' }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500">Belum ada booking anggota.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4">{{ $bookings->links() }}</div>
    </div>
</div>
@endsection
