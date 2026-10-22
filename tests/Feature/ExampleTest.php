<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Panduan Penggunaan')
            ->assertSee('Peraturan Perpustakaan')
            ->assertSee('Login')
            ->assertSee('Daftar Akun Anggota')
            ->assertSee(route('login'), false)
            ->assertSee(route('register'), false)
            ->assertDontSee('Masuk Petugas');
    }

    public function test_landing_page_shows_configured_loan_period_and_fine_rates(): void
    {
        Setting::create(['key' => 'nama_perpustakaan', 'value' => 'Perpustakaan Contoh']);
        Setting::create(['key' => 'lama_pinjam', 'value' => '10']);
        Setting::create(['key' => 'denda_per_hari', 'value' => '15000']);
        Setting::create(['key' => 'denda_rusak_ringan', 'value' => '5000']);
        Setting::create(['key' => 'denda_rusak_berat', 'value' => '10000']);
        Setting::create(['key' => 'denda_hilang', 'value' => '20000']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Perpustakaan Contoh')
            ->assertSee('10 hari')
            ->assertSee('Rp 15.000')
            ->assertSee('Rp 5.000')
            ->assertSee('Rp 10.000')
            ->assertSee('Rp 20.000');
    }
}
