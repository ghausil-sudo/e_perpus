@extends('layouts.app')

@section('title', 'Edit Pengguna')
@section('header_title', 'Edit Pengguna')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="input">
                    @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="input">
                    @error('username') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="input">
                @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Password Baru (kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="input">
                    @error('password') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="input">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Role</label>
                    <select name="role" required class="input">
                        <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        @if($user->role === 'user')
                            <option value="user" selected>Anggota</option>
                        @endif
                    </select>
                </div>
                @if($user->role === 'user')
                <div>
                    <label class="label">Hubungkan ke Data Anggota</label>
                    <select name="member_id" class="input">
                        <option value="">Menunggu verifikasi petugas</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ (string) old('member_id', $user->member_id) === (string) $member->id ? 'selected' : '' }}>
                                {{ $member->kode_member }} - {{ $member->nama }} ({{ $member->email }})
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Hanya anggota dengan kode dan email yang cocok dengan data pendaftaran yang dapat dipilih. Kode yang didaftarkan: {{ $user->member_code ?? $user->member->kode_member }}</p>
                    @error('member_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endif
                <div>
                    <label class="label">Foto</label>
                    <input type="file" name="foto" class="input">
                    @if($user->foto)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $user->foto) }}" class="h-12 object-contain border rounded">
                        </div>
                    @endif
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
