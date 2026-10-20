<?php
namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
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