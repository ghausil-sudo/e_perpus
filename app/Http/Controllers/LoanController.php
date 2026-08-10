<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Member;
use App\Models\Loan_detail;
use App\Models\Book;
use Illuminate\Http\Request;

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

    public function create()
    {
        $members = Member::where('status', 'aktif')->get();
        $books = Book::where('tersedia', '>', 0)->get();

        return view('loans.create', compact('members', 'books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'catatan' => 'nullable|string',
            'books' => 'required|array|min:1',
            'books.*.book_id' => 'required|exists:books,id',
            'books.*.jumlah' => 'required|integer|min:1',
            'books.*.kondisi' => 'required|in:baik,rusak,hilang',
        ]);

        $loan = Loan::create([
            'kode_pinjam' => 'PJM-' . date('Ymd') . '-' . str_pad(Loan::count() + 1, 4, '0', STR_PAD_LEFT),
            'member_id' => $request->member_id,
            'user_id' => auth()->id(),
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'catatan' => $request->catatan,
            'status' => 'dipinjam',
        ]);

        foreach ($request->books as $item) {
            Loan_detail::create([
                'loan_id' => $loan->id,
                'book_id' => $item['book_id'],
                'jumlah' => $item['jumlah'],
                'kondisi' => $item['kondisi'],
            ]);

            $book = Book::findOrFail($item['book_id']);
            $book->tersedia = max(0, $book->tersedia - $item['jumlah']);
            $book->save();
        }

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function show(Loan $loan)
    {
        $loan->load(['member', 'user', 'loanDetails.book']);
        return view('loans.show', compact('loan'));
    }
}
