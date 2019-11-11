<?php

namespace App;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function map()
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}
