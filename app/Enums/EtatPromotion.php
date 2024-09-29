<?php

namespace App\Enums;

enum EtatPromotion: string
{
    case ACTIF = 'actif';
    case CLOTURER = 'cloturer';
    case INACTIF = 'inactif';
}