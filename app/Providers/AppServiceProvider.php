<?php

namespace App\Providers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Member;
use App\Models\Publisher;
use App\Models\Shelf;
use App\Models\User;
use App\Observers\HistoryObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ([Book::class, Member::class, Category::class, Author::class, Publisher::class, Shelf::class, User::class] as $model) {
            $model::observe(HistoryObserver::class);
        }
    }
}
