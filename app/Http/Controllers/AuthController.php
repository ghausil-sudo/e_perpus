<?php
namespace App\Http\Controllers;

use App\Models\History;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'nis_nip' => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data['email'] = strtolower($data['email']);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'member_nis_nip' => $data['nis_nip'] ?? null,
            'member_jenis_kelamin' => $data['jenis_kelamin'],
            'member_alamat' => $data['alamat'],
            'member_telepon' => $data['telepon'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat. Petugas akan memverifikasi data Anda dan membuat kode anggota sebelum booking dapat dilakukan.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $loginType => $request->login,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            History::create([
                'jenis' => 'aktivitas',
                'judul' => 'Login',
                'keterangan' => 'Pengguna ' . Auth::user()->name . ' masuk ke aplikasi.',
                'user_id' => Auth::id(),
            ]);

            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang kembali!');
        }

        return back()->with('error', 'Username/Email atau Password salah.')->withInput();
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        History::create([
            'jenis' => 'aktivitas',
            'judul' => 'Logout',
            'keterangan' => 'Pengguna ' . $user->name . ' keluar dari aplikasi.',
            'user_id' => $user->id,
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}