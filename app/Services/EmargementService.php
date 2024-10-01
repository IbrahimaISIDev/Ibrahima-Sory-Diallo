<?php

namespace App\Services;

use App\Interfaces\EmargementServiceInterface;
use App\Interfaces\EmargementRepositoryInterface;
use Carbon\Carbon;

class EmargementService implements EmargementServiceInterface
{
    protected $repository;

    public function __construct(EmargementRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function enregistrerEmargementGroupe(array $apprenantIds, $date = null)
    {
        $date = $date ?? Carbon::today()->toDateString();
        return $this->repository->createForGroup($apprenantIds, $date);
    }

    public function enregistrerEmargementEntree($apprenantId, $date = null)
    {
        $date = $date ?? Carbon::today()->toDateString();
        return $this->repository->enregistrerEntree($apprenantId, $date);
    }

    public function enregistrerEmargementSortie($apprenantId, $date = null)
    {
        $date = $date ?? Carbon::today()->toDateString();
        return $this->repository->enregistrerSortie($apprenantId, $date);
    }


    // public function enregistrerEmargementApprenant($apprenantId, $date = null)
    // {
    //     $date = $date ?? Carbon::today();
    //     return $this->repository->createOrUpdateForApprenant($apprenantId, $date);
    // }

    public function marquerAbsencesFinJournee($date = null)
    {
        $date = $date ?? Carbon::yesterday()->toDateString(); // Par défaut, marquer pour la veille
        return $this->repository->marquerAbsencesFinJournee($date);
    }

    public function listerEmargements($promotionId, array $filters = [])
    {
        return $this->repository->getForPromotion($promotionId, $filters);
    }

    public function modifierEmargementApprenant($apprenantId, $date, array $data)
    {
        return $this->repository->updateForApprenant($apprenantId, $date, $data);
    }

    public function marquerAbsents()
    {
        $today = Carbon::today();
        return $this->repository->markAbsentees($today);
    }

    public function declencherAbsences($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();

        $absents = $this->repository->markAbsentees($date);

        return [
            'date' => $date->toDateString(),
            'absents_marques' => $absents,
        ];
    }
}
