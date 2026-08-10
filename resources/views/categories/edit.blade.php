@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('header_title', 'Edit Kategori')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="label">Kode Kategori</label>
                <input type="text" name="kode_kategori" value="{{ old('kode_kategori', $category->kode_kategori) }}" required class="input">
                @error('kode_kategori') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label">Nama Kategori</label>
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $category->nama_kategori) }}" required class="input">
                @error('nama_kategori') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="input">{{ old('deskripsi', $category->deskripsi) }}</textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
