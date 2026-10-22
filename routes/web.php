<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MemberPortalController;

Route::get('/', function () {
    $settings = \App\Models\Setting::query()
        ->whereIn('key', [
            'nama_perpustakaan',
            'lama_pinjam',
            'denda_per_hari',
            'denda_rusak_ringan',
            'denda_rusak_berat',
            'denda_hilang',
            'logo',
            'telepon',
            'email',
        ])
        ->pluck('value', 'key');

    return view('welcome', compact('settings'));
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:user')->prefix('anggota')->name('member.')->group(function () {
        Route::get('/', [MemberPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/buku', [MemberPortalController::class, 'books'])->name('books');
        Route::get('/booking', [MemberPortalController::class, 'bookings'])->name('bookings');
        Route::post('/buku/{book}/booking', [BookingController::class, 'store'])->name('booking.store');
        Route::delete('/booking/{booking}', [BookingController::class, 'cancel'])->name('booking.cancel');
    });

    Route::middleware('role:admin,petugas')->group(function () {
        Route::resource('books', BookController::class);
        Route::resource('members', MemberController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('authors', AuthorController::class);
        Route::resource('publishers', PublisherController::class);
        Route::resource('shelves', ShelfController::class);

        Route::resource('loans', LoanController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('loans/{loan}/receipt', [LoanController::class, 'receipt'])->name('loans.receipt');
        Route::get('returns', [ReturnController::class, 'index'])->name('returns.index');
        Route::post('returns/{loan}', [ReturnController::class, 'store'])->name('returns.store');
        Route::get('fines', [FineController::class, 'index'])->name('fines.index');
        Route::patch('fines/{fine}', [FineController::class, 'update'])->name('fines.update');
        Route::get('histories', [HistoryController::class, 'index'])->name('histories.index');
        Route::post('histories/expenses', [HistoryController::class, 'storeExpense'])->name('histories.expenses.store');
        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::patch('bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
        Route::patch('bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
        Route::patch('users/{user}/member-link', [UserController::class, 'linkMember'])->name('users.member-link');

        Route::middleware('role:admin')->group(function () {
            Route::resource('users', UserController::class);
            Route::resource('settings', SettingController::class)->only(['index', 'store']);
        });
    });
});
