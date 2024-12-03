<?php

namespace App\Providers;

use App\Models\Menus;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
         View::composer('components.templates.default', function ($view) {
            $menus = Menus::where('is_active', true)
                ->orderBy('order', 'asc')
                ->with('children')
                ->get();
            $view->with('menus', $menus);
        });
    }
}
