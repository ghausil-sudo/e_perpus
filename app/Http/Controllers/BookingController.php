<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use App\Services\ExpireBookings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function store(Book $book, ExpireBookings $expireBookings)
    {
        $expireBookings->handle();
        $member = auth()->user()->member;
        if (!$member) {
            return redirect()->route('member.dashboard')->with('error', 'Akun Anda belum dihubungkan ke data anggota.');
        }

        DB::transaction(function () use ($member, $book) {
            $member = Member::whereKey($member->id)->lockForUpdate()->firstOrFail();
            $book = Book::whereKey($book->id)->lockForUpdate()->firstOrFail();

            if ($member->status !== 'aktif') {
                throw ValidationException::withMessages(['booking' => 'Status anggota tidak aktif sehingga tidak dapat melakukan booking.']);
            }
            if (Loan::where('member_id', $member->id)->whereIn('status', ['dipinjam', 'terlambat'])->exists()) {
                throw ValidationException::withMessages(['booking' => 'Selesaikan peminjaman aktif sebelum mengajukan booking.']);
            }
            $activeBookings = Booking::where('member_id', $member->id)
                ->whereIn('status', ['menunggu', 'disetujui']);
            if ($activeBookings->count() >= 3) {
                throw ValidationException::withMessages(['booking' => 'Anda hanya dapat memiliki maksimal tiga booking aktif.']);
            }
            if ($activeBookings->where('book_id', $book->id)->exists()) {
                throw ValidationException::withMessages(['booking' => 'Buku ini sudah ada dalam booking aktif Anda.']);
            }
            if ($book->tersedia < 1) {
                throw ValidationException::withMessages(['booking' => 'Buku ini sedang tidak tersedia.']);
            }

            Booking::create([
                'member_id' => $member->id,
                'book_id' => $book->id,
                'status' => 'menunggu',
            ]);
        });

        return redirect()->route('member.bookings')->with('success', 'Booking berhasil diajukan. Tunggu persetujuan petugas sebelum datang ke perpustakaan.');
    }

    public function cancel(Booking $booking, ExpireBookings $expireBookings)
    {
        $expireBookings->handle();

        DB::transaction(function () use ($booking) {
            $booking = Booking::whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ($booking->member_id !== auth()->user()->member_id) {
                abort(403);
            }
            if (!in_array($booking->status, ['menunggu', 'disetujui'], true)) {
                throw ValidationException::withMessages(['booking' => 'Booking ini tidak dapat dibatalkan.']);
            }

            if ($booking->status === 'disetujui') {
                $book = Book::whereKey($booking->book_id)->lockForUpdate()->firstOrFail();
                $book->tersedia = min($book->jumlah, $book->tersedia + 1);
                $book->save();
            }

            $booking->update(['status' => 'dibatalkan']);
        });

        return redirect()->route('member.bookings')->with('success', 'Booking berhasil dibatalkan.');
    }

    public function index(ExpireBookings $expireBookings)
    {
        $expireBookings->handle();
        $bookings = Booking::with(['member', 'book', 'approver', 'loan'])
            ->latest()
            ->paginate(15);
        $pendingAccounts = User::where('role', 'user')
            ->whereNull('member_id')
            ->orderBy('created_at')
            ->get();

        return view('bookings.index', compact('bookings', 'pendingAccounts'));
    }

    public function approve(Booking $booking, ExpireBookings $expireBookings)
    {
        $expireBookings->handle();
        DB::transaction(function () use ($booking) {
            $booking = Booking::whereKey($booking->id)->lockForUpdate()->firstOrFail();
            $member = Member::whereKey($booking->member_id)->lockForUpdate()->firstOrFail();
            $book = Book::whereKey($booking->book_id)->lockForUpdate()->firstOrFail();

            if ($booking->status !== 'menunggu') {
                throw ValidationException::withMessages(['booking' => 'Booking ini sudah diproses.']);
            }
            if ($member->status !== 'aktif') {
                throw ValidationException::withMessages(['booking' => 'Anggota tidak aktif.']);
            }
            if (Loan::where('member_id', $member->id)->whereIn('status', ['dipinjam', 'terlambat'])->exists()) {
                throw ValidationException::withMessages(['booking' => 'Anggota masih memiliki peminjaman aktif.']);
            }
            if ($book->tersedia < 1) {
                throw ValidationException::withMessages(['booking' => 'Stok buku sudah tidak tersedia.']);
            }

            $book->decrement('tersedia');
            $booking->update([
                'status' => 'disetujui',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'expires_at' => now()->addHours(24),
            ]);
        });

        return redirect()->route('bookings.index')->with('success', 'Booking disetujui. Buku ditahan selama 24 jam.');
    }

    public function reject(Booking $booking)
    {
        DB::transaction(function () use ($booking) {
            $booking = Booking::whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ($booking->status !== 'menunggu') {
                throw ValidationException::withMessages(['booking' => 'Booking ini sudah diproses.']);
            }
            $booking->update([
                'status' => 'ditolak',
                'approved_by' => auth()->id(),
            ]);
        });

        return redirect()->route('bookings.index')->with('success', 'Booking ditolak.');
    }

}
