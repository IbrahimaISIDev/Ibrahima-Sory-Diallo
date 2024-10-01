<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreReferentielRequest;
use App\Http\Requests\UpdateReferentielRequest;
use App\Interfaces\ReferentielServiceInterface;

class ReferentielController extends Controller
{
    protected $referentielService;

    public function __construct(ReferentielServiceInterface $referentielService)
    {
        $this->referentielService = $referentielService;
    }

    public function index(Request $request)
    {
        $etat = $request->query('etat');

        if ($etat === 'actif') {
            return response()->json($this->referentielService->getReferentielsActifs());
        } elseif ($etat === 'archivé') {
            return response()->json($this->referentielService->getArchivedReferentiels());
        }

        return response()->json($this->referentielService->getAllReferentiels());
    }


    public function store(Request $request)
    {
        return $this->referentielService->createReferentiel($request->all());
    }


    public function show($id, Request $request)
    {
        $referentiel = $this->referentielService->getReferentielById($id);

        if ($request->query('filter') === 'competences') {
            // Récupère les compétences associées
            $competences = $this->referentielService->getCompetencesByReferentielId($id);
            return response()->json([
                'referentiel' => $referentiel,
                'competences' => $competences
            ]);
        }

        return response()->json($referentiel);
    }

    public function update(Request $request, $id)
    {
        $referentiel = $this->referentielService->updateReferentiel($id, $request->all());
        return response()->json($referentiel);
    }

    public function destroy($id)
    {
        $this->referentielService->deleteReferentiel($id);
        return response()->json(null, 204);
    }

    public function archived()
    {
        return response()->json($this->referentielService->getArchivedReferentiels());
    }
    public function deleteReferentiel($id)
    {
        $this->referentielService->updateReferentiel($id, ['etat' => 'archivé']);
        return response()->json(['message' => 'Referentiel archivé avec succès'], 204);
    }

    public function addCompetenceToReferentiel($referentielId, Request $request)
    {
        $competenceData = $request->validate([
            'titre' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $this->referentielService->addCompetenceToReferentiel($referentielId, $competenceData);
        return response()->json(['message' => 'Compétence ajoutée avec succès'], 200);
    }

    // Méthode pour modifier une compétence
    public function updateCompetence($competenceId, Request $request)
    {
        $updatedData = $request->validate([
            'titre' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $this->referentielService->updateCompetence($competenceId, $updatedData);
        return response()->json(['message' => 'Compétence modifiée avec succès'], 200);
    }

    // Méthode pour supprimer une compétence
    public function deleteCompetence($competenceId)
    {
        $this->referentielService->deleteCompetence($competenceId);
        return response()->json(['message' => 'Compétence supprimée avec succès'], 200);
    }

    // Méthode pour ajouter un module à une compétence
    public function addModuleToCompetence($competenceId, Request $request)
    {
        $moduleData = $request->validate([
            'titre' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $this->referentielService->addModuleToCompetence($competenceId, $moduleData);
        return response()->json(['message' => 'Module ajouté avec succès'], 200);
    }

    // Méthode pour lister les modules d'une compétence
    public function getModulesByCompetenceId($competenceId)
    {
        $modules = $this->referentielService->getModulesByCompetenceId($competenceId);
        return response()->json($modules, 200);
    }

    // Méthode pour modifier un module
    public function updateModule($moduleId, Request $request)
    {
        $updatedData = $request->validate([
            'titre' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $this->referentielService->updateModule($moduleId, $updatedData);
        return response()->json(['message' => 'Module modifié avec succès'], 200);
    }

    // Méthode pour supprimer un module
    public function deleteModule($moduleId)
    {
        $this->referentielService->deleteModule($moduleId);
        return response()->json(['message' => 'Module supprimé avec succès'], 200);
    }
}
