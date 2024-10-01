<?php

namespace App\Models;

use App\Interfaces\EmergementFirebaseInterface;

class Emargement extends FirebaseModel implements EmergementFirebaseInterface
{
    protected $path = 'emargements';

    protected $fillable = [
        'apprenant_id',
        'date',
        'heure_entree',
        'heure_sortie',
        'statut',
    ];

    protected $casts = [
        'date' => 'date',
        'heure_entree' => 'datetime',
        'heure_sortie' => 'datetime',
    ];
}