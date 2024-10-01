<?php

namespace App\Interfaces;

interface ReferentielServiceInterface
{
    public function createReferentiel(array $data);
    public function updateReferentiel($id, array $data);
    public function getReferentielById($id);
    public function getAllReferentiels();
    public function getReferentielsActifs();
    public function deleteReferentiel($id);
    public function getArchivedReferentiels();
    public function getCompetencesByReferentielId($referentielId);
    public function addCompetenceToReferentiel($referentielId, array $competenceData);
    public function updateCompetence($competenceId, array $updatedData);
    public function deleteCompetence($competenceId);
    public function addModuleToCompetence($competenceId, array $moduleData);
    public function getModulesByCompetenceId($competenceId);
    public function updateModule($moduleId, array $updatedData);
    public function deleteModule($moduleId);
}