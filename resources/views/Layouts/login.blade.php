@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-semibold tracking-tight text-gray-900">E-Perpus</h1>
        <p class="mt-2 text-sm text-gray-500">Sistem Manajemen Perpustakaan Digital</p>
    </div>

    @if (session('error'))
        <div class="mb-6 px-4 py-3 border border-red-200 bg-red-50 text-red-600 text-sm rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label for="login" class="label">Username atau Email</label>
            <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus
                class="input">
        </div>

        <div>
            <label for="password" class="label">Password</label>
            <input type="password" id="password" name="password" required
                class="input">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 mr-2">
                Ingat saya
            </label>
        </div>

        <button type="submit"
            class="btn btn-primary w-full">
            Masuk
        </button>
    </form>
@endsection
