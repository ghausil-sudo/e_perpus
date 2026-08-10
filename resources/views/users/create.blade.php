@extends('layouts.app')

@section('title', 'Tambah Pengguna')
@section('header_title', 'Tambah Pengguna')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input">
                    @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required class="input">
                    @error('username') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="input">
                @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Password</label>
                    <input type="password" name="password" required class="input">
                    @error('password') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required class="input">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Role</label>
                    <select name="role" required class="input">
                        <option value="petugas">Petugas</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="label">Foto</label>
                    <input type="file" name="foto" class="input">
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
