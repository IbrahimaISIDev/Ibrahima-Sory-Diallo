<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmargementService;

class EmargementController extends Controller
{
    protected $service;

    public function __construct(EmargementService $service)
    {
        $this->service = $service;
    }

    public function enregistrerGroupe(Request $request)
    {
        // Récupère toutes les données de la requête
        $data = $request->all();

        // Passe les données nécessaires à la fonction de service
        $emargements = $this->service->enregistrerEmargementGroupe(
            $data['apprenant_ids'],
            $data['date']
        );

        return response()->json($emargements, 201);
    }

    public function enregistrerEntree(Request $request, $apprenantId)
    {
        $emargement = $this->service->enregistrerEmargementEntree($apprenantId);

        if ($emargement) {
            return response()->json($emargement, 201);
        } else {
            return response()->json(['message' => 'Émargement non autorisé à cette heure'], 403);
        }
    }


    public function enregistrerSortie(Request $request, $apprenantId)
    {
        $emargement = $this->service->enregistrerEmargementSortie($apprenantId);

        if ($emargement) {
            return response()->json($emargement, 201);
        } else {
            return response()->json(['message' => 'Émargement non autorisé à cette heure ou entrée non enregistrée'], 403);
        }
    }
    // public function enregistrerApprenant(Request $request, $id)
    // {
    //     // Récupère toutes les données de la requête
    //     $data = $request->all();

    //     // Passe les données nécessaires à la fonction de service
    //     $emargement = $this->service->enregistrerEmargementApprenant(
    //         $id,
    //         $data['date']
    //     );

    //     return response()->json($emargement, 201);
    // }

    public function lister(Request $request)
    {
        // Récupère toutes les données de la requête
        $data = $request->all();

        // Passe les données nécessaires à la fonction de service
        $emargements = $this->service->listerEmargements(
            $data['promotion_id'],
            $data
        );

        return response()->json($emargements);
    }

    public function modifier(Request $request, $id)
    {
        // Récupère toutes les données de la requête
        $data = $request->all();

        // Passe les données nécessaires à la fonction de service
        $emargement = $this->service->modifierEmargementApprenant(
            $id,
            $data['date'],
            $data
        );

        return response()->json($emargement);
    }

    public function declencherAbsences(Request $request)
    {
        // Récupère toutes les données de la requête
        $data = $request->all();

        // Passe les données nécessaires à la fonction de service
        $result = $this->service->declencherAbsences($data['date']);

        return response()->json($result);
    }
}
