<?php

namespace App\Services;

use App\Enums\EtatReferentiel;
use App\Interfaces\ReferentielServiceInterface;
use App\Interfaces\ReferentielRepositoryInterface;

class ReferentielService implements ReferentielServiceInterface
{
    protected $referentielRepository;

    public function __construct(ReferentielRepositoryInterface $referentielRepository)
    {
        $this->referentielRepository = $referentielRepository;
    }

    public function createReferentiel(array $data)
    {
        $data['etat'] = EtatReferentiel::ACTIF->value;

        $competences = isset($data['competences']) && is_array($data['competences'])
            ? array_map(function ($competence) {
                return [
                    'titre' => trim($competence['titre']),
                    'description' => trim($competence['description']),
                    'modules' => $competence['modules'] ?? []
                ];
            }, $data['competences'])
            : [];

        $referentielId = $this->referentielRepository->create($data);
        if ($referentielId) {
            // Ajoutez les compétences au référentiel
            foreach ($competences as $competence) {
                $this->referentielRepository->addCompetenceToReferentiel($referentielId, $competence);
            }
            // Récupérez les compétences en utilisant la méthode modifiée
            $referentielCompetences = $this->referentielRepository->getCompetencesByReferentielId($referentielId);

            return $referentielCompetences;
        }

        return null;
    }


    public function updateReferentiel($id, array $data)
    {
        $referentiel = $this->referentielRepository->getReferentielById($id);
        if (isset($data['code']) && $data['code'] !== $referentiel['code']) {
            if ($this->referentielRepository->findByCode($data['code'])) {
                throw new \Exception("Le code du référentiel doit être unique.");
            }
        }
        return $this->referentielRepository->update($id, $data);
    }

    public function getReferentielById($id)
    {
        return $this->referentielRepository->getReferentielById($id);
    }

    public function getAllReferentiels()
    {
        return $this->referentielRepository->getAllReferentiels();
    }

    public function deleteReferentiel($id)
    {
        $referentiel = $this->referentielRepository->getReferentielById($id);
        return $this->referentielRepository->update($id, ['etat' => EtatReferentiel::ARCHIVE->value]);
    }

    public function getCompetencesByReferentielId($referentielId)
    {
        $competencesRef = $this->referentielRepository->getCompetencesByReferentielId("referentiels/{$referentielId}/competences");
        return $competencesRef->getValue();
    }

    public function getReferentielsActifs()
    {
        $referentiels = $this->getAllReferentiels();
        return array_filter($referentiels, function ($referentiel) {
            return $referentiel['etat'] === 'actif';
        });
    }

    public function getArchivedReferentiels()
    {
        $referentiels = $this->getAllReferentiels();
        return array_filter($referentiels, function ($referentiel) {
            return $referentiel['etat'] === 'archivé';
        });
    }

    public function addCompetenceToReferentiel($referentielId, array $competenceData)
    {
        $this->referentielRepository->addCompetenceToReferentiel($referentielId, $competenceData);
    }

    public function updateCompetence($competenceId, array $updatedData)
    {
        $this->referentielRepository->updateCompetence($competenceId, $updatedData);
    }

    // Méthode pour supprimer une compétence
    public function deleteCompetence($competenceId)
    {
        $this->referentielRepository->deleteCompetence($competenceId);
    }

    // Méthode pour ajouter un module à une compétence
    public function addModuleToCompetence($competenceId, array $moduleData)
    {
        $this->referentielRepository->addModuleToCompetence($competenceId, $moduleData);
    }

    // Méthode pour lister les modules d'une compétence
    public function getModulesByCompetenceId($competenceId)
    {
        return $this->referentielRepository->getModulesByCompetenceId($competenceId);
    }

    // Méthode pour modifier un module
    public function updateModule($moduleId, array $updatedData)
    {
        $this->referentielRepository->updateModule($moduleId, $updatedData);
    }

    // Méthode pour supprimer un module
    public function deleteModule($moduleId)
    {
        $this->referentielRepository->deleteModule($moduleId);
    }
}
