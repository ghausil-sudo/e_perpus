<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $publishers = Publisher::when($search, function ($query, $search) {
            return $query->where('nama_penerbit', 'like', "%{$search}%")
                ->orWhere('kode_penerbit', 'like', "%{$search}%");
        })->latest()->paginate(10);

        return view('publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('publishers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_penerbit' => 'required|string|max:255|unique:publishers,kode_penerbit',
            'nama_penerbit' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        Publisher::create($request->all());

        return redirect()->route('publishers.index')->with('success', 'Penerbit berhasil ditambahkan.');
    }

    public function edit(Publisher $publisher)
    {
        return view('publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $request->validate([
            'kode_penerbit' => 'required|string|max:255|unique:publishers,kode_penerbit,' . $publisher->id,
            'nama_penerbit' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        $publisher->update($request->all());

        return redirect()->route('publishers.index')->with('success', 'Penerbit berhasil diperbarui.');
    }

    public function destroy(Publisher $publisher)
    {
        $publisher->delete();

        return redirect()->route('publishers.index')->with('success', 'Penerbit berhasil dihapus.');
    }
}
