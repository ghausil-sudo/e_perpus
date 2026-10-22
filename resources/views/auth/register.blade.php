@extends('layouts.auth')

@section('title', 'Daftar Akun Anggota')

@section('content')
    <div class="login-brand mb-8 flex flex-col items-center text-center">
        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-600 text-xl font-semibold text-white shadow-sm">E</div>
        <h1 class="text-2xl font-semibold tracking-tight">Daftar Akun Anggota</h1>
        <p class="mt-2 text-sm text-gray-500">Isi data diri untuk membuat akun dan mengajukan booking buku dari rumah.</p>
    </div>

    @if($errors->any())
        <div class="login-error mb-6 px-4 py-3" role="alert">
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST" class="login-form space-y-5">
        @csrf
        <div>
            <label for="name" class="label">Nama Lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required class="input" autocomplete="name">
        </div>
        <div>
            <label for="username" class="label">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username') }}" required class="input" autocomplete="username">
        </div>
        <div>
            <label for="nis_nip" class="label">NIS / NIP <span class="font-normal text-gray-400">(opsional)</span></label>
            <input id="nis_nip" name="nis_nip" type="text" value="{{ old('nis_nip') }}" class="input">
        </div>
        <div>
            <label for="jenis_kelamin" class="label">Jenis Kelamin</label>
            <select id="jenis_kelamin" name="jenis_kelamin" required class="input">
                <option value="">-- Pilih --</option>
                <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
        <div>
            <label for="alamat" class="label">Alamat</label>
            <textarea id="alamat" name="alamat" rows="3" required class="input">{{ old('alamat') }}</textarea>
        </div>
        <div>
            <label for="telepon" class="label">Nomor Telepon</label>
            <input id="telepon" name="telepon" type="tel" value="{{ old('telepon') }}" required class="input" autocomplete="tel">
        </div>
        <div>
            <label for="email" class="label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="input" autocomplete="email">
        </div>
        <div>
            <label for="password" class="label">Password (minimal 8 karakter)</label>
            <input id="password" name="password" type="password" required minlength="8" class="input" autocomplete="new-password">
        </div>
        <div>
            <label for="password_confirmation" class="label">Konfirmasi Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" class="input" autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn-primary w-full">Daftar</button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">Login</a>
    </p>
@endsection
