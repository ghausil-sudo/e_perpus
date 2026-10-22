<?php
namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Shelf;
use App\Services\ExpireBookings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(ExpireBookings $expireBookings)
    {
        $expireBookings->handle();
        $books = Book::with(['category', 'author'])->latest()->paginate(10);
        return view('books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::all();
        $authors = Author::all();
        $publishers = Publisher::all();
        $shelves = Shelf::all();
        return view('books.create', compact('categories', 'authors', 'publishers', 'shelves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_buku' => 'required|string|max:255|unique:books,kode_buku',
            'judul' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'shelf_id' => 'nullable|exists:shelves,id',
            'tahun_terbit' => 'required|digits:4',
            'jumlah' => 'required|integer|min:1',
            'cover' => 'nullable|image|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $data = $request->except('cover');
        $data['tersedia'] = $request->jumlah;

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('books', 'public');
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book)
    {
        $book->load(['category', 'author', 'publisher', 'shelf']);
        $categories = Category::all();
        $authors = Author::all();
        $publishers = Publisher::all();
        $shelves = Shelf::all();

        return view('books.edit', compact('book', 'categories', 'authors', 'publishers', 'shelves'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'kode_buku' => 'required|string|max:255|unique:books,kode_buku,' . $book->id,
            'judul' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'shelf_id' => 'nullable|exists:shelves,id',
            'tahun_terbit' => 'required|digits:4',
            'jumlah' => 'required|integer|min:1',
            'cover' => 'nullable|image|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $data = $request->except(['_token', '_method', 'cover']);
        $data['shelf_id'] = $request->filled('shelf_id') ? $request->shelf_id : null;

        if ($request->hasFile('cover')) {
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }
            $data['cover'] = $request->file('cover')->store('books', 'public');
        } else {
            $data['cover'] = $book->cover;
        }

        $diff = (int) $request->jumlah - (int) $book->jumlah;
        $data['tersedia'] = min((int) $request->jumlah, max(0, (int) $book->tersedia + $diff));

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        if ($book->bookings()->whereIn('status', ['menunggu', 'disetujui'])->exists()) {
            return back()->with('error', 'Tidak dapat menghapus buku karena masih ada booking aktif.');
        }

        $hasActiveLoan = $book->loanDetails()
            ->whereHas('loan', function ($query) {
                $query->whereNull('tanggal_dikembalikan')
                    ->whereIn('status', ['dipinjam', 'terlambat']);
            })
            ->exists();

        if ($hasActiveLoan) {
            return back()->with('error', 'Tidak dapat menghapus buku karena masih ada peminjaman aktif.');
        }

        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}