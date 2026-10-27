<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    DB::listen(function (QueryExecuted $query) {
        if ($query->time >= 100) {
            Log::warning('pos.database.slow_query', [
                'duration_ms' => $query->time,
                'sql' => $query->sql,
                'connection' => $query->connectionName,
            ]);
        }
    });
    }
}
