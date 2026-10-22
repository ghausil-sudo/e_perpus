@extends('layouts.app')

@section('title', 'Daftar Penulis')
@section('header_title', 'Penulis')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <form method="GET" class="flex items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari penulis..." class="input max-w-md">
        </form>
        <a href="{{ route('authors.create') }}" class="btn btn-primary">Tambah Penulis</a>
    </div>

    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($authors as $author)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $author->kode_penulis }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $author->nama_penulis }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('authors.edit', $author) }}" class="btn-action btn-action--primary">Edit</a>
                            <form action="{{ route('authors.destroy', $author) }}" method="POST" class="inline" onsubmit="return confirm('Hapus penulis ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action btn-action--danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6">{{ $authors->links() }}</div>
</div>
@endsection
