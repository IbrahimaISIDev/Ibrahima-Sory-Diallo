<?php

namespace App\Enums;

enum EtatReferentiel: string
{
    case ACTIF = 'actif';
    case INACTIF = 'inactif';
    case ARCHIVE = 'archive';
}