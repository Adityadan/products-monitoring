<?php

namespace App\Providers;

use App\Models\Menus;
use Illuminate\Support\Facades\View;
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
        // View::composer('components.templates.default', function ($view) {
        //     $menus = Menus::where('is_active', true)
        //         ->orderBy('order', 'asc')
        //         ->with('children')
        //         ->get();
        //     $view->with('menus', $menus);
        // });
    }
}
