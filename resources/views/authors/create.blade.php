@extends('layouts.app')

@section('title', 'Tambah Penulis')
@section('header_title', 'Tambah Penulis')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form action="{{ route('authors.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="label">Kode Penulis</label>
                <input type="text" name="kode_penulis" value="{{ old('kode_penulis') }}" required class="input">
                @error('kode_penulis') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label">Nama Penulis</label>
                <input type="text" name="nama_penulis" value="{{ old('nama_penulis') }}" required class="input">
                @error('nama_penulis') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label">Biografi</label>
                <textarea name="biografi" rows="4" class="input">{{ old('biografi') }}</textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <a href="{{ route('authors.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
