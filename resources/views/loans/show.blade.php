@extends('layouts.app')

@section('title', 'Detail Peminjaman')
@section('header_title', 'Detail Peminjaman')

@section('content')
<div class="space-y-6">
    <div class="card p-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500 uppercase">Kode Peminjaman</p>
                <p class="text-sm font-medium text-gray-900">{{ $loan->kode_pinjam }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Status</p>
                <p class="text-sm font-medium text-gray-900 capitalize">{{ $loan->status }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Anggota</p>
                <p class="text-sm font-medium text-gray-900">{{ $loan->member->nama ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Petugas</p>
                <p class="text-sm font-medium text-gray-900">{{ $loan->user->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Tanggal Pinjam</p>
                <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Batas Kembali</p>
                <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Tanggal Dikembalikan</p>
                <p class="text-sm font-medium text-gray-900">{{ $loan->tanggal_dikembalikan ? \Carbon\Carbon::parse($loan->tanggal_dikembalikan)->format('d M Y') : '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Catatan</p>
                <p class="text-sm font-medium text-gray-900">{{ $loan->catatan ?? '-' }}</p>
            </div>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-sm font-medium text-gray-900">Daftar Buku</h2>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($loan->loanDetails as $detail)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $detail->book->judul ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $detail->jumlah }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 capitalize">{{ $detail->kondisi }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada detail buku.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between">
        <a href="{{ route('loans.index') }}" class="btn btn-secondary">Kembali</a>
        @if(in_array($loan->status, ['dipinjam', 'terlambat']))
        <form action="{{ route('returns.store', $loan) }}" method="POST" onsubmit="return confirm('Proses pengembalian?')">
            @csrf
            <input type="hidden" name="tanggal_kembali" value="{{ date('Y-m-d') }}">
            <button type="submit" class="btn btn-primary">Proses Pengembalian</button>
        </form>
        @endif
    </div>
</div>
@endsection
