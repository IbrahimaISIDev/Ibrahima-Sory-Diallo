<?php

namespace App\Repositories;

use App\Enums\EtatReferentiel;
use App\Facades\ReferentielFacade;
use App\Interfaces\ReferentielRepositoryInterface;
use App\Services\FirebaseService;

class ReferentielRepository implements ReferentielRepositoryInterface
{
    protected $firebaseService;

    public function __construct(ReferentielFacade $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function all()
    {
        return ReferentielFacade::all();
    }


    public function find($id)
    {
        return ReferentielFacade::find($id);
    }

    public function create(array $data)
    {
        return ReferentielFacade::create($data);
    }

    public function update($id, array $data)
    {
        return ReferentielFacade::update($id)->set($data);
    }

    public function delete($id)
    {
        return (array) ReferentielFacade::delete($id);
    }

    public function findById($id)
    {
        return $this->find($id);
    }

    public function getAllReferentiels()
    {
        return $this->all();
    }

    public function getReferentielsActifs()
    {
        $referentiels = $this->all();
        return array_filter($referentiels, function ($referentiel) {
            return $referentiel['etat'] === EtatReferentiel::ACTIF->value;
        });
    }

    public function deleteReferentiel($id)
    {
        return $this->delete($id);
    }

    public function getArchivedReferentiels()
    {
        $referentiels = $this->all();
        return array_filter($referentiels, function ($referentiel) {
            return $referentiel['etat'] === EtatReferentiel::ARCHIVE->value;
        });
    }

    public function findByCode($code)
    {
        $referentiels = $this->all();
        foreach ($referentiels as $id => $referentiel) {
            if ($referentiel['code'] === $code) {
                return ['id' => $id] + $referentiel;
            }
        }
        return null;
    }

    public function getReferentielById($id)
    {
        $referentiel = $this->find($id);
        if ($referentiel) {
            $referentiel->competences = $this->getCompetencesByReferentielId($id);
        }
        return $referentiel;
    }

    public function addCompetenceToReferentiel($referentielId, $competenceData)
    {
        // Ajouter la compétence au référentiel via son ID
        $referentiel = ReferentielFacade::find($referentielId);
        $referentiel->competences()->create($competenceData);
    }

    // Méthode pour récupérer toutes les compétences d'un référentiel
    public function getCompetencesByReferentielId($referentielId)
    {
        return ReferentielFacade::find($referentielId);
    }

    // Nouvelle méthode : modifier une compétence
    public function updateCompetence($competenceId, $updatedData)
    {
        $competence = ReferentielFacade::find($competenceId);
        $competence->update($updatedData);
    }

    // Nouvelle méthode : supprimer une compétence
    public function deleteCompetence($competenceId)
    {
        $competence = ReferentielFacade::find($competenceId);
        $competence->delete();
    }

    // Nouvelle méthode : ajouter un module à une compétence
    public function addModuleToCompetence($competenceId, $moduleData)
    {
        $competence = ReferentielFacade::find($competenceId);
        $competence->modules()->create($moduleData);
    }

    // Nouvelle méthode : lister les modules d'une compétence
    public function getModulesByCompetenceId($competenceId)
    {
        return ReferentielFacade::find($competenceId);
    }

    // Nouvelle méthode : modifier un module
    public function updateModule($moduleId, $updatedData)
    {
        $module = ReferentielFacade::find($moduleId);
        $module->update($updatedData);
    }

    // Nouvelle méthode : supprimer un module
    public function deleteModule($moduleId)
    {
        $module = ReferentielFacade::find($moduleId);
        $module->delete();
    }
}