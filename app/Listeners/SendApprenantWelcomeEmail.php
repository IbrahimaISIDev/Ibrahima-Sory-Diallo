<?php

namespace App\Listeners;

use App\Events\ApprenantCreated;
use App\Jobs\SendWelcomeEmail;

class SendApprenantWelcomeEmail
{
    public function handle(ApprenantCreated $event)
    {
        SendWelcomeEmail::dispatch($event->apprenant->id);
    }
}