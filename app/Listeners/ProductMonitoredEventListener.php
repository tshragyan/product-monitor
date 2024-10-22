<?php

namespace App\Listeners;

use App\Events\ProductMonitoredEvent;
use App\Mail\ProductPriceEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ProductMonitoredEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductMonitoredEvent $event): void
    {
        foreach ($event->product->users as $user) {
            Mail::to($user->email)->send(new ProductPriceEmail($event->product));
        }
    }
}
