<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Jalur ke "home" dari aplikasi kamu.
     *
     * Ini biasanya digunakan setelah autentikasi berhasil.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Boot method
     */
    public function boot()
    {
        $this->routes(function () {
            // Route untuk API
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // Route untuk Web
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
