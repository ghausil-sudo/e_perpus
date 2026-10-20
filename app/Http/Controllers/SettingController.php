<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        $grouped = Setting::all()->groupBy('key');
        $settings = [];

        foreach ($grouped as $key => $items) {
            $settings[$key] = $items->count() > 1 ? $items->pluck('value', 'id') : $items->first()->value ?? null;
        }

        return view('settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $dendaPerHari = $request->input('denda_per_hari');
        if (is_string($dendaPerHari)) {
            $request->merge([
                'denda_per_hari' => str_replace('.', '', $dendaPerHari),
            ]);
        }

        $data = $request->validate([
            'nama_perpustakaan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string',
            'email' => 'nullable|email',
            'denda_per_hari' => 'required|numeric|min:0',
            'lama_pinjam' => 'required|integer|min:1',
            'logo' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                if ($key === 'logo') {
                    continue;
                }

                $setting = Setting::firstOrNew(['key' => $key]);
                $oldValue = $setting->exists ? $setting->value : null;
                $setting->value = $value;
                $setting->save();

                $valueChanged = $oldValue === null || (string) $oldValue !== (string) $value;
                if (!$valueChanged) {
                    continue;
                }

                if ($key === 'denda_per_hari') {
                    History::create([
                        'jenis' => 'perubahan_tarif_denda',
                        'judul' => 'Tarif denda per hari diubah',
                        'keterangan' => 'Denda keterlambatan',
                        'nilai_lama' => $oldValue,
                        'nilai_baru' => $value,
                        'user_id' => Auth::id(),
                    ]);
                } else {
                    History::create([
                        'jenis' => 'aktivitas',
                        'judul' => 'Pengaturan diubah',
                        'keterangan' => 'Pengaturan "' . $key . '" diperbarui.',
                        'user_id' => Auth::id(),
                    ]);
                }
            }
        });

        if ($request->hasFile('logo')) {
            Setting::updateOrCreate(['key' => 'logo'], [
                'value' => $request->file('logo')->store('settings', 'public'),
            ]);

            History::create([
                'jenis' => 'aktivitas',
                'judul' => 'Logo diperbarui',
                'keterangan' => 'Logo perpustakaan diperbarui.',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
