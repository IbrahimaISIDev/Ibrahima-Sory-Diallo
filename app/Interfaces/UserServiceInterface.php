<?php
namespace App\Interfaces;

use Illuminate\Http\Request;

interface UserServiceInterface
{
   public function getAllUsers(Request $request);

   public function createUser(array $data);

   public function getUserById(string $id);

   public function updateUser(string $id, array $data);

   public function deleteUser(string $id);
}