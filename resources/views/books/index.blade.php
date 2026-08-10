@extends('layouts.app')

@section('title', 'Daftar Buku')
@section('header_title', 'Daftar Buku')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <form method="GET" class="flex items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau kode buku..." class="input max-w-md">
        </form>
        <a href="{{ route('books.create') }}" class="btn btn-primary">Tambah Buku</a>
    </div>

    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cover</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penulis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($books as $book)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover" class="h-10 w-10 object-cover rounded border border-gray-200">
                        @else
                            <div class="h-10 w-10 bg-gray-100 rounded border border-gray-200 flex items-center justify-center text-xs text-gray-400">-</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $book->kode_buku }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $book->judul }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $book->category->nama_kategori ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $book->author->nama_penulis ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $book->tahun_terbit }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $book->tersedia }} / {{ $book->jumlah }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('books.edit', $book) }}" class="text-gray-900 hover:text-gray-700">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data buku.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6">
        {{ $books->links() }}
    </div>
</div>
@endsection
