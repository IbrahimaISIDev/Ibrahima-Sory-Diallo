<?php
namespace App\Models;

use App\Interfaces\ReferentielFirebaseInterface;

class ReferentielFirebase extends FirebaseModel implements ReferentielFirebaseInterface
{
    protected $path = 'referentiels';
}