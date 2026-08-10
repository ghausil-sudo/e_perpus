@extends('layouts.app')

@section('title', 'Edit Anggota')
@section('header_title', 'Edit Anggota')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form action="{{ route('members.update', $member) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $member->nama) }}" required class="input">
                    @error('nama') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">NIS / NIP</label>
                    <input type="text" name="nis_nip" value="{{ old('nis_nip', $member->nis_nip) }}" class="input">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="input">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin', $member->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $member->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $member->telepon) }}" required class="input">
                    @error('telepon') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="label">Alamat</label>
                <textarea name="alamat" rows="3" required class="input">{{ old('alamat', $member->alamat) }}</textarea>
                @error('alamat') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">Email</label>
                <input type="email" name="email" value="{{ old('email', $member->email) }}" class="input">
                @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2">
                <a href="{{ route('members.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
