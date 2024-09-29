<?php

namespace App\Repositories;

use App\Models\User;
use App\Events\UserCreated;
use App\Facades\UserFirebase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\LocalStorageService;
use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $LocalStorageService;
    public function __construct(LocalStorageService $LocalStorageService)
    {
        $this->LocalStorageService = $LocalStorageService;
    }

    public function getAllUsers(array $filters)
    {
        $query = UserFirebase::query();
        if (!empty($filters['role'])) {
            $query->where('fonction', $filters['role']);
        }
        return $query->get();
    }

    public function createUser(array $data)
    {
        DB::beginTransaction();
    
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
    
        // Récupérer le nom original du fichier
        $originalFileName = $data['photo']->getClientOriginalName();
        
        // Encoder l'image en base64
        $imageBase64 = base64_encode(file_get_contents($data['photo']->getPathname()));
    
        // Stocker l'image localement
        $localPath = $this->LocalStorageService->storeImageLocally($imageBase64, 'images/users', $originalFileName);
        
        // Ajouter le chemin de l'image dans les données
        $data['photo'] = $localPath;
    
        // Créer l'utilisateur dans MySQL
        $userMysql = User::create($data);
    
        // Créer l'utilisateur dans Firebase
        $firebaseUserId = UserFirebase::create($data);
    
        // Récupérer les données depuis Firebase
        $data = UserFirebase::find($firebaseUserId);
    
        // Mettre à jour l'utilisateur MySQL avec l'ID Firebase
        $userMysql->id = $firebaseUserId;
        $userMysql->save();
    
        // Commit la transaction
        DB::commit();
    
        // Émettre un événement pour l'utilisateur créé
        event(new UserCreated($userMysql, $firebaseUserId));
    
        return $userMysql;
    }
    


    public function getUserById(string $id)
    {
        return UserFirebase::find($id);
    }

    public function updateUser(string $id, array $data): ?array
    {
        DB::beginTransaction();
        $userMysql = User::find($id);
        if ($userMysql) {
            $userMysql->update($data);
        }
        $userFirebase = UserFirebase::find($id);
        if ($userFirebase) {
            UserFirebase::update($id, $data);
        }
        DB::commit();
        return [
            'firebase' => UserFirebase::find($id),
        ];
    }

    public function deleteUser(string $id): bool
    {
        DB::beginTransaction();
        $deletedMysql = User::destroy($id);
        $deletedFirebase = UserFirebase::delete($id);
        DB::commit();
        return $deletedMysql && $deletedFirebase;
    }
}
