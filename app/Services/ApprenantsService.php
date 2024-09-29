<?php

namespace App\Services;

use App\Notifications\WelcomeApprenant;
use Illuminate\Support\Facades\Notification;
use App\Interfaces\ApprenantsServiceInterface;
use App\Interfaces\ApprenantsRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ApprenantsImport;
use App\Exports\FailedApprenantsExport;

class ApprenantsService implements ApprenantsServiceInterface
{
    protected $repository;

    public function __construct(ApprenantsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function registerApprenant(array $data)
    {
        $apprenant = $this->repository->create($data);
        Notification::send($apprenant, new WelcomeApprenant($apprenant));
        return $apprenant;
    }

    public function updateApprenantDetails($id, array $data)
    {
        $currentData = (array) $this->repository->find($id);
        $updatedData = array_merge($currentData, $data);
        return $this->repository->update($id, $updatedData);
    }

    public function getApprenantDetails($id)
    {
        return $this->repository->find($id);
    }

    public function listAllApprenants(array $filters = [])
    {
        return $this->repository->all($filters);
    }

    public function removeApprenant($id)
    {
        $apprenant = (array)$this->repository->find($id);
        if ($apprenant) {
            $apprenant['deleted_at'] = now()->timestamp;
            $this->repository->update($id, $apprenant);
            return true;
        }
        return false;
    }

    public function listDeletedApprenants()
    {
        return $this->repository->getTrashed();
    }

    public function restoreApprenant($id)
    {
        return $this->repository->restore($id);
    }

    public function permanentlyDeleteApprenant($id)
    {
        return $this->repository->forceDelete($id);
    }

    public function importApprenants($file)
    {
        // $import = new ApprenantsImport($this->repository);
        // Excel::import($import, $file);
        // if ($import->failures()->isNotEmpty()) {
        //     $export = new FailedApprenantsExport($import->failures());
        //     $failedFile = 'failed_imports/apprenants_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        //     Excel::store($export, $failedFile, 'public');
        //     return [
        //         'success' => false,
        //         'failed_file' => Storage::url($failedFile)
        //     ];
        // }
        // return ['success' => true];
    }

    public function getInactiveApprenants()
    {
        return $this->repository->getInactive();
    }

    public function sendRelanceToInactiveApprenants($apprenantIds = null)
    {
        $inactiveApprenants = $apprenantIds
            ? $this->repository->all(['status' => 'Inactive'])->whereIn('id', $apprenantIds)
            : $this->repository->getInactive();

        foreach ($inactiveApprenants as $apprenant) {
            // Envoyer relance
            // Notification::send($apprenant, new RelanceActivationCompte($apprenant));
        }

        return count($inactiveApprenants);
    }
}