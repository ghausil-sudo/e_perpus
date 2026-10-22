<?php

namespace Tests\Feature;

use App\Models\History;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HistoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_records_changes_to_the_daily_fine_rate(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $settings = [
            'nama_perpustakaan' => 'Perpustakaan',
            'alamat' => 'Bandung',
            'telepon' => '08123456789',
            'email' => 'perpus@example.com',
            'lama_pinjam' => 7,
            'denda_rusak_ringan' => '5.000',
            'denda_rusak_berat' => '10.000',
            'denda_hilang' => '20.000',
        ];

        $this->post(route('settings.store'), $settings + ['denda_per_hari' => '10.000'])
            ->assertRedirect(route('settings.index'));
        $this->post(route('settings.store'), $settings + ['denda_per_hari' => '15.000'])
            ->assertRedirect(route('settings.index'));

        $this->assertSame(2, History::where('jenis', 'perubahan_tarif_denda')
            ->where('keterangan', 'Denda keterlambatan per hari')
            ->count());
        $this->assertDatabaseHas('settings', [
            'key' => 'denda_per_hari',
            'value' => '15000',
        ]);
        $this->assertDatabaseHas('settings', [
            'key' => 'denda_rusak_ringan',
            'value' => '5000',
        ]);
        $this->assertDatabaseHas('settings', [
            'key' => 'denda_rusak_berat',
            'value' => '10000',
        ]);
        $this->assertDatabaseHas('settings', [
            'key' => 'denda_hilang',
            'value' => '20000',
        ]);
        $this->assertDatabaseHas('histories', [
            'jenis' => 'perubahan_tarif_denda',
            'nilai_lama' => '10000',
            'nilai_baru' => '15000',
            'user_id' => $admin->id,
        ]);
    }

    public function test_it_records_manual_cash_expenses_and_displays_history(): void
    {
        $user = User::factory()->create(['role' => 'petugas']);
        $this->actingAs($user);

        $this->post(route('histories.expenses.store'), [
            'keterangan' => 'Pembelian alat tulis',
            'jumlah' => 25000,
        ])->assertRedirect(route('histories.index'));

        $this->assertDatabaseHas('histories', [
            'jenis' => 'kas_keluar',
            'keterangan' => 'Pembelian alat tulis',
            'jumlah' => 25000,
            'user_id' => $user->id,
        ]);

        $this->get(route('histories.index'))
            ->assertOk()
            ->assertSee('Pembelian alat tulis')
            ->assertSee('Kas Keluar');
    }

    public function test_it_records_catalog_creation_update_and_deletion(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        $category = Category::create([
            'kode_kategori' => 'CTG001',
            'nama_kategori' => 'Programming',
            'deskripsi' => 'Programming books',
        ]);
        $category->update(['nama_kategori' => 'Teknologi']);
        $category->delete();

        $this->assertSame(3, History::where('jenis', 'aktivitas')
            ->where('keterangan', 'like', '%Kategori%')
            ->count());
        $this->assertDatabaseHas('histories', [
            'jenis' => 'aktivitas',
            'judul' => 'Kategori Diperbarui',
            'user_id' => $user->id,
        ]);
    }

    public function test_history_page_only_displays_requested_activity_types(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        foreach ([
            'peminjaman' => 'Riwayat peminjaman tampil',
            'pengembalian' => 'Riwayat pengembalian tampil',
            'perubahan_tarif_denda' => 'Riwayat perubahan denda tampil',
            'kas_keluar' => 'Riwayat kas keluar tampil',
            'kas_masuk' => 'Riwayat kas masuk tampil',
            'aktivitas' => 'Riwayat aktivitas tidak tampil',
        ] as $jenis => $keterangan) {
            History::create([
                'jenis' => $jenis,
                'judul' => $keterangan,
                'keterangan' => $keterangan,
                'user_id' => $user->id,
            ]);
        }

        $response = $this->get(route('histories.index'))->assertOk();

        $response
            ->assertSee('Riwayat peminjaman tampil')
            ->assertSee('Riwayat pengembalian tampil')
            ->assertSee('Riwayat perubahan denda tampil')
            ->assertSee('Riwayat kas keluar tampil')
            ->assertSee('Riwayat kas masuk tampil')
            ->assertDontSee('Riwayat aktivitas tidak tampil');
    }

    public function test_navigation_shows_cash_balance_from_incoming_and_outgoing_cash(): void
    {
        $user = User::factory()->create(['role' => 'petugas']);
        $this->actingAs($user);

        History::create([
            'jenis' => 'kas_masuk',
            'judul' => 'Pembayaran denda',
            'keterangan' => 'Pembayaran masuk',
            'jumlah' => 75000,
            'user_id' => $user->id,
        ]);
        History::create([
            'jenis' => 'kas_keluar',
            'judul' => 'Pengeluaran kas',
            'keterangan' => 'Pembelian alat tulis',
            'jumlah' => 12500,
            'user_id' => $user->id,
        ]);

        $this->get(route('histories.index'))
            ->assertOk()
            ->assertSee('Saldo kas', false)
            ->assertSee('Rp 62.500');
    }
}
