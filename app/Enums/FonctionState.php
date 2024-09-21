<?php

namespace App\Enums;

enum FonctionState: string
{
    case ADMIN = 'ADMIN';
    case MANAGER = 'MANAGER';
    case CM = 'CM';
    case COACH = 'COACH';
}