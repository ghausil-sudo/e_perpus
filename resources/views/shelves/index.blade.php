@extends('layouts.app')

@section('title', 'Daftar Rak')
@section('header_title', 'Rak / Etalase')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <form method="GET" class="flex items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari rak..." class="input max-w-md">
        </form>
        <a href="{{ route('shelves.create') }}" class="btn btn-primary">Tambah Rak</a>
    </div>

    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($shelves as $shelf)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $shelf->kode_rak }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $shelf->nama_rak }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $shelf->lokasi ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 capitalize">{{ $shelf->tipe }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('shelves.edit', $shelf) }}" class="text-gray-900 hover:text-gray-700 mr-3">Edit</a>
                        <form action="{{ route('shelves.destroy', $shelf) }}" method="POST" class="inline" onsubmit="return confirm('Hapus rak ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6">{{ $shelves->links() }}</div>
</div>
@endsection
