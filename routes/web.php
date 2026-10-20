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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('books', BookController::class);
    Route::resource('members', MemberController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('publishers', PublisherController::class);
    Route::resource('shelves', ShelfController::class);

    Route::resource('loans', LoanController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::post('returns/{loan}', [ReturnController::class, 'store'])->name('returns.store');
    Route::get('fines', [FineController::class, 'index'])->name('fines.index');
    Route::patch('fines/{fine}', [FineController::class, 'update'])->name('fines.update');
    Route::get('histories', [HistoryController::class, 'index'])->name('histories.index');
    Route::post('histories/expenses', [HistoryController::class, 'storeExpense'])->name('histories.expenses.store');

    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('settings', SettingController::class)->only(['index', 'store']);
    });
});
