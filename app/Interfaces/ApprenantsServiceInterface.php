<?php

namespace App\Interfaces;

interface ApprenantsServiceInterface
{
    public function registerApprenant(array $data);
    public function updateApprenantDetails(int $id, array $data);
    public function getApprenantDetails(int $id);
    public function listAllApprenants(array $filters = []);
    public function removeApprenant(int $id);
    public function listDeletedApprenants();
    public function restoreApprenant($id);
    public function permanentlyDeleteApprenant($id);
    public function importApprenants($file);
    public function getInactiveApprenants();
    public function sendRelanceToInactiveApprenants($apprenantIds = null);
}
