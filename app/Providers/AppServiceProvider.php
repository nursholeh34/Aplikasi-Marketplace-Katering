<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Pemakaian di Blade: @rp($menu->price)  =>  Rp 28.000
        Blade::directive('rp', function ($expression) {
            return "<?php echo 'Rp '.number_format((float) ({$expression}), 0, ',', '.'); ?>";
        });
    }
}
