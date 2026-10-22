@extends('layouts.app')

@section('title', 'Pengembalian')
@section('header_title', 'Pengembalian')

@section('content')
<div class="space-y-6">
    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="GET" class="flex items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode pinjam atau nama anggota..." class="input max-w-md">
    </form>

    <div class="card min-w-0 overflow-hidden">
        <div class="w-full overflow-x-auto">
        <table class="w-full table-fixed divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="w-[12%] px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Pinjam</th>
                    <th class="w-[13%] px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anggota</th>
                    <th class="w-[10%] px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam</th>
                    <th class="w-[11%] px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase">Batas Kembali</th>
                    <th class="w-[31%] px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buku dan Kondisi Akhir</th>
                    <th class="w-[10%] px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="w-[13%] px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($loans as $loan)
                <tr class="hover:bg-gray-50">
                    <td class="break-words px-3 py-4 text-sm font-medium text-gray-900">{{ $loan->kode_pinjam }}</td>
                    <td class="break-words px-3 py-4 text-sm text-gray-700">{{ $loan->member->nama ?? 'N/A' }}</td>
                    <td class="break-words px-2 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}</td>
                    <td class="break-words px-2 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y') }}</td>
                    <td class="px-3 py-4 text-sm text-gray-700">
                        <div class="space-y-3">
                            @foreach($loan->loanDetails as $detail)
                                <div>
                                    <label for="condition-{{ $detail->id }}" class="mb-1 block break-words font-medium">
                                        {{ $detail->book->judul ?? 'Buku tidak tersedia' }} ({{ $detail->jumlah }})
                                    </label>
                                    <select id="condition-{{ $detail->id }}" name="conditions[{{ $detail->id }}]" form="return-form-{{ $loan->id }}" required class="input min-w-0 max-w-full text-xs">
                                        <option value="baik">Baik</option>
                                        <option value="rusak_ringan">Rusak Ringan</option>
                                        <option value="rusak_berat">Rusak Berat</option>
                                        <option value="hilang">Hilang</option>
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="break-words px-2 py-4 text-sm">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $loan->status == 'dipinjam' ? 'bg-blue-50 text-blue-700' : '' }}
                            {{ $loan->status == 'terlambat' ? 'bg-red-50 text-red-700' : '' }}
                            {{ $loan->status == 'dikembalikan' ? 'bg-green-50 text-green-700' : '' }}">
                            {{ $loan->status == 'dikembalikan' ? 'Dikembalikan' : ucfirst($loan->status) }}
                        </span>
                    </td>
                    <td class="break-words px-2 py-4 text-right text-sm">
                        <form id="return-form-{{ $loan->id }}" action="{{ route('returns.store', $loan) }}" method="POST" onsubmit="return confirm('Proses pengembalian?')" class="inline">
                            @csrf
                            <input type="hidden" name="tanggal_kembali" value="{{ date('Y-m-d') }}">
                            <button type="submit" class="btn-action btn-action--primary whitespace-normal">Proses Kembali</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada peminjaman aktif.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="px-6">
        {{ $loans->links() }}
    </div>
</div>
@endsection
