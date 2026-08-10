@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-semibold tracking-tight">E-Perpus</h1>
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
            <label for="login" class="block text-xs font-medium text-gray-600 mb-1">Username atau Email</label>
            <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus
                class="w-full px-3 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition">
        </div>

        <div>
            <label for="password" class="block text-xs font-medium text-gray-600 mb-1">Password</label>
            <input type="password" id="password" name="password" required
                class="w-full px-3 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center text-xs text-gray-600 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 mr-2">
                Ingat saya
            </label>
        </div>

        <button type="submit"
            class="w-full bg-gray-900 text-white py-2.5 rounded-md text-sm font-medium hover:bg-gray-800 transition focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
            Masuk
        </button>
    </form>
@endsection
