<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Loan;
use App\Models\Fine;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->role === 'user') {
            return redirect()->route('member.dashboard');
        }

        $data = [
            'totalBooks' => Book::sum('jumlah'),
            'totalMembers' => Member::count(),
            'activeLoans' => Loan::where('status', 'dipinjam')->count(),
            'unpaidFines' => Fine::where('status', 'belum_bayar')->count(),
            'recentActivities' => Loan::with('member')->latest()->take(5)->get(),
            'returnsToday' => Loan::whereDate('tanggal_dikembalikan', today())->count(),
            'availableBooks' => Book::sum('tersedia'),
        ];

        if ($user && $user->role === 'admin') {
            return view('admin.dashboard.index', $data);
        }

        return view('petugas.dashboard.index', $data);
    }
}