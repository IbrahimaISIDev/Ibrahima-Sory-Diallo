<?php

namespace App\Services;

use Carbon\Carbon;
use App\Enums\EtatPromotion;
use App\Jobs\SendReleveNotesJob;
use Illuminate\Support\Facades\DB;
use App\Interfaces\PromotionServiceInterface;
use App\Interfaces\PromotionRepositoryInterface;
use App\Interfaces\ReferentielRepositoryInterface;

class PromotionService implements PromotionServiceInterface
{
    protected $promotionRepository;
    protected $referentielRepository;

    public function __construct(
        PromotionRepositoryInterface $promotionRepository,
        ReferentielRepositoryInterface $referentielRepository
    ) {
        $this->promotionRepository = $promotionRepository;
        $this->referentielRepository = $referentielRepository;
    }

    public function createPromotion(array $data)
    {
        DB::beginTransaction();

        try {
            $this->validateUniqueLibelle($data['libelle']);
            $data['etat'] = EtatPromotion::INACTIF;
            $this->setPromotionDates($data);
            $promotion = $this->promotionRepository->createPromotion($data);
            $this->updatePromotionReferentiels($promotion, $data['referentiels'] ?? []);

            DB::commit();
            return $promotion;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatePromotion($id, array $data)
    {
        $promotion = $this->promotionRepository->getPromotionById($id);
        $this->validatePromotionUpdate($promotion, $data);
        return $this->promotionRepository->updatePromotion($id, $data);
    }

    public function changePromotionStatus($id, EtatPromotion $newStatus)
    {
        return $this->promotionRepository->updatePromotion($id, ['etat' => $newStatus]);
    }

    public function cloturerPromotion($id)
    {
        $promotion = $this->promotionRepository->getPromotionById($id);
        if ($this->isPromotionPast($promotion)) {
            SendReleveNotesJob::dispatch($id);
            return $this->changePromotionStatus($id, EtatPromotion::CLOTURER);
        }
        return false;
    }

    private function validateUniqueLibelle($libelle)
    {
        if ($this->promotionRepository->findByLibelle($libelle)) {
            throw new \Exception("Erreur : le libellé de la promotion doit être unique.");
        }
    }

    public function getPromotionStats($id)
    {
        return $this->promotionRepository->getPromotionStats($id);
    }

    private function setPromotionDates(array &$data)
    {
        if (!isset($data['date_fin'])) {
            $data['date_fin'] = $this->calculerDateFin($data['date_debut'], $data['duree']);
        } elseif (!isset($data['duree'])) {
            $data['duree'] = $this->calculerDuree($data['date_debut'], $data['date_fin']);
        }
    }

    private function validatePromotionUpdate($promotion, $data)
    {
        if ($promotion->etat === EtatPromotion::CLOTURER) {
            throw new \Exception("Une promotion clôturée ne peut pas être modifiée.");
        }

        if (isset($data['libelle']) && $data['libelle'] !== $promotion->libelle) {
            $this->validateUniqueLibelle($data['libelle']);
        }
    }

    private function isPromotionPast($promotion)
    {
        return Carbon::parse($promotion->date_fin)->isPast();
    }

    private function canRemoveReferentiel($promotionId, $referentielId)
    {
        if (auth()->user()->role === 'MANAGER') {
            return true;
        }
        $referentiel = $this->referentielRepository->findById($referentielId);
        return $referentiel->apprenants()->where('promotion_id', $promotionId)->count() === 0;
    }

    // Autres méthodes privées
    private function calculerDateFin($dateDebut, $duree)
    {
        return Carbon::parse($dateDebut)->addMonths($duree);
    }

    private function calculerDuree($dateDebut, $dateFin)
    {
        return Carbon::parse($dateDebut)->diffInMonths(Carbon::parse($dateFin));
    }

    public function getAllPromotions()
    {
        return $this->promotionRepository->getAllPromotions();
    }

    public function getPromotionById($id)
    {
        return $this->promotionRepository->getPromotionById($id);
    }

    public function getPromotionEncours()
    {
        return $this->promotionRepository->getPromotionEncours();
    }

    public function updatePromotionReferentiels($promotionId, array $referentielIds)
    {
        $promotion = $this->promotionRepository->getPromotionById($promotionId)->getKey();
        dd($promotion);
        if ($promotion->etat === EtatPromotion::CLOTURER) {
            throw new \Exception("Une promotion clôturée ne peut pas être modifiée.");
        }

        foreach ($referentielIds as $referentielId) {
            $referentiel = $this->referentielRepository->findById($referentielId);
            if ($referentiel->etat !== 'actif') {
                throw new \Exception("Seuls les référentiels actifs peuvent être ajoutés à une promotion.");
            }
            $this->promotionRepository->addReferentielToPromotion($promotionId, $referentielId);
        }

        return $this->promotionRepository->getPromotionById($promotionId);
    }
}
