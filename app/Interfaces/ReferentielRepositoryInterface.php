<?php

namespace App\Interfaces;

interface ReferentielRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findById($id);
    public function getReferentielById($id);
    public function getAllReferentiels();
    public function getReferentielsActifs();
    public function deleteReferentiel($id);
    public function getArchivedReferentiels();
    public function findByCode($code);
    public function addCompetenceToReferentiel($referentielId, $competenceData);
    public function updateCompetence($competenceId, $updatedData);
    public function deleteCompetence($competenceId);
    public function addModuleToCompetence($competenceId, $moduleData);
    public function getModulesByCompetenceId($competenceId);
    public function updateModule($moduleId, $updatedData);
    public function deleteModule($moduleId);
    public function getCompetencesByReferentielId($referentielId);

}
