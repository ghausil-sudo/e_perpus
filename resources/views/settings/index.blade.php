@extends('layouts.app')

@section('title', 'Pengaturan')
@section('header_title', 'Pengaturan')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="label">Nama Perpustakaan</label>
                <input type="text" name="nama_perpustakaan" value="{{ old('nama_perpustakaan', $settings['nama_perpustakaan'] ?? '') }}" required class="input">
            </div>
            <div>
                <label class="label">Alamat</label>
                <textarea name="alamat" rows="3" class="input">{{ old('alamat', $settings['alamat'] ?? '') }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $settings['telepon'] ?? '') }}" class="input">
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $settings['email'] ?? '') }}" class="input">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Denda Keterlambatan (per hari)</label>
                    <input type="number" name="denda_per_hari" value="{{ old('denda_per_hari', $settings['denda_per_hari'] ?? 0) }}" min="0" required class="input">
                </div>
                <div>
                    <label class="label">Lama Pinjam (hari)</label>
                    <input type="number" name="lama_pinjam" value="{{ old('lama_pinjam', $settings['lama_pinjam'] ?? 7) }}" min="1" required class="input">
                </div>
            </div>
            <div>
                <label class="label">Logo</label>
                <input type="file" name="logo" class="input">
                @if(!empty($settings['logo']))
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $settings['logo']) }}" class="h-12 object-contain border rounded">
                    </div>
                @endif
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
