<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\EtatReferentiel;
use App\Interfaces\ReferentielFirebaseInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReferentielFirebase extends FirebaseModel implements ReferentielFirebaseInterface
{
    use HasFactory, SoftDeletes;

    protected $path ='referentiels';

    protected $fillable = [
        'code',
        'libelle',
        'description',
        'photo_couverture',
        'etat',
    ];

    protected $casts = [
        'etat' => EtatReferentiel::class,
    ];

    public function competences()
    {
    }

    public function modules()
    {
    }

    public function findByCode($code)
    {
        $referentiels = $this->all();
        return collect($referentiels)->firstWhere('code', $code);
    }


    public function updateReferentiel($id, array $newDetails)
    {
        $referentiel = $this->find($id);
        if (!$referentiel) {
            return null;
        }

        $updatedReferentiel = array_merge($referentiel, $newDetails);
        $this->reference->getChild($id)->set($updatedReferentiel);

        return $updatedReferentiel;
    }
}
