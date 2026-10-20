@extends('layouts.app')

@section('title', 'Tambah Peminjaman')
@section('header_title', 'Tambah Peminjaman')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form action="{{ route('loans.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="label">Anggota</label>
                <select name="member_id" required class="input">
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->kode_member }} - {{ $member->nama }}</option>
                    @endforeach
                </select>
                @error('member_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="mt-1 text-xs text-gray-500">Setiap anggota hanya dapat memiliki satu transaksi peminjaman aktif. Satu transaksi dapat berisi beberapa buku.</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" required class="input">
                    @error('tanggal_pinjam') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Batas Kembali</label>
                    <input type="date" name="tanggal_kembali" required class="input">
                    @error('tanggal_kembali') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="label">Catatan</label>
                <textarea name="catatan" rows="3" class="input"></textarea>
            </div>

            <div>
                <label class="label">Daftar Buku</label>
                <div id="book-list" class="space-y-3">
                    <div class="book-item grid grid-cols-12 gap-2 items-end">
                        <div class="col-span-6">
                            <select name="books[0][book_id]" required class="input">
                                <option value="">-- Pilih Buku --</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->kode_buku }} - {{ $book->judul }} (Tersedia: {{ $book->tersedia }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-3">
                            <input type="number" name="books[0][jumlah]" value="1" min="1" required class="input">
                        </div>
                        <div class="col-span-2">
                            <select name="books[0][kondisi]" required class="input">
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </div>
                        <div class="col-span-1">
                            <button type="button" onclick="removeBook(this)" class="w-full py-2 text-red-600 hover:text-red-800 text-sm">Hapus</button>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addBook()" class="mt-3 text-sm text-gray-900 underline">+ Tambah Buku</button>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2">
                <a href="{{ route('loans.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
let bookCount = 1;
function addBook() {
    const list = document.getElementById('book-list');
    const div = document.createElement('div');
    div.className = 'book-item grid grid-cols-12 gap-2 items-end';
    div.innerHTML = `
        <div class="col-span-6">
            <select name="books[${bookCount}][book_id]" required class="input">
                <option value="">-- Pilih Buku --</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}">{{ $book->kode_buku }} - {{ $book->judul }} (Tersedia: {{ $book->tersedia }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-span-3">
            <input type="number" name="books[${bookCount}][jumlah]" value="1" min="1" required class="input">
        </div>
        <div class="col-span-2">
            <select name="books[${bookCount}][kondisi]" required class="input">
                <option value="baik">Baik</option>
                <option value="rusak">Rusak</option>
                <option value="hilang">Hilang</option>
            </select>
        </div>
        <div class="col-span-1">
            <button type="button" onclick="removeBook(this)" class="w-full py-2 text-red-600 hover:text-red-800 text-sm">Hapus</button>
        </div>
    `;
    list.appendChild(div);
    bookCount++;
}
function removeBook(btn) {
    btn.closest('.book-item').remove();
}
</script>
@endsection
