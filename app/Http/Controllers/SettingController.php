<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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
        $data = $request->validate([
            'nama_perpustakaan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string',
            'email' => 'nullable|email',
            'denda_per_hari' => 'required|numeric|min:0',
            'lama_pinjam' => 'required|integer|min:1',
            'logo' => 'nullable|image|max:2048',
        ]);

        foreach ($data as $key => $value) {
            if ($key === 'logo') continue;
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if ($request->hasFile('logo')) {
            Setting::updateOrCreate(['key' => 'logo'], [
                'value' => $request->file('logo')->store('settings', 'public'),
            ]);
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
