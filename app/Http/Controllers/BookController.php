<?php
namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Shelf;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
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

        $data = $request->except('cover');

        if ($request->hasFile('cover')) {
            if ($book->cover && \Storage::disk('public')->exists($book->cover)) {
                \Storage::disk('public')->delete($book->cover);
            }
            $data['cover'] = $request->file('cover')->store('books', 'public');
        }

        $diff = $request->jumlah - $book->jumlah;
        $data['tersedia'] = max(0, $book->tersedia + $diff);

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        if ($book->tersedia < $book->jumlah) {
            return back()->with('error', 'Tidak dapat menghapus buku yang sedang dipinjam.');
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}