<?php

namespace App\Http\Controllers;

use App\Models\Shelf;
use Illuminate\Http\Request;

class ShelfController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $shelves = Shelf::when($search, function ($query, $search) {
            return $query->where('nama_rak', 'like', "%{$search}%")
                ->orWhere('kode_rak', 'like', "%{$search}%");
        })->latest()->paginate(10);

        return view('shelves.index', compact('shelves'));
    }

    public function create()
    {
        return view('shelves.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_rak' => 'required|string|max:255|unique:shelves,kode_rak',
            'nama_rak' => 'required|string|max:255',
            'lokasi' => 'nullable|string',
            'tipe' => 'required|in:rak,etalase',
            'kapasitas' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        Shelf::create($request->all());

        return redirect()->route('shelves.index')->with('success', 'Rak berhasil ditambahkan.');
    }

    public function edit(Shelf $shelf)
    {
        return view('shelves.edit', compact('shelf'));
    }

    public function update(Request $request, Shelf $shelf)
    {
        $request->validate([
            'kode_rak' => 'required|string|max:255|unique:shelves,kode_rak,' . $shelf->id,
            'nama_rak' => 'required|string|max:255',
            'lokasi' => 'nullable|string',
            'tipe' => 'required|in:rak,etalase',
            'kapasitas' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $shelf->update($request->all());

        return redirect()->route('shelves.index')->with('success', 'Rak berhasil diperbarui.');
    }

    public function destroy(Shelf $shelf)
    {
        $shelf->delete();

        return redirect()->route('shelves.index')->with('success', 'Rak berhasil dihapus.');
    }
}
