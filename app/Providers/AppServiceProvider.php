<?php

namespace App\Providers;

use App\Events\ProductMonitoredEvent;
use App\Events\UserCreatedEvent;
use App\Listeners\ProductMonitoredEventListener;
use App\Listeners\UserCreatedEventListener;
use Illuminate\Support\Facades\Event;
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
        Event::listen(
            ProductMonitoredEvent::class,
            ProductMonitoredEventListener::class
        );

        Event::listen(
            UserCreatedEvent::class,
            UserCreatedEventListener::class
        );
    }
}
