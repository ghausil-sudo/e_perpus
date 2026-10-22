<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Booking;
use App\Models\Loan;
use App\Models\Setting;
use App\Services\ExpireBookings;
use Illuminate\Http\Request;

class MemberPortalController extends Controller
{
    public function dashboard(ExpireBookings $expireBookings)
    {
        $expireBookings->handle();
        $member = auth()->user()->member;

        if (!$member) {
            return view('member.pending');
        }

        $activeBookings = Booking::with('book')
            ->where('member_id', $member->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->latest()
            ->get();

        return view('member.dashboard', compact('member', 'activeBookings'));
    }

    public function books(Request $request, ExpireBookings $expireBookings)
    {
        $expireBookings->handle();
        $member = auth()->user()->member;
        if (!$member) {
            return redirect()->route('member.dashboard')->with('error', 'Akun Anda masih menunggu dihubungkan ke data anggota oleh petugas.');
        }

        $maxBooks = max(1, (int) (Setting::where('key', 'maksimal_buku_dipinjam')->value('value') ?? 3));
        $books = Book::with(['category', 'author'])
            ->withCount(['bookings as pending_booking_count' => fn ($query) => $query->where('status', 'menunggu')])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('judul', 'like', "%{$search}%")
                        ->orWhere('kode_buku', 'like', "%{$search}%")
                        ->orWhereHas('author', fn ($author) => $author->where('nama_penulis', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        foreach ($books as $book) {
            $book->tersedia = max(0, (int) $book->tersedia - (int) $book->pending_booking_count);
        }

        $activeBookings = Booking::where('member_id', $member->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->get(['book_id']);
        $activeBookingCount = $activeBookings->count();
        $activeBookIds = $activeBookings->pluck('book_id')->all();
        $hasActiveLoan = Loan::where('member_id', $member->id)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->exists();

        return view('member.books', compact('books', 'activeBookingCount', 'activeBookIds', 'hasActiveLoan', 'maxBooks'));
    }

    public function bookings(ExpireBookings $expireBookings)
    {
        $expireBookings->handle();
        $member = auth()->user()->member;
        if (!$member) {
            return redirect()->route('member.dashboard')->with('error', 'Akun Anda masih menunggu dihubungkan ke data anggota oleh petugas.');
        }

        $bookings = Booking::with(['book', 'approver', 'loan'])
            ->where('member_id', $member->id)
            ->latest()
            ->paginate(12);

        return view('member.bookings', compact('bookings'));
    }
}
