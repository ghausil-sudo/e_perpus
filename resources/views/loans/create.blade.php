@extends('layouts.app')

@section('title', 'Tambah Peminjaman')
@section('header_title', 'Tambah Peminjaman')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form action="{{ route('loans.store') }}" method="POST" class="space-y-6">
            @csrf
            @if($bookingBatch)
                @foreach($bookingBatch as $booking)
                    <input type="hidden" name="booking_ids[]" value="{{ $booking->id }}">
                @endforeach
                <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                    Form ini memuat semua buku yang sudah disetujui untuk anggota tersebut. Simpan untuk mencatat semuanya sebagai satu peminjaman.
                </div>
            @endif
            <div>
                <label for="member_id" class="label">Anggota</label>
                <select id="member_id" @if(!$bookingBatch) name="member_id" @endif required class="input searchable-select" {{ $bookingBatch ? 'disabled' : '' }}>
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ (string) ($selectedMemberId ?? old('member_id')) === (string) $member->id ? 'selected' : '' }}>{{ $member->kode_member }} - {{ $member->nama }}</option>
                    @endforeach
                </select>
                @if($bookingBatch)
                    <input type="hidden" name="member_id" value="{{ $selectedMemberId }}">
                @endif
                @error('member_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="mt-1 text-xs text-gray-500">Maksimal {{ $maxBooks }} buku berbeda per transaksi, masing-masing satu eksemplar.</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Tanggal Pinjam</label>
                    <div class="input flex items-center bg-gray-50 text-gray-600">{{ $tanggalPinjam->format('d M Y') }}</div>
                </div>
                <div>
                    <label class="label">Batas Kembali</label>
                    <div class="input flex items-center bg-gray-50 text-gray-600">{{ $tanggalKembali->format('d M Y') }}</div>
                    <p class="mt-1 text-xs text-gray-500">Otomatis mengikuti pengaturan lama pinjam: {{ $loanPeriod }} hari.</p>
                </div>
            </div>

            <div>
                <label class="label">Catatan</label>
                <textarea name="catatan" rows="3" class="input"></textarea>
            </div>

            @php
                $loanBooks = $bookingBatch
                    ? $bookingBatch->map(fn ($booking) => ['book_id' => $booking->book_id, 'kondisi' => 'baik'])->all()
                    : array_slice(old('books', [['book_id' => '', 'kondisi' => 'baik']]), 0, $maxBooks);
                if ($loanBooks === []) {
                    $loanBooks = [['book_id' => '', 'kondisi' => 'baik']];
                }
            @endphp
            <div>
                <label class="label">Daftar Buku (maksimal {{ $maxBooks }} buku berbeda)</label>
                <div id="book-list" class="space-y-4">
                    @foreach($loanBooks as $index => $loanBook)
                        <div class="book-item rounded-lg border border-gray-200 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700">Buku {{ $index + 1 }} <span class="font-normal text-gray-500">(1 eksemplar)</span></span>
                                @if($index > 0)
                                    <button type="button" class="remove-book-btn btn-action btn-action--danger">Hapus</button>
                                @endif
                            </div>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-12 md:items-end">
                                <div class="md:col-span-8">
                                    <label for="book_id_{{ $index }}" class="label">Pilih Buku</label>
                                    <select id="book_id_{{ $index }}" @if(!$bookingBatch) name="books[{{ $index }}][book_id]" @endif required class="input book-select searchable-select" {{ $bookingBatch ? 'disabled' : '' }}>
                                        <option value="">-- Pilih Buku --</option>
                                        @foreach($books as $book)
                                            <option value="{{ $book->id }}" {{ (string) ($loanBook['book_id'] ?? '') === (string) $book->id ? 'selected' : '' }}>{{ $book->kode_buku }} - {{ $book->judul }} (Tersedia: {{ $book->tersedia }})</option>
                                        @endforeach
                                    </select>
                                    @if($bookingBatch)
                                        <input type="hidden" name="books[{{ $index }}][book_id]" value="{{ $loanBook['book_id'] }}">
                                    @endif
                                </div>
                                <div class="md:col-span-4">
                                    <label for="book_condition_{{ $index }}" class="label">Kondisi</label>
                                    <select id="book_condition_{{ $index }}" name="books[{{ $index }}][kondisi]" required class="input">
                                        <option value="baik" {{ ($loanBook['kondisi'] ?? 'baik') === 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="rusak" {{ ($loanBook['kondisi'] ?? '') === 'rusak' ? 'selected' : '' }}>Rusak</option>
                                        <option value="hilang" {{ ($loanBook['kondisi'] ?? '') === 'hilang' ? 'selected' : '' }}>Hilang</option>
                                    </select>
                                </div>
                            </div>
                            @error("books.$index.book_id") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>
                @error('books') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                <button type="button" id="add-book-btn" class="btn btn-secondary mt-3 gap-2 text-sm" {{ $bookingBatch || count($loanBooks) >= $maxBooks || $books->count() < 2 ? 'hidden' : '' }}>
                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14"></path>
                    </svg>
                    Tambah Buku Berbeda
                </button>
                <p id="book-hint" class="mt-1 text-xs text-gray-500">Masing-masing buku dicatat satu eksemplar.</p>
                @error('booking_ids') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2">
                <a href="{{ route('loans.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan dan Cetak</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('page_scripts')
    <script>
        $(function () {
            const select2Options = {
                width: '100%',
                minimumResultsForSearch: 0,
                placeholder: 'Ketik untuk mencari',
            };
            const bookList = document.getElementById('book-list');
            const addBookButton = document.getElementById('add-book-btn');
            const bookOptions = Array.from(bookList.querySelector('.book-select').options)
                .map(function (option) {
                    return option.cloneNode(true);
                });
            let nextBookIndex = {{ count($loanBooks) }};
            const maxBooks = {{ $maxBooks }};
            const lockedBookingBatch = @json((bool) $bookingBatch);

            function refreshBookChoices() {
                const selectedIds = $('.book-select').map(function () {
                    return this.value;
                }).get().filter(Boolean);

                $('.book-select').each(function () {
                    const currentSelect = this;
                    const currentValue = currentSelect.value;
                    currentSelect.replaceChildren();

                    bookOptions.forEach(function (option) {
                        if (!option.value || !selectedIds.includes(option.value) || option.value === currentValue) {
                            currentSelect.appendChild(option.cloneNode(true));
                        }
                    });

                    currentSelect.value = currentValue;
                    $(currentSelect).trigger('change.select2');
                });

                addBookButton.hidden = lockedBookingBatch
                    || $('.book-item').length >= maxBooks
                    || bookOptions.length <= 2;
            }

            function addBookRow() {
                if (lockedBookingBatch || $('.book-item').length >= maxBooks) {
                    return;
                }

                const index = nextBookIndex++;
                const row = document.createElement('div');
                row.className = 'book-item rounded-lg border border-gray-200 p-4';
                row.innerHTML = `
                    <div class="mb-3 flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-700">Buku ${$('.book-item').length + 1} <span class="font-normal text-gray-500">(1 eksemplar)</span></span>
                        <button type="button" class="remove-book-btn btn-action btn-action--danger">Hapus</button>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-12 md:items-end">
                        <div class="md:col-span-8">
                            <label for="book_id_${index}" class="label">Pilih Buku</label>
                            <select id="book_id_${index}" name="books[${index}][book_id]" required class="input book-select"></select>
                        </div>
                        <div class="md:col-span-4">
                            <label for="book_condition_${index}" class="label">Kondisi</label>
                            <select id="book_condition_${index}" name="books[${index}][kondisi]" required class="input">
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </div>
                    </div>
                `;
                bookOptions.forEach(function (option) {
                    row.querySelector('.book-select').appendChild(option.cloneNode(true));
                });
                bookList.appendChild(row);
                $(row).find('.book-select').select2(select2Options).on('change', refreshBookChoices);
                $(row).find('.remove-book-btn').on('click', function () {
                    $(row).find('.book-select').select2('destroy');
                    row.remove();
                    refreshBookChoices();
                });
                refreshBookChoices();
            }

            $('.book-select').on('change', refreshBookChoices);
            addBookButton.addEventListener('click', addBookRow);
            $('.remove-book-btn').on('click', function () {
                const row = this.closest('.book-item');
                $(row).find('.book-select').select2('destroy');
                row.remove();
                refreshBookChoices();
            });
            refreshBookChoices();
        });
    </script>
@endsection
