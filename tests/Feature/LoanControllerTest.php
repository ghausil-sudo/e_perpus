<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
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

class LoanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_loan_allows_two_distinct_books_but_only_one_copy_of_each(): void
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

        $books = collect(['BK001', 'BK002', 'BK003'])->map(function ($code) use ($category, $publisher, $author, $shelf) {
            return Book::create([
                'kode_buku' => $code,
                'judul' => 'Book ' . $code,
                'category_id' => $category->id,
                'publisher_id' => $publisher->id,
                'author_id' => $author->id,
                'shelf_id' => $shelf->id,
                'tahun_terbit' => 2024,
                'jumlah' => 2,
                'tersedia' => 2,
            ]);
        });
        Setting::create(['key' => 'lama_pinjam', 'value' => '10']);
        Loan::create([
            'kode_pinjam' => 'PJM-' . today()->format('Ymd') . '-0012',
            'member_id' => $member->id,
            'user_id' => $user->id,
            'tanggal_pinjam' => today()->subDays(30),
            'tanggal_kembali' => today()->subDays(20),
            'tanggal_dikembalikan' => today()->subDays(20),
            'status' => 'dikembalikan',
        ]);

        $this->actingAs($user);

        $loanData = [
            'member_id' => $member->id,
            'books' => [
                ['book_id' => $books[0]->id, 'kondisi' => 'baik'],
                ['book_id' => $books[1]->id, 'kondisi' => 'baik'],
            ],
        ];

        $this->get(route('loans.create'))
            ->assertOk()
            ->assertSee('select2.min.js')
            ->assertSee('select2.min.css')
            ->assertSee('Otomatis mengikuti pengaturan lama pinjam: 10 hari.')
            ->assertSee('Maksimal 2 buku berbeda per transaksi')
            ->assertDontSee('name="tanggal_kembali"');

        $this->post(route('loans.store'), [
            ...$loanData,
            'books' => [
                ...$loanData['books'],
                ['book_id' => $books[0]->id, 'kondisi' => 'baik'],
            ],
        ])->assertSessionHasErrors('books.2.book_id');

        $this->post(route('loans.store'), [
            ...$loanData,
            'books' => [
                ...$loanData['books'],
                ['book_id' => $books[2]->id, 'kondisi' => 'baik'],
            ],
        ])->assertSessionHasErrors('books');

        $books[1]->update(['tersedia' => 0]);
        $this->post(route('loans.store'), $loanData)
            ->assertSessionHasErrors('books.1.book_id');
        $this->assertSame(1, Loan::count());
        $books[1]->update(['tersedia' => 2]);

        $response = $this->post(route('loans.store'), $loanData);

        $loan = Loan::where('member_id', $member->id)->where('status', 'dipinjam')->firstOrFail();
        $response->assertRedirect(route('loans.receipt', $loan));
        $this->assertSame('PJM-' . today()->format('Ymd') . '-0013', $loan->kode_pinjam);
        $this->assertSame(2, Loan_detail::where('loan_id', $loan->id)->count());
        $this->assertSame(2, Loan_detail::where('loan_id', $loan->id)->where('jumlah', 1)->count());
        $this->assertSame(today()->toDateString(), $loan->tanggal_pinjam->toDateString());
        $this->assertSame(today()->addDays(10)->toDateString(), $loan->tanggal_kembali->toDateString());
        $this->assertSame(1, (int) $books[0]->fresh()->tersedia);
        $this->assertSame(1, (int) $books[1]->fresh()->tersedia);
        $this->assertDatabaseHas('histories', [
            'jenis' => 'peminjaman',
            'user_id' => $user->id,
        ]);
        $this->assertSame(1, History::where('jenis', 'peminjaman')->count());

        $this->get(route('loans.receipt', $loan))
            ->assertOk()
            ->assertSee($loan->kode_pinjam)
            ->assertSee('window.print()')
            ->assertSee('Book BK001')
            ->assertSee('Book BK002');

        $this->get(route('loans.index'))
            ->assertOk()
            ->assertSee('data-select2="false"', false)
            ->assertSee('sm:w-44', false);

        $this->post(route('loans.store'), $loanData)
            ->assertSessionHasErrors('member_id');

        $this->assertSame(2, Loan::where('member_id', $member->id)->count());
    }
}
