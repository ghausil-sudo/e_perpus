<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Loan_detail;
use App\Models\Member;
use App\Models\Publisher;
use App\Models\Setting;
use App\Models\Shelf;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberBookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_registration_waits_for_staff_link_and_cannot_access_staff_pages(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('select2.min.css')
            ->assertSee('select2.min.js')
            ->assertSee('initializeSelects(document)');

        $this->post(route('register.store'), [
            'name' => 'Dewi Anggota',
            'username' => 'dewi-anggota',
            'email' => 'dewi@example.test',
            'nis_nip' => '12345',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Anggota Baru',
            'telepon' => '08123456789',
            'password' => 'password-anggota',
            'password_confirmation' => 'password-anggota',
        ])->assertRedirect(route('dashboard'));

        $account = User::where('username', 'dewi-anggota')->firstOrFail();
        $this->assertSame('user', $account->role);
        $this->assertNull($account->member_id);
        $this->assertNull($account->member_code);
        $this->assertSame(0, Member::count());

        $this->get(route('member.dashboard'))->assertOk()->assertSee('Menunggu verifikasi petugas');
        $this->get(route('register'))->assertRedirect(route('dashboard'));

        $staff = User::factory()->create(['role' => 'petugas']);
        $this->actingAs($staff)
            ->get(route('bookings.index'))
            ->assertOk()
            ->assertSee('Verifikasi Akun Anggota')
            ->assertSee('Verifikasi & Buat Anggota', false);
        $this->patch(route('users.member-link', $account))
            ->assertRedirect(route('bookings.index'));
        $account->refresh();
        $member = $account->member;
        $this->assertNotNull($member);
        $this->assertSame('Dewi Anggota', $member->nama);
        $this->assertSame('dewi@example.test', $member->email);
        $this->assertSame('12345', $member->nis_nip);
        $this->assertSame('P', $member->jenis_kelamin);
        $this->assertSame('Jl. Anggota Baru', $member->alamat);
        $this->assertSame('08123456789', $member->telepon);
        $this->assertMatchesRegularExpression('/^MBR-\d{8}-0001$/', $member->kode_member);
        $this->assertSame(1, Member::count());
        $this->actingAs($staff)->get(route('members.index'))
            ->assertOk()
            ->assertSee('Dewi Anggota')
            ->assertSee($member->kode_member);

        $this->actingAs($account->fresh())
            ->get(route('member.dashboard'))
            ->assertOk()
            ->assertSee('Beranda Anggota');
        $this->get(route('loans.index'))->assertRedirect();
    }

    public function test_staff_approves_booking_and_records_pickup_as_a_loan(): void
    {
        $member = $this->createMember();
        $book = $this->createBook(2);
        $staff = User::factory()->create(['role' => 'admin']);
        $memberAccount = $this->registerMember($member);
        $member = $memberAccount->member;
        $this->assertSame($member->id, $memberAccount->member_id);
        $this->assertNull($memberAccount->member_code);

        $this->actingAs($memberAccount)
            ->get(route('member.books'))
            ->assertOk()
            ->assertSee('Buku Contoh')
            ->assertSee('Booking Buku');
        $this->get(route('member.bookings'))->assertOk();

        $this->actingAs($memberAccount)
            ->post(route('member.booking.store', $book))
            ->assertRedirect(route('member.bookings'));

        $booking = Booking::where('member_id', $member->id)->firstOrFail();
        $this->assertSame('menunggu', $booking->status);
        $this->assertSame(2, (int) $book->fresh()->tersedia);

        $anotherBook = $this->createBook(1);
        $this->post(route('member.booking.store', $book))
            ->assertSessionHasErrors('booking');
        $this->post(route('member.booking.store', $anotherBook))
            ->assertRedirect(route('member.bookings'));

        $thirdBook = $this->createBook(1);
        $this->post(route('member.booking.store', $thirdBook))
            ->assertRedirect(route('member.bookings'));
        $this->get(route('member.books'))
            ->assertOk()
            ->assertSee('sudah mencapai batas 3 booking aktif')
            ->assertSee('Sudah Dibooking');
        $fourthBook = $this->createBook(1);
        $this->post(route('member.booking.store', $fourthBook))
            ->assertSessionHasErrors('booking');
        $this->assertSame(3, Booking::where('member_id', $member->id)->whereIn('status', ['menunggu', 'disetujui'])->count());

        $this->actingAs($staff)
            ->patch(route('bookings.approve', $booking))
            ->assertRedirect(route('bookings.index'));
        $secondBooking = Booking::where('member_id', $member->id)->where('book_id', $anotherBook->id)->firstOrFail();
        $thirdBooking = Booking::where('member_id', $member->id)->where('book_id', $thirdBook->id)->firstOrFail();
        $this->patch(route('bookings.approve', $secondBooking))->assertRedirect(route('bookings.index'));
        $this->patch(route('bookings.approve', $thirdBooking))->assertRedirect(route('bookings.index'));

        $booking->refresh();
        $this->assertSame('disetujui', $booking->status);
        $this->assertTrue($booking->expires_at->equalTo($booking->approved_at->copy()->addHours(24)));
        $this->assertSame(1, (int) $book->fresh()->tersedia);

        $response = $this->actingAs($staff)
            ->get(route('bookings.index'))
            ->assertOk()
            ->assertSee('Dewi Anggota')
            ->assertSee('Keranjang Buku Siap Diambil')
            ->assertSee('Proses Keranjang (3 buku)')
            ->assertSee($book->kode_buku)
            ->assertSee($anotherBook->kode_buku)
            ->assertSee($thirdBook->kode_buku);
        $this->assertSame(1, substr_count($response->getContent(), 'Proses Keranjang (3 buku)'));
        $memberBookingsResponse = $this->actingAs($memberAccount)
            ->get(route('member.bookings'))
            ->assertOk()
            ->assertSee('Keranjang Booking Disetujui')
            ->assertSee($book->kode_buku)
            ->assertSee($anotherBook->kode_buku)
            ->assertSee($thirdBook->kode_buku);
        $this->assertSame(3, substr_count($memberBookingsResponse->getContent(), 'Batalkan Buku'));

        Setting::create(['key' => 'lama_pinjam', 'value' => '10']);
        $this->actingAs($staff)
            ->get(route('loans.create', ['booking_id' => $booking->id]))
            ->assertOk()
            ->assertSee('Form ini memuat semua buku yang sudah disetujui')
            ->assertSee('name="booking_ids[]"', false)
            ->assertSee('value="' . $member->id . '"', false)
            ->assertSee('value="' . $book->id . '"', false)
            ->assertSee('value="' . $anotherBook->id . '"', false)
            ->assertSee('value="' . $thirdBook->id . '"', false);

        $this->post(route('loans.store'), [
            'member_id' => $member->id,
            'booking_ids' => [$booking->id, $secondBooking->id, $thirdBooking->id],
            'books' => [
                ['book_id' => $book->id, 'kondisi' => 'baik'],
                ['book_id' => $anotherBook->id, 'kondisi' => 'baik'],
                ['book_id' => $thirdBook->id, 'kondisi' => 'baik'],
            ],
        ])->assertRedirect();

        $booking->refresh();
        $secondBooking->refresh();
        $thirdBooking->refresh();
        $loan = Loan::where('member_id', $member->id)->firstOrFail();
        $this->assertSame('diambil', $booking->status);
        $this->assertSame('diambil', $secondBooking->status);
        $this->assertSame('diambil', $thirdBooking->status);
        $this->assertSame($loan->id, $booking->loan_id);
        $this->assertSame($loan->id, $secondBooking->loan_id);
        $this->assertSame($loan->id, $thirdBooking->loan_id);
        $this->assertSame(3, Loan_detail::where('loan_id', $loan->id)->count());
        $this->assertSame(1, Loan::where('member_id', $member->id)->whereIn('status', ['dipinjam', 'terlambat'])->count());
        $this->assertSame(today()->addDays(10)->toDateString(), $loan->tanggal_kembali->toDateString());
        $this->assertSame(1, (int) $book->fresh()->tersedia);
        $this->assertSame(0, (int) $anotherBook->fresh()->tersedia);
        $this->assertSame(0, (int) $thirdBook->fresh()->tersedia);
    }

    public function test_member_booking_limit_uses_the_configured_loan_limit(): void
    {
        Setting::create(['key' => 'maksimal_buku_dipinjam', 'value' => '2']);
        $memberAccount = $this->registerMember($this->createMember());
        $firstBook = $this->createBook(1);
        $secondBook = $this->createBook(1);
        $thirdBook = $this->createBook(1);

        $this->actingAs($memberAccount)
            ->get(route('member.books'))
            ->assertOk()
            ->assertSee('Booking maksimal 2 buku berbeda');

        $this->post(route('member.booking.store', $firstBook))->assertRedirect(route('member.bookings'));
        $this->post(route('member.booking.store', $secondBook))->assertRedirect(route('member.bookings'));
        $this->post(route('member.booking.store', $thirdBook))->assertSessionHasErrors('booking');
        $this->assertSame(2, Booking::where('member_id', $memberAccount->member_id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->count());
    }

    public function test_pending_booking_reduces_visible_stock_for_members(): void
    {
        $member = $this->createMember();
        $book = $this->createBook(2);
        $memberAccount = $this->registerMember($member);

        $this->actingAs($memberAccount)->post(route('member.booking.store', $book));

        $response = $this->actingAs($memberAccount)->get(route('member.books'));
        $response->assertOk()->assertSee('Tersedia: 1');
        $this->assertSame(2, (int) $book->fresh()->tersedia);
        $this->assertCount(1, Booking::where('book_id', $book->id)->where('status', 'menunggu')->get());
    }

    public function test_approved_booking_releases_held_stock_after_24_hours(): void
    {
        $member = $this->createMember();
        $book = $this->createBook(1);
        $staff = User::factory()->create(['role' => 'admin']);
        $memberAccount = $this->registerMember($member);
        $member = $memberAccount->member;

        $this->actingAs($memberAccount)->post(route('member.booking.store', $book));
        $booking = Booking::where('member_id', $member->id)->firstOrFail();
        $this->actingAs($staff)->patch(route('bookings.approve', $booking));

        $this->travel(25)->hours();
        $this->actingAs($memberAccount)->get(route('member.bookings'))->assertOk();

        $this->assertSame('kedaluwarsa', $booking->fresh()->status);
        $this->assertSame(1, (int) $book->fresh()->tersedia);
    }

    private function registerMember(Member $member): User
    {
        $this->post(route('register.store'), [
            'name' => $member->nama,
            'username' => 'dewi-' . $member->id,
            'email' => $member->email,
            'nis_nip' => $member->nis_nip,
            'jenis_kelamin' => $member->jenis_kelamin,
            'alamat' => $member->alamat,
            'telepon' => $member->telepon,
            'password' => 'password-anggota',
            'password_confirmation' => 'password-anggota',
        ]);

        $account = User::where('username', 'dewi-' . $member->id)->firstOrFail();
        $staff = User::factory()->create(['role' => 'petugas']);
        $this->actingAs($staff)->patch(route('users.member-link', $account))->assertRedirect(route('bookings.index'));

        return $account->fresh();
    }

    private function createMember(): Member
    {
        return Member::create([
            'kode_member' => 'MBR-' . fake()->unique()->numerify('#####'),
            'nama' => 'Dewi Anggota',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Contoh',
            'telepon' => '08123456789',
            'email' => fake()->unique()->safeEmail(),
            'tanggal_daftar' => today(),
            'status' => 'aktif',
        ]);
    }

    private function createBook(int $available): Book
    {
        $category = Category::create([
            'kode_kategori' => 'CAT-' . fake()->unique()->numerify('###'),
            'nama_kategori' => 'Fiksi',
        ]);
        $publisher = Publisher::create([
            'kode_penerbit' => 'PUB-' . fake()->unique()->numerify('###'),
            'nama_penerbit' => 'Penerbit',
        ]);
        $author = Author::create([
            'kode_penulis' => 'AUT-' . fake()->unique()->numerify('###'),
            'nama_penulis' => 'Penulis',
        ]);
        $shelf = Shelf::create([
            'kode_rak' => 'RAK-' . fake()->unique()->numerify('###'),
            'nama_rak' => 'Rak A',
            'lokasi' => 'Lantai 1',
        ]);

        return Book::create([
            'kode_buku' => 'BK-' . fake()->unique()->numerify('#####'),
            'judul' => 'Buku Contoh',
            'category_id' => $category->id,
            'publisher_id' => $publisher->id,
            'author_id' => $author->id,
            'shelf_id' => $shelf->id,
            'tahun_terbit' => 2024,
            'jumlah' => $available,
            'tersedia' => $available,
        ]);
    }
}
