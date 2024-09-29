<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\UserFirebase;
use App\Interfaces\ApprenantsModelInterface;

class ApprenantFirebase extends UserFirebase implements ApprenantsModelInterface
{
    protected $path = 'apprenants';

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'email',
        'adresse',
        'telephone',
        'password',
        'photo_couverture',
        'referentiel',
        'statut'
    ];
    
    public function genererMatricule()
    {
        return 'APRENANT-N°' . date('Y') . '-' . Str::random(5);
    }

    public function genererCodeQR()
    {
        // Implémentation de la génération du code QR
        return 'QR-' . Str::random(10);
    }
}
