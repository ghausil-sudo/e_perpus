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
use App\Models\Shelf;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_borrow_multiple_books_but_only_in_one_active_loan(): void
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

        $books = collect(['BK001', 'BK002'])->map(function ($code) use ($category, $publisher, $author, $shelf) {
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

        $this->actingAs($user);

        $loanData = [
            'member_id' => $member->id,
            'tanggal_pinjam' => today()->toDateString(),
            'tanggal_kembali' => today()->addDays(7)->toDateString(),
            'books' => [
                ['book_id' => $books[0]->id, 'jumlah' => 1, 'kondisi' => 'baik'],
                ['book_id' => $books[1]->id, 'jumlah' => 1, 'kondisi' => 'baik'],
            ],
        ];

        $this->post(route('loans.store'), $loanData)
            ->assertRedirect(route('loans.index'));

        $loan = Loan::where('member_id', $member->id)->firstOrFail();
        $this->assertSame(2, Loan_detail::where('loan_id', $loan->id)->count());
        $this->assertDatabaseHas('histories', [
            'jenis' => 'peminjaman',
            'user_id' => $user->id,
        ]);
        $this->assertSame(1, History::where('jenis', 'peminjaman')->count());

        $this->post(route('loans.store'), $loanData)
            ->assertSessionHasErrors('member_id');

        $this->assertSame(1, Loan::where('member_id', $member->id)->count());

        $loan->update(['status' => 'dikembalikan']);

        $this->post(route('loans.store'), [
            ...$loanData,
            'books' => [
                ['book_id' => $books[0]->id, 'jumlah' => 1, 'kondisi' => 'baik'],
            ],
        ])->assertRedirect(route('loans.index'));

        $this->assertSame(2, Loan::where('member_id', $member->id)->count());
    }
}
