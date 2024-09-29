<?php

namespace App\Interfaces;

interface ApprenantsRepositoryInterface
{
    public function all(array $filters = []);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete(array $id);
    public function getTrashed();
    public function restore($id);
    public function getInactive();
    public function forceDelete($id);
}
