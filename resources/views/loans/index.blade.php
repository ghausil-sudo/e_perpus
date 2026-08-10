@extends('layouts.app')

@section('title', 'Peminjaman')
@section('header_title', 'Peminjaman')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <form method="GET" class="flex items-center">
            <select name="status" onchange="this.form.submit()" class="input max-w-xs">
                <option value="dipinjam" {{ ($status ?? 'dipinjam') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan" {{ ($status ?? 'dipinjam') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                <option value="terlambat" {{ ($status ?? 'dipinjam') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
            </select>
        </form>
        <a href="{{ route('loans.create') }}" class="btn btn-primary">Tambah Peminjaman</a>
    </div>

    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anggota</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Petugas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Batas Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($loans as $loan)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loan->kode_pinjam }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $loan->member->nama ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $loan->user->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $loan->status == 'dipinjam' ? 'bg-blue-50 text-blue-700' : '' }}
                            {{ $loan->status == 'dikembalikan' ? 'bg-green-50 text-green-700' : '' }}
                            {{ $loan->status == 'terlambat' ? 'bg-red-50 text-red-700' : '' }}">
                            {{ ucfirst($loan->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6">
        {{ $loans->links() }}
    </div>
</div>
@endsection
