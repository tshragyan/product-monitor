<?php

namespace App\Listeners;

use App\Events\UserCreatedEvent;
use App\Mail\ProductPriceEmail;
use App\Mail\VerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class UserCreatedEventListener
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
    public function handle(UserCreatedEvent $event): void
    {
            Mail::to($event->user->email)->send(new VerifyEmail($event->user));
    }
}
