<?php

namespace App\Repositories;

use App\Interfaces\EmargementRepositoryInterface;
use Carbon\Carbon;
use App\Facades\EmargementFacade;

class EmargementRepository implements EmargementRepositoryInterface
{
    public function createForGroup(array $apprenantIds, $date, $heureEntree = null, $heureSortie = null)
    {
        $emargements = [];
        $now = Carbon::now();

        // Si heureEntree n'est pas fourni, utiliser l'heure actuelle
        $heureEntree = $heureEntree ?? $now->format('H:i:s');

        // Si heureSortie n'est pas fourni, utiliser une heure par défaut (par exemple, 8 heures après l'entrée)
        if (!$heureSortie) {
            $heureSortie = Carbon::parse($heureEntree)->addHours(8)->format('H:i:s');
        }

        foreach ($apprenantIds as $apprenantId) {
            $emargement = [
                'apprenant_id' => $apprenantId,
                'date' => $date,
                'heure_entree' => $heureEntree,
                'heure_sortie' => $heureSortie,
                'statut' => $this->determinerStatut($heureEntree),
            ];
            EmargementFacade::create($emargement);
            $emargements[] = $emargement;
        }
        return $emargements;
    }

    public function enregistrerEntree($apprenantId, $date)
    {
        $now = Carbon::now(); // Obtenir l'heure actuelle
        $heureEntree = $now->format('H:i:s'); // Formater l'heure d'entrée

        // Nouvelle plage horaire : 23h00 à 23h30
        $heureMin = Carbon::parse('23:00:00');
        $heureMax = Carbon::parse('23:30:00');

        // Vérification de la plage horaire
        if ($now->lt($heureMin) || $now->gt($heureMax)) {
            return null; // Hors de la plage d'émargement (trop tôt ou trop tard)
        }

        // Récupérer l'émargement existant
        $emargement = EmargementFacade::where('apprenant_id', $apprenantId)
            ->where('date', $date)
            ->first();

        // Si l'émargement existe et que l'heure d'entrée n'est pas encore renseignée
        if ($emargement && $emargement->heure_entree === null) {
            // Mettre à jour l'heure d'entrée et le statut (retard si après 23h00)
            $emargement->update([
                'heure_entree' => $heureEntree,
                'statut' => $now->gt($heureMin) ? 'retard' : 'present',
            ]);
            return $emargement; // Retourner l'émargement mis à jour
        }

        return null; // Déjà émargé ou émargement non trouvé
    }


    public function enregistrerSortie($apprenantId, $date)
    {
        $now = Carbon::now();
        $heureSortie = $now->format('H:i:s');
        $heureMin = Carbon::parse('16:00:00');

        if ($now->lt($heureMin)) {
            return null; // Trop tôt pour émarger la sortie
        }

        $emargement = EmargementFacade::where('apprenant_id', $apprenantId)
            ->where('date', $date)
            ->first();

        if ($emargement && $emargement->heure_entree !== null && $emargement->heure_sortie === null) {
            $emargement->update([
                'heure_sortie' => $heureSortie,
            ]);
            return $emargement;
        }

        return null; // Pas d'émargement d'entrée ou déjà émargé pour la sortie
    }

    public function marquerAbsencesFinJournee($date)
    {
        return EmargementFacade::where('date', $date)
            ->where(function ($query) {
                $query->whereNull('heure_entree')
                    ->orWhereNull('heure_sortie');
            })
            ->update(['statut' => 'absent']);
    }
    private function determinerStatut($heureEntree)
    {
        $heureEntreeCarbon = Carbon::parse($heureEntree);
        $heureLimit = Carbon::parse('08:00:00');

        if ($heureEntreeCarbon->lt($heureLimit)) {
            return 'present';
        } elseif ($heureEntreeCarbon->eq($heureLimit)) {
            return 'present';
        } else {
            return 'retard';
        }
    }

    public function createOrUpdateForApprenant($apprenantId, $date)
    {
        $emargement = EmargementFacade::where('apprenant_id', $apprenantId)
            ->where('date', $date)
            ->first();

        $now = Carbon::now();

        if (!$emargement) {
            $emargement = [
                'apprenant_id' => $apprenantId,
                'date' => $date,
                'heure_entree' => $now->toTimeString(),
                'heure_sortie' => $now->addHours(8)->toTimeString(), // Par défaut, 8 heures après l'entrée
                'statut' => $this->determinerStatut($now->toTimeString()),
            ];
            return EmargementFacade::create($emargement);
        } else {
            $emargement->heure_sortie = $now->toTimeString();
            $emargement->save();
            return $emargement;
        }
    }

    public function getForPromotion($promotionId, array $filters = [])
    {
        $query = EmargementFacade::where('promotion_id', $promotionId);

        if (isset($filters['mois'])) {
            $query->whereMonth('date', $filters['mois']);
        }

        if (isset($filters['date'])) {
            $query->whereDate('date', $filters['date']);
        }

        return $query->get();
    }

    public function updateForApprenant($apprenantId, $date, array $data)
    {
        $emargement = EmargementFacade::where('apprenant_id', $apprenantId)
            ->where('date', $date)
            ->first();

        if ($emargement) {
            $emargement->update($data);
        }

        return $emargement;
    }

    public function markAbsentees(Carbon $date)
    {
        $absentsCount = EmargementFacade::whereDate('date', $date)
            ->whereNull('heure_entree')
            ->update(['statut' => 'absent']);

        return $absentsCount;
    }
}
