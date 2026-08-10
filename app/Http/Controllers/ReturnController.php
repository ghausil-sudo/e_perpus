<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Member;
use App\Models\Loan_detail;
use App\Models\Book;
use App\Models\BookReturn;
use App\Models\Fine;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with(['member', 'user', 'loanDetails.book'])
            ->whereIn('status', ['dipinjam', 'terlambat']);

        if ($search = $request->get('search')) {
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_member', 'like', "%{$search}%");
            })->orWhere('kode_pinjam', 'like', "%{$search}%");
        }

        $loans = $query->latest()->paginate(10);

        return view('returns.index', compact('loans'));
    }

    public function store(Request $request, Loan $loan)
    {
        if (!in_array($loan->status, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan.');
        }

        $request->validate([
            'tanggal_kembali' => 'required|date|after_or_equal:' . $loan->tanggal_pinjam,
            'catatan' => 'nullable|string',
        ]);

        $terlambat = false;
        $terlambat_hari = 0;
        $denda = 0;

        if ($request->tanggal_kembali > $loan->tanggal_kembali) {
            $terlambat = true;
            $terlambat_hari = \Carbon\Carbon::parse($request->tanggal_kembali)->diffInDays(\Carbon\Carbon::parse($loan->tanggal_kembali));
            $denda = $terlambat_hari * 1000;
        }

        BookReturn::create([
            'kode_kembali' => 'KMB-' . date('Ymd') . '-' . str_pad(BookReturn::count() + 1, 4, '0', STR_PAD_LEFT),
            'loan_id' => $loan->id,
            'user_id' => auth()->id(),
            'tanggal_kembali' => $request->tanggal_kembali,
            'terlambat_hari' => $terlambat_hari,
            'denda' => $denda,
            'catatan' => $request->catatan,
        ]);

        $loan->update([
            'tanggal_dikembalikan' => $request->tanggal_kembali,
            'status' => $terlambat ? 'terlambat' : 'dikembalikan',
        ]);

        foreach ($loan->loanDetails as $detail) {
            $book = Book::findOrFail($detail->book_id);
            $book->tersedia += $detail->jumlah;
            $book->save();
        }

        if ($denda > 0) {
            Fine::create([
                'kode_denda' => 'DND-' . date('Ymd') . '-' . str_pad(Fine::count() + 1, 4, '0', STR_PAD_LEFT),
                'member_id' => $loan->member_id,
                'loan_id' => $loan->id,
                'jumlah' => $denda,
                'keterangan' => 'Denda keterlambatan ' . $terlambat_hari . ' hari',
                'status' => 'belum_bayar',
                'tanggal_bayar' => null,
            ]);
        }

        return redirect()->route('returns.index')->with('success', 'Pengembalian berhasil dicatat.');
    }
}
