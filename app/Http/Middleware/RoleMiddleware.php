<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Role yang diizinkan (bisa lebih dari satu)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = Auth::user()->role;

        // 2. Cek apakah role user termasuk dalam daftar role yang diizinkan
        if (!in_array($userRole, $roles)) {
            // Jika akses via AJAX/API, kembalikan response 403
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Anda tidak memiliki izin.'
                ], 403);
            }

            // Jika akses via web, redirect kembali dengan pesan error
            return redirect()->back()->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman tersebut.');
        }

        return $next($request);
    }
}