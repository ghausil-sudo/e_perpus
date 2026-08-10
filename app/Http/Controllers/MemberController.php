<?php
namespace App\Http\Controllers;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::latest()->paginate(10);
        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis_nip' => 'nullable|string',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'nullable|email',
        ]);

        $kode = 'MBR-' . date('Ymd') . '-' . str_pad(Member::count() + 1, 4, '0', STR_PAD_LEFT);

        Member::create(array_merge($request->all(), [
            'kode_member' => $kode,
            'tanggal_daftar' => today(),
            'status' => 'aktif'
        ]));

        return redirect()->route('members.index')->with('success', 'Anggota baru berhasil ditambahkan.');
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate(['nama' => 'required|string|max:255']);
        $member->update($request->all());
        return redirect()->route('members.index')->with('success', 'Data anggota diperbarui.');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Anggota dihapus.');
    }
}