<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookReturn;
use App\Models\Category;
use App\Models\Fine;
use App\Models\History;
use App\Models\Loan;
use App\Models\Loan_detail;
use App\Models\Member;
use App\Models\Publisher;
use App\Models\Setting;
use App\Models\Shelf;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReturnControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_prevents_duplicate_returns_and_creates_fine_for_late_return(): void
    {
        [$user, $book, $loan, $detail] = $this->makeLoanForReturn();

        Setting::create(['key' => 'denda_per_hari', 'value' => 1000]);
        Setting::create(['key' => 'denda_rusak_ringan', 'value' => 2500]);
        Setting::create(['key' => 'denda_rusak_berat', 'value' => 5000]);
        Setting::create(['key' => 'denda_hilang', 'value' => 10000]);

        $this->actingAs($user);

        $response = $this->post(route('returns.store', $loan), [
            'tanggal_kembali' => now()->toDateString(),
            'catatan' => 'Kembali tepat waktu',
            'conditions' => [$detail->id => 'rusak_ringan'],
        ]);

        $response->assertRedirect(route('returns.index'));

        $loan->refresh();
        $book->refresh();

        $this->assertSame('dikembalikan', $loan->status);
        $this->assertSame(1, $book->tersedia);
        $this->assertSame(1, BookReturn::where('loan_id', $loan->id)->count());
        $this->assertSame(2, (int) BookReturn::where('loan_id', $loan->id)->value('terlambat_hari'));
        $this->assertSame(4500.0, (float) BookReturn::where('loan_id', $loan->id)->value('denda'));
        $this->assertSame('rusak_ringan', Loan_detail::where('loan_id', $loan->id)->value('kondisi_kembali'));
        $this->assertDatabaseHas('histories', [
            'jenis' => 'pengembalian',
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('fines', [
            'loan_id' => $loan->id,
            'jumlah' => 2000,
            'status' => 'belum_bayar',
        ]);
        $this->assertDatabaseHas('fines', [
            'loan_id' => $loan->id,
            'jumlah' => 2500,
            'keterangan' => 'Denda kerusakan ringan',
            'status' => 'belum_bayar',
        ]);
        $this->assertSame(4500.0, (float) Fine::where('loan_id', $loan->id)->sum('jumlah'));
        $fine = Fine::where('loan_id', $loan->id)->firstOrFail();
        $this->assertSame(0, History::where('jenis', 'kas_masuk')->count());

        $this->patch(route('fines.update', $fine))->assertRedirect(route('fines.index'));
        $this->assertDatabaseHas('histories', [
            'jenis' => 'kas_masuk',
            'fine_id' => $fine->id,
            'jumlah' => $fine->jumlah,
        ]);
        $this->patch(route('fines.update', $fine));
        $this->assertSame(1, History::where('jenis', 'kas_masuk')->where('fine_id', $fine->id)->count());

        $secondResponse = $this->post(route('returns.store', $loan), [
            'tanggal_kembali' => now()->toDateString(),
        ]);

        $secondResponse->assertRedirect();
        $secondResponse->assertSessionHas('error', 'Peminjaman ini sudah dikembalikan.');
        $this->assertSame(1, $book->fresh()->tersedia);
        $this->assertSame(1, BookReturn::where('loan_id', $loan->id)->count());
    }

    public function test_lost_books_create_configured_fine_and_reduce_total_stock(): void
    {
        [$user, $book, $loan, $detail] = $this->makeLoanForReturn();
        Setting::create(['key' => 'denda_per_hari', 'value' => 0]);
        Setting::create(['key' => 'denda_rusak_ringan', 'value' => 2500]);
        Setting::create(['key' => 'denda_rusak_berat', 'value' => 5000]);
        Setting::create(['key' => 'denda_hilang', 'value' => 10000]);

        $this->actingAs($user)
            ->post(route('returns.store', $loan), [
                'tanggal_kembali' => now()->toDateString(),
                'conditions' => [$detail->id => 'hilang'],
            ])
            ->assertRedirect(route('returns.index'));

        $this->assertSame(1, (int) $book->fresh()->jumlah);
        $this->assertSame(1, (int) $book->fresh()->tersedia);
        $this->assertSame('hilang', $detail->fresh()->kondisi_kembali);
        $this->assertDatabaseHas('fines', [
            'loan_id' => $loan->id,
            'jumlah' => 10000,
            'keterangan' => 'Denda kehilangan',
            'status' => 'belum_bayar',
        ]);
        $this->assertSame(10000.0, (float) BookReturn::where('loan_id', $loan->id)->value('denda'));
    }

    private function makeLoanForReturn(): array
    {
        $user = User::factory()->create(['role' => 'petugas']);
        $member = Member::create([
            'kode_member' => 'M001',
            'nama' => 'Budi',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Test',
            'telepon' => '08123456789',
            'email' => 'budi@example.com',
            'tanggal_daftar' => now()->toDateString(),
            'status' => 'aktif',
        ]);
        $category = Category::create([
            'kode_kategori' => 'CTG001',
            'nama_kategori' => 'Programming',
            'deskripsi' => 'Programming books',
        ]);
        $publisher = Publisher::create([
            'kode_penerbit' => 'PUB001',
            'nama_penerbit' => 'Tech Press',
            'alamat' => 'Bandung',
            'telepon' => '022111111',
            'email' => 'press@example.com',
        ]);
        $author = Author::create([
            'kode_penulis' => 'AUT001',
            'nama_penulis' => 'Jane Doe',
            'biografi' => 'Author',
        ]);
        $shelf = Shelf::create([
            'kode_rak' => 'RAK001',
            'nama_rak' => 'A1',
            'lokasi' => 'Lantai 1',
        ]);
        $book = Book::create([
            'kode_buku' => 'BK001',
            'judul' => 'Laravel Basics',
            'category_id' => $category->id,
            'publisher_id' => $publisher->id,
            'author_id' => $author->id,
            'shelf_id' => $shelf->id,
            'tahun_terbit' => 2024,
            'jumlah' => 2,
            'tersedia' => 1,
        ]);
        $loan = Loan::create([
            'kode_pinjam' => 'PJM-001',
            'member_id' => $member->id,
            'user_id' => $user->id,
            'tanggal_pinjam' => now()->subDays(7)->toDateString(),
            'tanggal_kembali' => now()->subDays(2)->toDateString(),
            'status' => 'dipinjam',
        ]);
        $detail = Loan_detail::create([
            'loan_id' => $loan->id,
            'book_id' => $book->id,
            'jumlah' => 1,
            'kondisi' => 'baik',
        ]);

        return [$user, $book, $loan, $detail];
    }
}
