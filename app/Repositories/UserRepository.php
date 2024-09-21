<?php

namespace App\Repositories;

use App\Models\UserMysql;
use App\Facades\UserFirebase;
use Illuminate\Support\Facades\DB;
use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers()
    {
        return UserFirebase::all();
    }

    public function createUser(array $data)
    {
        DB::beginTransaction();
        try {
            $userMysql = UserMysql::create($data);
            $userFirebaseId = UserFirebase::create($data);
            DB::commit();
            return [
                'mysql' => $userMysql,
                'firebase' => UserFirebase::find($userFirebaseId),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getUserById(string $id)
    {
        return UserFirebase::find($id);
    }

    public function updateUser(string $id, array $data): ?array
    {
        DB::beginTransaction();
        try {
            $userMysql = UserMysql::find($id);
            if ($userMysql) {
                $userMysql->update($data);
            }
            $userFirebase = UserFirebase::find($id);
            if ($userFirebase) {
                UserFirebase::update($id, $data);
            }
            DB::commit();
            return [
                'mysql' => $userMysql,
                'firebase' => UserFirebase::find($id),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteUser(string $id): bool
    {
        DB::beginTransaction();
        try {
            $deletedMysql = UserMysql::destroy($id) > 0;
            $deletedFirebase = UserFirebase::delete($id);
            DB::commit();
            return $deletedMysql && $deletedFirebase;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}