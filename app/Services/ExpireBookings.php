<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class ExpireBookings
{
    public function handle(): void
    {
        Booking::query()
            ->where('status', 'disetujui')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->orderBy('id')
            ->pluck('id')
            ->each(function ($id) {
                DB::transaction(function () use ($id) {
                    $booking = Booking::whereKey($id)->lockForUpdate()->first();
                    if (!$booking || $booking->status !== 'disetujui' || $booking->expires_at->isFuture()) {
                        return;
                    }

                    $book = Book::whereKey($booking->book_id)->lockForUpdate()->first();
                    if ($book) {
                        $book->tersedia = min($book->jumlah, $book->tersedia + 1);
                        $book->save();
                    }

                    $booking->update(['status' => 'kedaluwarsa']);
                });
            });
    }
}
