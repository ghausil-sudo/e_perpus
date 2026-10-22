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
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'nis_nip' => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        Member::createWithGeneratedCode(array_merge($data, [
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
        if ($member->bookings()->whereIn('status', ['menunggu', 'disetujui'])->exists()) {
            return back()->with('error', 'Tidak dapat menghapus anggota karena masih memiliki booking aktif.');
        }

        if ($member->loans()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus anggota karena memiliki riwayat peminjaman.');
        }

        $member->delete();
        return redirect()->route('members.index')->with('success', 'Anggota dihapus.');
    }
}