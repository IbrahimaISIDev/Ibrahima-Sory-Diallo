<?php

namespace App\Jobs;

use App\Facades\ApprenantsFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Notifications\WelcomeApprenant;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $apprenantId;

    public function __construct($apprenantId)
    {
        $this->apprenantId = $apprenantId;
    }

    public function handle()
    {
        $apprenant = ApprenantsFacade::find($this->apprenantId);
        Notification::send($apprenant, new WelcomeApprenant($apprenant));
    }

}
