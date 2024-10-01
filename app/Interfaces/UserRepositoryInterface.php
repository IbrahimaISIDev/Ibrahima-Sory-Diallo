<?php
namespace App\Interfaces;

use App\Models\User;
use App\Models\UserFirebase;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function getAllUsers(array $filters);
    public function createUser(array $data);
    public function getUserById(string $id); 
    public function updateUser(string $id, array $data): ?array;
    public function deleteUser(string $id): bool;
}