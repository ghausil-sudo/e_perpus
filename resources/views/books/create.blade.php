@extends('layouts.app')

@section('title', 'Tambah Buku')
@section('header_title', 'Tambah Buku')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
                    <p class="font-semibold">Buku belum dapat disimpan. Periksa kembali data berikut:</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Kode Buku</label>
                    <input type="text" name="kode_buku" value="{{ old('kode_buku') }}" required class="input">
                    @error('kode_buku') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}" class="input">
                    @error('isbn') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="label">Judul</label>
                <input type="text" name="judul" value="{{ old('judul') }}" required class="input">
                @error('judul') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="label">Kategori</label>
                    <select name="category_id" required class="input">
                        <option value="">-- Pilih --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Penulis</label>
                    <select name="author_id" required class="input">
                        <option value="">-- Pilih --</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>{{ $author->nama_penulis }}</option>
                        @endforeach
                    </select>
                    @error('author_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Penerbit</label>
                    <select name="publisher_id" required class="input">
                        <option value="">-- Pilih --</option>
                        @foreach($publishers as $publisher)
                            <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>{{ $publisher->nama_penerbit }}</option>
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
                            <option value="{{ $shelf->id }}" {{ old('shelf_id') == $shelf->id ? 'selected' : '' }}>{{ $shelf->nama_rak }} ({{ $shelf->lokasi }})</option>
                        @endforeach
                    </select>
                    @error('shelf_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit') }}" required min="1000" max="2100" class="input">
                    @error('tahun_terbit') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Jumlah</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" required min="1" class="input">
                    @error('jumlah') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Cover</label>
                    <input type="file" name="cover" class="input">
                    @error('cover') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="label">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="input">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2">
                <a href="{{ route('books.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[enctype="multipart/form-data"]');
    if (!form) {
        return;
    }

    form.addEventListener('invalid', function () {
        let notice = form.querySelector('[data-client-validation-notice]');
        if (!notice) {
            notice = document.createElement('p');
            notice.dataset.clientValidationNotice = '';
            notice.className = 'rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800';
            notice.setAttribute('role', 'alert');
            notice.textContent = 'Mohon lengkapi kolom wajib dan periksa kembali nilai yang dimasukkan.';
            form.prepend(notice);
        }
    }, true);
});
</script>
@endsection
