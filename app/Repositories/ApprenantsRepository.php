<?php

namespace App\Repositories;

use App\Facades\ApprenantsFacade;
use App\Services\LocalStorageService;
use App\Interfaces\ApprenantsRepositoryInterface;

class ApprenantsRepository implements ApprenantsRepositoryInterface
{
    protected $LocalStorageService;
    public function __construct(LocalStorageService $LocalStorageService)
    {
        $this->LocalStorageService = $LocalStorageService;
    }
    
    public function create(array $data)
    {
        // Récupérer le nom original du fichier
        $originalFileName = $data['photo']->getClientOriginalName();
        
        // Encoder l'image en base64
        $imageBase64 = base64_encode(file_get_contents($data['photo']->getPathname()));
    
        // Stocker l'image localement
        $localPath = $this->LocalStorageService->storeImageLocally($imageBase64, 'images/apprenants', $originalFileName);
    
        // Ajouter le chemin de l'image dans les données
        $data['photo_couverture'] = $localPath;
    
        // Générer un matricule et un code QR
        $data['matricule'] = ApprenantsFacade::genererMatricule();
        $data['code_qr'] = ApprenantsFacade::genererCodeQR();
    
        // Créer l'apprenant avec les données modifiées
        $apprenant = ApprenantsFacade::create($data);
    
        return $apprenant;
    }
    

    public function update($id, array $data)
    {
        return ApprenantsFacade::update($id, $data);
    }

    public function find($id)
    {
        return ApprenantsFacade::find($id);
    }

    public function all(array $filters = []): mixed
    {
        $query = ApprenantsFacade::query();

        if (isset($filters['referentiel'])) {
            $query->where('referentiel', '=', $filters['referentiel']);
        }

        if (isset($filters['status'])) {
            $query->where('status', '=', $filters['status']);
        }

        return $query->get();
    }

    public function getTrashed()
    {
        return ApprenantsFacade::query()->where('deleted_at', '!=', null)->get();
    }

    public function restore($id)
    {
        $apprenant = (array) $this->find($id);
        if ($apprenant && isset($apprenant['deleted_at'])) {
            return $this->delete($apprenant['deleted_at']);
        }
        return false;
    }

    public function getInactive()
    {
        return ApprenantsFacade::query()->where('status', '=', 'Inactive')->get();
    }

    public function delete(array $id)
    {
        return ApprenantsFacade::delete($id);
    }

    public function forceDelete($id)
    {
        return ApprenantsFacade::delete($id);
    }

}
