@extends('layouts.app')

@section('title', 'Daftar Denda')
@section('header_title', 'Daftar Denda')

@section('content')
<div class="space-y-6">
    <form method="GET" class="flex items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode denda atau nama anggota..." class="input max-w-md">
    </form>

    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Denda</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anggota</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($fines as $fine)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $fine->kode_denda }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $fine->member->nama ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp {{ number_format($fine->jumlah, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $fine->status == 'lunas' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                            {{ $fine->status == 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $fine->keterangan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        @if($fine->status != 'lunas')
                            <form action="{{ route('fines.update', $fine) }}" method="POST" class="inline" onsubmit="return confirm('Tandai denda ini sebagai lunas?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-gray-900 hover:text-gray-700 font-medium">Bayar</button>
                            </form>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data denda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6">
        {{ $fines->links() }}
    </div>
</div>
@endsection
