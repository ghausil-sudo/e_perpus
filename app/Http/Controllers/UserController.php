<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%");
        })->latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,petugas',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('password', 'password_confirmation', 'foto');
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('users', 'public');
        }

        User::create($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $members = Member::where('status', 'aktif')
            ->where(function ($query) use ($user) {
                $query->whereDoesntHave('user')
                    ->when($user->member_id, fn ($memberQuery) => $memberQuery->orWhereKey($user->member_id));
            })
            ->orderBy('nama')
            ->get();

        return view('users.edit', compact('user', 'members'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,petugas,user',
            'member_id' => 'nullable|exists:members,id|unique:users,member_id,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('password', 'password_confirmation', 'foto');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto && \Storage::disk('public')->exists($user->foto)) {
                \Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('users', 'public');
        }

        if ($request->role === 'user' && $request->filled('member_id')) {
            $member = Member::whereKey($request->member_id)->firstOrFail();
            if (
                $user->role !== 'user'
                || ((int) $user->member_id !== (int) $member->id && $user->member_code !== $member->kode_member)
                || strtolower((string) $user->email) !== strtolower((string) $member->email)
                || $member->status !== 'aktif'
            ) {
                return back()->withErrors([
                    'member_id' => 'Kode anggota dan email harus sesuai dengan data anggota aktif.',
                ])->withInput();
            }
            $data['member_code'] = null;
        } elseif ($request->role === 'user') {
            if ($user->role !== 'user' || !$user->member_id) {
                return back()->withErrors([
                    'member_id' => 'Akun anggota harus terhubung ke data anggota yang sesuai.',
                ])->withInput();
            }
            $data['member_id'] = $user->member_id;
        } else {
            $data['member_id'] = null;
            $data['member_code'] = null;
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function linkMember(User $user)
    {
        DB::transaction(function () use ($user) {
            $user = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            if ($user->role !== 'user' || $user->member_id !== null) {
                throw ValidationException::withMessages([
                    'member_verification' => 'Akun ini bukan akun anggota yang menunggu verifikasi.',
                ]);
            }

            $member = null;

            if ($user->member_jenis_kelamin && $user->member_alamat && $user->member_telepon) {
                $member = Member::where('email', $user->email)
                    ->where('nama', $user->name)
                    ->where('status', 'aktif')
                    ->whereDoesntHave('user')
                    ->lockForUpdate()
                    ->first();

                if (!$member) {
                    $member = Member::createWithGeneratedCode([
                        'nama' => $user->name,
                        'nis_nip' => $user->member_nis_nip,
                        'jenis_kelamin' => $user->member_jenis_kelamin,
                        'alamat' => $user->member_alamat,
                        'telepon' => $user->member_telepon,
                        'email' => $user->email,
                        'tanggal_daftar' => today(),
                        'status' => 'aktif',
                    ]);
                }
            } else {
                $member = Member::where('kode_member', $user->member_code)
                    ->where('email', $user->email)
                    ->where('status', 'aktif')
                    ->whereDoesntHave('user')
                    ->lockForUpdate()
                    ->first();
            }

            if (!$member) {
                throw ValidationException::withMessages([
                    'member_verification' => 'Data anggota belum lengkap atau tidak cocok. Periksa data pendaftaran sebelum memverifikasi.',
                ]);
            }

            $user->update([
                'member_id' => $member->id,
                'member_code' => null,
                'member_nis_nip' => null,
                'member_jenis_kelamin' => null,
                'member_alamat' => null,
                'member_telepon' => null,
            ]);
        });

        return redirect()->route('bookings.index')->with('success', 'Akun anggota berhasil diverifikasi dan dihubungkan.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang login.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
