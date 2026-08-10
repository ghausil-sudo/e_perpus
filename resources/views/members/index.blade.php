@extends('layouts.app')

@section('title', 'Daftar Anggota')
@section('header_title', 'Daftar Anggota')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <form method="GET" class="flex items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode anggota..." class="input max-w-md">
        </form>
        <a href="{{ route('members.create') }}" class="btn btn-primary">Tambah Anggota</a>
    </div>

    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Kelamin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($members as $member)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $member->kode_member }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $member->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $member->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $member->telepon }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $member->status == 'aktif' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                            {{ ucfirst($member->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('members.edit', $member) }}" class="text-gray-900 hover:text-gray-700">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data anggota.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6">
        {{ $members->links() }}
    </div>
</div>
@endsection
