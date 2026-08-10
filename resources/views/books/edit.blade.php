@extends('layouts.app')

@section('title', 'Edit Buku')
@section('header_title', 'Edit Buku')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Kode Buku</label>
                    <input type="text" name="kode_buku" value="{{ old('kode_buku', $book->kode_buku) }}" required class="input">
                    @error('kode_buku') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" class="input">
                </div>
            </div>

            <div>
                <label class="label">Judul</label>
                <input type="text" name="judul" value="{{ old('judul', $book->judul) }}" required class="input">
                @error('judul') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="label">Kategori</label>
                    <select name="category_id" required class="input">
                        <option value="">-- Pilih --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Penulis</label>
                    <select name="author_id" required class="input">
                        <option value="">-- Pilih --</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>{{ $author->nama_penulis }}</option>
                        @endforeach
                    </select>
                    @error('author_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Penerbit</label>
                    <select name="publisher_id" required class="input">
                        <option value="">-- Pilih --</option>
                        @foreach($publishers as $publisher)
                            <option value="{{ $publisher->id }}" {{ old('publisher_id', $book->publisher_id) == $publisher->id ? 'selected' : '' }}>{{ $publisher->nama_penerbit }}</option>
                        @endforeach
                    </select>
                    @error('publisher_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Rak / Etalase</label>
                    <select name="shelf_id" class="input">
                        <option value="">-- Pilih --</option>
                        @foreach($shelves as $shelf)
                            <option value="{{ $shelf->id }}" {{ old('shelf_id', $book->shelf_id) == $shelf->id ? 'selected' : '' }}>{{ $shelf->nama_rak }} ({{ $shelf->lokasi }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Jumlah (Total Stok)</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', $book->jumlah) }}" required min="1" class="input">
                    @error('jumlah') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="label">Cover</label>
                <input type="file" name="cover" class="input">
                @if($book->cover)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $book->cover) }}" class="h-16 object-contain border rounded">
                    </div>
                @endif
            </div>

            <div>
                <label class="label">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="input">{{ old('deskripsi', $book->deskripsi) }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2">
                <a href="{{ route('books.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
