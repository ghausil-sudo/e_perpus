@extends('layouts.app')

@section('title', 'Edit Rak')
@section('header_title', 'Edit Rak')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form action="{{ route('shelves.update', $shelf) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="label">Kode Rak</label>
                <input type="text" name="kode_rak" value="{{ old('kode_rak', $shelf->kode_rak) }}" required class="input">
                @error('kode_rak') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label">Nama Rak</label>
                <input type="text" name="nama_rak" value="{{ old('nama_rak', $shelf->nama_rak) }}" required class="input">
                @error('nama_rak') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="label">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $shelf->lokasi) }}" class="input">
                </div>
                <div>
                    <label class="label">Tipe</label>
                    <select name="tipe" required class="input">
                        <option value="rak" {{ old('tipe', $shelf->tipe) == 'rak' ? 'selected' : '' }}>Rak</option>
                        <option value="etalase" {{ old('tipe', $shelf->tipe) == 'etalase' ? 'selected' : '' }}>Etalase</option>
                    </select>
                </div>
                <div>
                    <label class="label">Kapasitas</label>
                    <input type="number" name="kapasitas" value="{{ old('kapasitas', $shelf->kapasitas) }}" min="0" class="input">
                </div>
            </div>
            <div>
                <label class="label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="input">{{ old('deskripsi', $shelf->deskripsi) }}</textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <a href="{{ route('shelves.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
