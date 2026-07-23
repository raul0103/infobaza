<?php

namespace App\Providers;

use App\Support\Markdown;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('ru');
        Paginator::defaultView('vendor.pagination.tailwind');
        Markdown::registerBlade();
    }
}
