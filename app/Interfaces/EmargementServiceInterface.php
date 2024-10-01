<?php

namespace App\Interfaces;

interface EmargementServiceInterface
{
    public function enregistrerEmargementGroupe(array $apprenantIds, $date = null);
   // public function enregistrerEmargementApprenant($apprenantId, $date = null);
    public function listerEmargements($promotionId, array $filters = []);
    public function modifierEmargementApprenant($apprenantId, $date, array $data);
    public function marquerAbsents();
    public function declencherAbsences($date = null);
    public function enregistrerEmargementEntree($apprenantId, $date = null);
    public function enregistrerEmargementSortie($apprenantId, $date = null);
    public function marquerAbsencesFinJournee($date = null);

}