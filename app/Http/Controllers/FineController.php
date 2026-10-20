<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $query = Fine::with(['member', 'loan'])->latest();

        if ($search = $request->get('search')) {
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_member', 'like', "%{$search}%");
            })->orWhere('kode_denda', 'like', "%{$search}%");
        }

        $fines = $query->paginate(10);

        return view('fines.index', compact('fines'));
    }

    public function update(Fine $fine)
    {
        DB::transaction(function () use ($fine) {
            $fine = Fine::with('member')->whereKey($fine->id)->lockForUpdate()->firstOrFail();

            if ($fine->status === 'lunas') {
                return;
            }

            $fine->update([
                'status' => 'lunas',
                'tanggal_bayar' => today(),
            ]);

            History::create([
                'jenis' => 'kas_masuk',
                'judul' => 'Pembayaran denda',
                'keterangan' => sprintf(
                    'Denda %s dari anggota %s',
                    $fine->kode_denda,
                    $fine->member->nama
                ),
                'jumlah' => $fine->jumlah,
                'fine_id' => $fine->id,
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()->route('fines.index')->with('success', 'Denda berhasil dilunasi dan dicatat sebagai kas masuk.');
    }
}
