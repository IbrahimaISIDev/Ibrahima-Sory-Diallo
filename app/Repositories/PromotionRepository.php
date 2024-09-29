<?php

namespace App\Repositories;

use App\Enums\EtatPromotion;
use App\Facades\PromotionFacade;
use App\Interfaces\PromotionRepositoryInterface;

class PromotionRepository implements PromotionRepositoryInterface
{
    public function getAllPromotions()
    {
        return PromotionFacade::all();
    }

    public function getPromotionById($id)
    {
        return PromotionFacade::with(['apprenants', 'referentiels'])->find($id);
    }

    public function createPromotion(array $promotionDetails)
    {
        return PromotionFacade::create($promotionDetails);
    }

    public function updatePromotion($id, array $newDetails)
    {
        return PromotionFacade::updatePromotion($id, $newDetails);
    }

    public function deletePromotion($id)
    {
        PromotionFacade::destroy($id);
    }

    public function getPromotionEncours()
    {
        return PromotionFacade::getEncours();
    }

    public function addReferentielToPromotion($promotionId, $referentielId)
    {
        $this->getPromotionById($promotionId)->referentiels()->attach($referentielId);
    }

    public function removeReferentielFromPromotion($promotionId, $referentielId)
    {
        $this->getPromotionById($promotionId)->referentiels()->detach($referentielId);
    }

    public function findByLibelle($libelle)
    {
        return PromotionFacade::where('libelle', $libelle)->first();
    }

    public function getPromotionStats($id)
    {
        $promotion = $this->getPromotionById($id);
        return [
            'info' => $promotion,
            'nombre_apprenants' => $promotion->apprenants->count(),
            'nombre_apprenants_actifs' => $promotion->apprenants->where('statut', 'actif')->count(),
            'nombre_apprenants_inactifs' => $promotion->apprenants->where('statut', 'inactif')->count(),
            'referentiels' => $promotion->referentiels->map(function ($referentiel) {
                return [
                    'info' => $referentiel,
                    'apprenants_count' => $referentiel->apprenants->count(),
                ];
            }),
        ];
    }

    public function cloturerPromotion($id)
    {
        $promotion = $this->getPromotionById($id);
        if ($promotion->date_fin->isPast()) {
            $promotion->update(['etat' => EtatPromotion::CLOTURER]);
            // Ici, on pourrait déclencher un job pour envoyer les relevés de notes
            return true;
        }
        return false;
    }
}
