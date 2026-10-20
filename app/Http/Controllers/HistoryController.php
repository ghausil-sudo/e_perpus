<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $histories = History::with('user')->latest()->paginate(15);

        return view('histories.index', compact('histories'));
    }

    public function storeExpense(Request $request)
    {
        $data = $request->validate([
            'keterangan' => 'required|string|max:1000',
            'jumlah' => 'required|numeric|gt:0|max:9999999999.99',
        ]);

        History::create([
            'jenis' => 'kas_keluar',
            'judul' => 'Pengeluaran kas',
            'keterangan' => $data['keterangan'],
            'jumlah' => $data['jumlah'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('histories.index')->with('success', 'Pengeluaran berhasil dicatat.');
    }
}
