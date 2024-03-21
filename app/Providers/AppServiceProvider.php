<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ParseServices\Contracts\FileParseContract;
use App\Services\ParseServices\CsvParser;
use App\Services\HandleDataServices\HandleDataService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton(FileParseContract::class, function ($app) {
            return new CsvParser(new HandleDataService);
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
    }
}
