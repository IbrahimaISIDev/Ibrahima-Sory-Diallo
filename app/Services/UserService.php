<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Interfaces\UserServiceInterface;
use App\Interfaces\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    protected $Repository;
    public function __construct(UserRepositoryInterface $Repository)
    {
        $this->Repository = $Repository;
    }
    public function getAllUsers(Request $request)
    {
        return $this->Repository->getAllUsers($request->only('fonction'));
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