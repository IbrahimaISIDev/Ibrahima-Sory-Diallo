<?php

namespace App\Models;

use App\Enums\EtatPromotion;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Interfaces\PromotionFirebaseInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromotionFirebase extends FirebaseModel implements PromotionFirebaseInterface
{
    use HasFactory, SoftDeletes;
    protected $path = 'promotions';

    protected $fillable = [
        'libelle',
        'date_debut',
        'date_fin',
        'duree',
        'etat',
        'photo_couverture',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'etat' => EtatPromotion::class,
    ];

    public function getEncours()
    {
        $allPromotions = $this->all();
        return array_filter($allPromotions, function ($promotion) {
            return isset($promotion['etat']) && $promotion['etat'] === EtatPromotion::ACTIF->value;
        });
    }

    // Adjust the calculerDuree method to handle potential null values
    public function calculerDuree()
    {
        $dateDebut = $this->date_debut;
        $dateFin = $this->date_fin;

        if ($dateDebut && $dateFin) {
            return \Carbon\Carbon::parse($dateDebut)->diffInMonths(\Carbon\Carbon::parse($dateFin));
        }
        return null;
    }

    // Adjust the calculerDateFin method to handle potential null values
    public function calculerDateFin()
    {
        $dateDebut = $this->date_debut;
        $duree = $this->duree;

        if ($dateDebut && $duree) {
            return \Carbon\Carbon::parse($dateDebut)->addMonths($duree);
        }
        return null;
    }

    // Add a safe getter method
    public function getAttribute($key)
    {
        $data = $this->reference->getValue();
        return $data[$key] ?? null;
    }

    // Override the magic __get method to use the safe getter
    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    public function findByName($name)
    {
        $promotions = $this->all();
        return array_filter($promotions, function ($promotion) use ($name) {
            return isset($promotion['name']) && $promotion['name'] === $name;
        });
    }

    public function updatePromotion($id, array $newDetails)
    {
        $promotion = $this->find($id);
        if (!$promotion) {
            return null;
        }
        $promotionArray = (array) $promotion;

        $updatedPromotion = array_merge($promotionArray, $newDetails);
        $this->reference->getChild($id)->set($updatedPromotion);

        return $updatedPromotion;
    }
}
