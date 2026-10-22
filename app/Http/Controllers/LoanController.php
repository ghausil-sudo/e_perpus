<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Booking;
use App\Models\History;
use App\Models\Loan;
use App\Models\Loan_detail;
use App\Models\Member;
use App\Models\Setting;
use App\Services\ExpireBookings;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'dipinjam');
        $loans = Loan::with(['member', 'user', 'loanDetails.book'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return view('loans.index', compact('loans', 'status'));
    }

    public function create(Request $request, ExpireBookings $expireBookings)
    {
        $expireBookings->handle();
        $members = Member::where('status', 'aktif')->get();
        $bookingBatch = null;
        $selectedMemberId = null;

        if ($request->filled('booking_id')) {
            $requestedBooking = Booking::with('member')
                ->whereKey($request->integer('booking_id'))
                ->where('status', 'disetujui')
                ->first();

            if (! $requestedBooking || ! $requestedBooking->expires_at || $requestedBooking->expires_at->isPast()) {
                return redirect()->route('bookings.index')->with('error', 'Booking tidak berlaku atau masa penahanan buku telah habis.');
            }

            $selectedMemberId = $requestedBooking->member_id;
            $bookingBatch = Booking::with('book')
                ->where('member_id', $selectedMemberId)
                ->where('status', 'disetujui')
                ->where('expires_at', '>', now())
                ->oldest()
                ->get();

            if ($bookingBatch->isEmpty() || $bookingBatch->count() > 3) {
                return redirect()->route('bookings.index')->with('error', 'Daftar booking anggota tidak valid. Periksa kembali booking yang disetujui.');
            }
        }

        $reservedBookIds = $bookingBatch?->pluck('book_id') ?? collect();
        $books = Book::where('tersedia', '>', 0)
            ->when($reservedBookIds->isNotEmpty(), fn ($query) => $query->orWhereIn('id', $reservedBookIds))
            ->get();
        $loanPeriod = (int) (Setting::where('key', 'lama_pinjam')->value('value') ?? 7);
        $loanPeriod = max(1, $loanPeriod);
        $tanggalPinjam = today();
        $tanggalKembali = $tanggalPinjam->copy()->addDays($loanPeriod);

        return view('loans.create', compact(
            'members',
            'books',
            'loanPeriod',
            'tanggalPinjam',
            'tanggalKembali',
            'bookingBatch',
            'selectedMemberId'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id' => 'required|exists:members,id',
            'catatan' => 'nullable|string',
            'booking_ids' => 'nullable|array|min:1|max:3',
            'booking_ids.*' => 'required|integer|distinct|exists:bookings,id',
            'books' => 'required|array|min:1|max:3',
            'books.*.book_id' => 'required|integer|distinct|exists:books,id',
            'books.*.kondisi' => 'required|in:baik,rusak,hilang',
        ]);

        if (empty($data['booking_ids']) && count($data['books']) > 2) {
            throw ValidationException::withMessages([
                'books' => 'Peminjaman langsung maksimal dua buku berbeda.',
            ]);
        }

        $attempt = 0;
        do {
            try {
                $loan = DB::transaction(function () use ($data) {
                    $member = Member::whereKey($data['member_id'])->lockForUpdate()->firstOrFail();
                    if ($member->status !== 'aktif') {
                        throw ValidationException::withMessages([
                            'member_id' => 'Anggota tidak aktif dan tidak dapat melakukan peminjaman.',
                        ]);
                    }

                    $bookingIds = $data['booking_ids'] ?? [];
                    $bookings = collect();
                    if ($bookingIds !== []) {
                        $bookings = Booking::whereIn('id', $bookingIds)
                            ->where('member_id', $member->id)
                            ->where('status', 'disetujui')
                            ->lockForUpdate()
                            ->get();

                        $submittedBookIds = collect($data['books'])->pluck('book_id')->map(fn ($id) => (int) $id)->sort()->values();
                        $bookedBookIds = $bookings->pluck('book_id')->map(fn ($id) => (int) $id)->sort()->values();

                        if (
                            $bookings->count() !== count($bookingIds)
                            || $submittedBookIds->all() !== $bookedBookIds->all()
                            || $bookings->contains(fn ($booking) => ! $booking->expires_at || $booking->expires_at->isPast())
                        ) {
                            throw ValidationException::withMessages([
                                'booking_ids' => 'Booking sudah berubah, kedaluwarsa, atau tidak sesuai dengan daftar buku.',
                            ]);
                        }
                    }

                    $activeLoan = Loan::where('member_id', $data['member_id'])
                        ->whereIn('status', ['dipinjam', 'terlambat'])
                        ->lockForUpdate()
                        ->first();

                    if ($activeLoan && ($bookingIds === [] || ! str_contains((string) $activeLoan->catatan, 'Peminjaman melalui booking anggota'))) {
                        throw ValidationException::withMessages([
                            'member_id' => 'Anggota masih memiliki peminjaman aktif. Selesaikan pengembalian terlebih dahulu sebelum membuat peminjaman baru.',
                        ]);
                    }
                    if ($bookingIds === [] && Booking::where('member_id', $member->id)->where('status', 'disetujui')->exists()) {
                        throw ValidationException::withMessages([
                            'member_id' => 'Anggota memiliki booking buku yang disetujui. Proses pengambilan booking tersebut terlebih dahulu.',
                        ]);
                    }

                    $selectedBooks = [];
                    foreach (array_values($data['books']) as $index => $item) {
                        $book = Book::whereKey($item['book_id'])->lockForUpdate()->firstOrFail();
                        $booking = $bookings->firstWhere('book_id', $book->id);
                        if (! $booking && $book->tersedia < 1) {
                            throw ValidationException::withMessages([
                                'books.'.$index.'.book_id' => 'Buku '.$book->judul.' sudah tidak tersedia.',
                            ]);
                        }
                        $selectedBooks[] = ['book' => $book, 'condition' => $item['kondisi'], 'booking' => $booking];
                    }

                    $tanggalPinjam = today();
                    $loanPeriod = max(1, (int) (Setting::where('key', 'lama_pinjam')->value('value') ?? 7));
                    $tanggalKembali = $tanggalPinjam->copy()->addDays($loanPeriod);

                    $loan = $activeLoan ?? Loan::create([
                        'kode_pinjam' => $this->nextLoanCode(),
                        'member_id' => $data['member_id'],
                        'user_id' => auth()->id(),
                        'tanggal_pinjam' => $tanggalPinjam,
                        'tanggal_kembali' => $tanggalKembali,
                        'catatan' => $bookingIds !== [] ? 'Peminjaman melalui booking anggota' : ($data['catatan'] ?? null),
                        'status' => 'dipinjam',
                    ]);

                    $borrowedBooks = [];
                    foreach ($selectedBooks as $selectedBook) {
                        $book = $selectedBook['book'];
                        Loan_detail::create([
                            'loan_id' => $loan->id,
                            'book_id' => $book->id,
                            'jumlah' => 1,
                            'kondisi' => $selectedBook['condition'],
                        ]);

                        if ($selectedBook['booking']) {
                            $selectedBook['booking']->update([
                                'status' => 'diambil',
                                'loan_id' => $loan->id,
                            ]);
                        } else {
                            $book->decrement('tersedia');
                        }
                        $borrowedBooks[] = $book->judul.' (1)';
                    }

                    History::create([
                        'jenis' => 'peminjaman',
                        'judul' => 'Peminjaman buku',
                        'keterangan' => sprintf(
                            '%s - anggota %s meminjam: %s. Batas kembali: %s.',
                            $loan->kode_pinjam,
                            $member->nama,
                            implode(', ', $borrowedBooks),
                            $loan->tanggal_kembali
                        ),
                        'user_id' => auth()->id(),
                    ]);

                    return $loan;
                });
                break;
            } catch (QueryException $exception) {
                if (! $this->isLoanCodeCollision($exception) || $attempt >= 2) {
                    throw $exception;
                }

                $attempt++;
            }
        } while (true);

        return redirect()->route('loans.receipt', $loan)->with('success', 'Peminjaman berhasil dicatat.');
    }

    private function nextLoanCode(): string
    {
        $prefix = 'PJM-'.today()->format('Ymd').'-';
        $lastSequence = Loan::where('kode_pinjam', 'like', $prefix.'%')
            ->pluck('kode_pinjam')
            ->map(fn ($code) => (int) substr($code, strlen($prefix)))
            ->max() ?? 0;

        return $prefix.str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
    }

    private function isLoanCodeCollision(QueryException $exception): bool
    {
        $errorInfo = $exception->errorInfo;

        return isset($errorInfo[1])
            && (int) $errorInfo[1] === 1062
            && str_contains($exception->getMessage(), 'loans_kode_pinjam_unique');
    }

    public function show(Loan $loan)
    {
        $loan->load(['member', 'user', 'loanDetails.book']);

        return view('loans.show', compact('loan'));
    }

    public function receipt(Loan $loan)
    {
        $loan->load(['member', 'user', 'loanDetails.book']);

        return view('loans.receipt', compact('loan'));
    }
}
