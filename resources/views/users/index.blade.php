@extends('layouts.app')

@section('title', 'Daftar Pengguna')
@section('header_title', 'Pengguna')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <form method="GET" class="flex items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email/username..." class="input max-w-md">
        </form>
        <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah Pengguna</a>
    </div>

    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $user->username }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 capitalize">{{ $user->role }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('users.edit', $user) }}" class="text-gray-900 hover:text-gray-700 mr-3">Edit</a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" {{ $user->id == auth()->id() ? 'disabled' : '' }}>Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6">{{ $users->links() }}</div>
</div>
@endsection
