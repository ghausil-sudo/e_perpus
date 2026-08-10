@extends('layouts.app')

@section('title', 'Tambah Penerbit')
@section('header_title', 'Tambah Penerbit')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form action="{{ route('publishers.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="label">Kode Penerbit</label>
                <input type="text" name="kode_penerbit" value="{{ old('kode_penerbit') }}" required class="input">
                @error('kode_penerbit') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label">Nama Penerbit</label>
                <input type="text" name="nama_penerbit" value="{{ old('nama_penerbit') }}" required class="input">
                @error('nama_penerbit') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="label">Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat') }}" class="input">
                </div>
                <div>
                    <label class="label">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon') }}" class="input">
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input">
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <a href="{{ route('publishers.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
