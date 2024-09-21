<?php
namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\UserServiceInterface;

class UserService implements UserServiceInterface
{
    protected $Repository;
    public function __construct(UserRepositoryInterface $Repository)
    {
        $this->Repository = $Repository;
    }
    public function getAllUsers()
    {
        return $this->Repository->getAllUsers();
    }

    public function createUser(array $data)
    {
        return $this->Repository->createUser($data);
    }

    public function getUserById(string $id)
    {
        return $this->Repository->getUserById($id);
    }

    public function updateUser(string $id, array $data)
    {
        return $this->Repository->updateUser($id, $data);
    }

    public function deleteUser(string $id)
    {
        return $this->Repository->deleteUser($id);
    }
}