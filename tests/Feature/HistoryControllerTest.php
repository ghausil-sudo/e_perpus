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
        ];

        $this->post(route('settings.store'), $settings + ['denda_per_hari' => '10.000'])
            ->assertRedirect(route('settings.index'));
        $this->post(route('settings.store'), $settings + ['denda_per_hari' => '15.000'])
            ->assertRedirect(route('settings.index'));

        $this->assertSame(2, History::where('jenis', 'perubahan_tarif_denda')->count());
        $this->assertDatabaseHas('settings', [
            'key' => 'denda_per_hari',
            'value' => '15000',
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
}
