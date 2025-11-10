<?php

namespace App\Providers;

use App\Channels\WhatsappChannel;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Notification;
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
        Paginator::useBootstrap();

        Blade::directive('money', function ($expression) {
            return "Rp. <?php echo number_format($expression,0,',','.'); ?>";
        });

        // Register WhatsApp notification channel
        Notification::extend('whatsapp', function ($app) {
            return $app->make(WhatsappChannel::class);
        });
    }
}
