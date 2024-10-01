<?php

namespace App\Events;

use App\Models\UserMysql;
use Illuminate\Foundation\Events\Dispatchable;

class UserCreated
{
    use Dispatchable;

    public $userMysql;
    public $userFirebase;

    public function __construct(UserMysql $userMysql, $userFirebase)
    {
        $this->userMysql = $userMysql;
        $this->userFirebase = $userFirebase;
    }
}
