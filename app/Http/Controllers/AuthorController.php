<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $authors = Author::when($search, function ($query, $search) {
            return $query->where('nama_penulis', 'like', "%{$search}%")
                ->orWhere('kode_penulis', 'like', "%{$search}%");
        })->latest()->paginate(10);

        return view('authors.index', compact('authors'));
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_penulis' => 'required|string|max:255|unique:authors,kode_penulis',
            'nama_penulis' => 'required|string|max:255',
            'biografi' => 'nullable|string',
        ]);

        Author::create($request->all());

        return redirect()->route('authors.index')->with('success', 'Penulis berhasil ditambahkan.');
    }

    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $request->validate([
            'kode_penulis' => 'required|string|max:255|unique:authors,kode_penulis,' . $author->id,
            'nama_penulis' => 'required|string|max:255',
            'biografi' => 'nullable|string',
        ]);

        $author->update($request->all());

        return redirect()->route('authors.index')->with('success', 'Penulis berhasil diperbarui.');
    }

    public function destroy(Author $author)
    {
        $author->delete();

        return redirect()->route('authors.index')->with('success', 'Penulis berhasil dihapus.');
    }
}
