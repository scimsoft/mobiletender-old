<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use App\Services\ShopBasketData;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.shop', function ($view) {
            $data = $view->getData();
            if (! array_key_exists('totalBasketPrice', $data)) {
                $view->with('totalBasketPrice', app(ShopBasketData::class)->totalBasketPriceExTax());
            }
            if (! array_key_exists('basketItemCount', $data)) {
                $view->with('basketItemCount', app(ShopBasketData::class)->lineCountWithProducts());
            }
        });

        Blade::directive('money', function ($amount) {
            return "<?php echo number_format($amount, 2).'€'; ?>";
        });
        Paginator::useTailwind();
    }
}
