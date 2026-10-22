<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\BookReturn;
use App\Models\Fine;
use App\Models\History;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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

        $data = $request->validate([
            'tanggal_kembali' => 'required|date|after_or_equal:' . $loan->tanggal_pinjam,
            'catatan' => 'nullable|string',
            'conditions' => 'required|array',
            'conditions.*' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
        ]);

        DB::transaction(function () use ($data, $loan) {
            $loan = Loan::with(['member', 'loanDetails.book'])
                ->whereKey($loan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($loan->status, ['dipinjam', 'terlambat']) || BookReturn::where('loan_id', $loan->id)->exists()) {
                throw ValidationException::withMessages([
                    'tanggal_kembali' => 'Peminjaman ini sudah dikembalikan.',
                ]);
            }

            $detailIds = $loan->loanDetails->pluck('id')->map(fn ($id) => (int) $id)->sort()->values();
            $conditionIds = collect(array_keys($data['conditions']))->map(fn ($id) => (int) $id)->sort()->values();
            if ($detailIds->all() !== $conditionIds->all()) {
                throw ValidationException::withMessages([
                    'conditions' => 'Pilih kondisi akhir untuk setiap buku yang dikembalikan.',
                ]);
            }

            $tanggalKembali = Carbon::parse($data['tanggal_kembali']);
            $batasKembali = Carbon::parse($loan->tanggal_kembali);
            $terlambatHari = $tanggalKembali->greaterThan($batasKembali)
                ? (int) $batasKembali->diffInDays($tanggalKembali)
                : 0;

            $rates = Setting::whereIn('key', [
                'denda_per_hari',
                'denda_rusak_ringan',
                'denda_rusak_berat',
                'denda_hilang',
            ])->pluck('value', 'key');

            $fineBreakdown = [];
            $fineDescriptions = [];
            if ($terlambatHari > 0) {
                $lateFine = $terlambatHari * (float) ($rates['denda_per_hari'] ?? 0);
                if ($lateFine > 0) {
                    $fineBreakdown['keterlambatan'] = $lateFine;
                    $fineDescriptions['keterlambatan'] = 'Denda keterlambatan ' . $terlambatHari . ' hari';
                }
            }

            $stockChanges = [];
            $returnedBooks = [];
            foreach ($loan->loanDetails as $detail) {
                $condition = $data['conditions'][$detail->id];
                $detail->kondisi_kembali = $condition;
                $detail->save();

                $bookId = $detail->book_id;
                $stockChanges[$bookId] ??= ['baik' => 0, 'hilang' => 0];
                if ($condition === 'baik') {
                    $stockChanges[$bookId]['baik'] += $detail->jumlah;
                } elseif ($condition === 'hilang') {
                    $stockChanges[$bookId]['hilang'] += $detail->jumlah;
                }

                $bookTitle = $detail->book->judul ?? 'Buku tidak tersedia';
                $conditionLabel = match ($condition) {
                    'rusak_ringan' => 'rusak ringan',
                    'rusak_berat' => 'rusak berat',
                    'hilang' => 'hilang',
                    default => 'baik',
                };
                $returnedBooks[] = $bookTitle . ' (' . $detail->jumlah . ', ' . $conditionLabel . ')';

                $fineType = match ($condition) {
                    'rusak_ringan' => ['key' => 'rusak_ringan', 'rate' => 'denda_rusak_ringan', 'label' => 'Denda kerusakan ringan'],
                    'rusak_berat' => ['key' => 'rusak_berat', 'rate' => 'denda_rusak_berat', 'label' => 'Denda kerusakan berat'],
                    'hilang' => ['key' => 'hilang', 'rate' => 'denda_hilang', 'label' => 'Denda kehilangan'],
                    default => null,
                };

                if ($fineType !== null) {
                    $amount = $detail->jumlah * (float) ($rates[$fineType['rate']] ?? 0);
                    if ($amount > 0) {
                        $fineBreakdown[$fineType['key']] = ($fineBreakdown[$fineType['key']] ?? 0) + $amount;
                        $fineDescriptions[$fineType['key']] = $fineType['label'];
                    }
                }
            }

            foreach ($stockChanges as $bookId => $change) {
                $book = Book::whereKey($bookId)->lockForUpdate()->firstOrFail();
                if ($book->jumlah < $change['hilang']) {
                    throw ValidationException::withMessages([
                        'conditions' => 'Jumlah stok buku tidak sesuai untuk mencatat buku hilang: ' . $book->judul . '.',
                    ]);
                }

                $book->jumlah -= $change['hilang'];
                $book->tersedia += $change['baik'];
                if ($book->tersedia > $book->jumlah) {
                    throw ValidationException::withMessages([
                        'conditions' => 'Stok tersedia melebihi jumlah buku: ' . $book->judul . '.',
                    ]);
                }
                $book->save();
            }

            $totalFine = array_sum($fineBreakdown);
            BookReturn::create([
                'kode_kembali' => 'KMB-' . date('Ymd') . '-' . str_pad(BookReturn::count() + 1, 4, '0', STR_PAD_LEFT),
                'loan_id' => $loan->id,
                'user_id' => Auth::id(),
                'tanggal_kembali' => $data['tanggal_kembali'],
                'terlambat_hari' => $terlambatHari,
                'denda' => $totalFine,
                'catatan' => $data['catatan'] ?? null,
            ]);

            $loan->update([
                'tanggal_dikembalikan' => $data['tanggal_kembali'],
                'status' => 'dikembalikan',
            ]);

            foreach ($fineBreakdown as $key => $amount) {
                Fine::create([
                    'kode_denda' => 'DND-' . date('Ymd') . '-' . str_pad(Fine::count() + 1, 4, '0', STR_PAD_LEFT),
                    'member_id' => $loan->member_id,
                    'loan_id' => $loan->id,
                    'jumlah' => $amount,
                    'keterangan' => $fineDescriptions[$key],
                    'status' => 'belum_bayar',
                    'tanggal_bayar' => null,
                ]);
            }

            $description = sprintf(
                '%s - anggota %s mengembalikan: %s pada %s.',
                $loan->kode_pinjam,
                $loan->member->nama,
                implode(', ', $returnedBooks),
                $tanggalKembali->format('d-m-Y')
            );

            if ($terlambatHari > 0) {
                $description .= sprintf(' Terlambat %d hari.', $terlambatHari);
            }
            if ($totalFine > 0) {
                $description .= sprintf(' Total denda Rp %s.', number_format($totalFine, 0, ',', '.'));
            }

            History::create([
                'jenis' => 'pengembalian',
                'judul' => 'Pengembalian buku',
                'keterangan' => $description,
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()->route('returns.index')->with('success', 'Pengembalian berhasil dicatat.');
    }
}
