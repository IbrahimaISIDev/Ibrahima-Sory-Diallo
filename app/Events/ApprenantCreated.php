<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApprenantCreated
{
    use Dispatchable, SerializesModels;

    public $apprenant;

    public function __construct($apprenant)
    {
        $this->apprenant = $apprenant;
    }
}