@extends('layouts.app')

@section('title', 'Riwayat')
@section('header_title', 'Riwayat')

@section('content')
<div class="space-y-6">
    <div class="card p-6">
        <h2 class="text-base font-semibold text-gray-900">Catat Kas Keluar</h2>
        <p class="mt-1 text-sm text-gray-500">Kas masuk dicatat otomatis saat pembayaran denda dilunasi.</p>
        <form action="{{ route('histories.expenses.store') }}" method="POST" class="mt-4 grid gap-4 md:grid-cols-[1fr_220px_auto] md:items-end">
            @csrf
            <div>
                <label for="keterangan" class="label">Keterangan pengeluaran</label>
                <input id="keterangan" type="text" name="keterangan" value="{{ old('keterangan') }}" maxlength="1000" required class="input" placeholder="Contoh: pembelian alat tulis">
                @error('keterangan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="jumlah" class="label">Jumlah (Rp)</label>
                <input id="jumlah" type="number" name="jumlah" value="{{ old('jumlah') }}" min="1" step="0.01" required class="input">
                @error('jumlah')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan Pengeluaran</button>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-base font-semibold text-gray-900">Riwayat Aktivitas</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Aktivitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Keterangan / Perubahan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">Kas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Pengguna</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($histories as $history)
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">{{ $history->created_at->format('d M Y H:i') }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                            @if($history->jenis === 'kas_masuk')
                                <span class="text-green-700">Kas Masuk</span>
                            @elseif($history->jenis === 'kas_keluar')
                                <span class="text-red-700">Kas Keluar</span>
                            @elseif($history->jenis === 'peminjaman')
                                <span class="text-indigo-700">Peminjaman</span>
                            @elseif($history->jenis === 'pengembalian')
                                <span class="text-teal-700">Pengembalian</span>
                            @elseif($history->jenis === 'aktivitas')
                                <span class="text-gray-700">Aktivitas</span>
                            @else
                                <span class="text-blue-700">Perubahan Pengaturan</span>
                            @endif
                            <div class="mt-1 text-xs font-normal text-gray-500">{{ $history->judul }}</div>
                        </td>
                        <td class="min-w-[320px] px-6 py-4 text-sm text-gray-700">
                            @if($history->jenis === 'perubahan_tarif_denda')
                                <span class="font-medium">{{ $history->keterangan }}:</span>
                                <span class="whitespace-nowrap">
                                    {{ $history->nilai_lama === null ? 'Belum diatur' : 'Rp ' . number_format((float) $history->nilai_lama, 0, ',', '.') }}
                                    &rarr;
                                    {{ 'Rp ' . number_format((float) $history->nilai_baru, 0, ',', '.') }}
                                    per hari
                                </span>
                            @else
                                {{ $history->keterangan }}
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                            @if($history->jenis === 'kas_masuk')
                                <span class="text-green-700">+ Rp {{ number_format((float) $history->jumlah, 0, ',', '.') }}</span>
                            @elseif($history->jenis === 'kas_keluar')
                                <span class="text-red-700">- Rp {{ number_format((float) $history->jumlah, 0, ',', '.') }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">{{ $history->user->name ?? 'Pengguna dihapus' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada riwayat aktivitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $histories->links() }}
        </div>
    </div>
</div>
@endsection
