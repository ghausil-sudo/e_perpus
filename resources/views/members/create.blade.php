@extends('layouts.app')

@section('title', 'Tambah Anggota')
@section('header_title', 'Tambah Anggota')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form action="{{ route('members.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required class="input">
                    @error('nama') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">NIS / NIP</label>
                    <input type="text" name="nis_nip" value="{{ old('nis_nip') }}" class="input">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="input">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon') }}" required class="input">
                    @error('telepon') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="label">Alamat</label>
                <textarea name="alamat" rows="3" required class="input">{{ old('alamat') }}</textarea>
                @error('alamat') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input">
                @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2">
                <a href="{{ route('members.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
