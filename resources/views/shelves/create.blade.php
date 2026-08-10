@extends('layouts.app')

@section('title', 'Tambah Rak')
@section('header_title', 'Tambah Rak')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form action="{{ route('shelves.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="label">Kode Rak</label>
                <input type="text" name="kode_rak" value="{{ old('kode_rak') }}" required class="input">
                @error('kode_rak') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label">Nama Rak</label>
                <input type="text" name="nama_rak" value="{{ old('nama_rak') }}" required class="input">
                @error('nama_rak') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="label">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="input">
                </div>
                <div>
                    <label class="label">Tipe</label>
                    <select name="tipe" required class="input">
                        <option value="rak">Rak</option>
                        <option value="etalase">Etalase</option>
                    </select>
                </div>
                <div>
                    <label class="label">Kapasitas</label>
                    <input type="number" name="kapasitas" value="{{ old('kapasitas', 0) }}" min="0" class="input">
                </div>
            </div>
            <div>
                <label class="label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="input">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <a href="{{ route('shelves.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
