<?php

namespace App\Repositories;

use App\Models\ReferentielFirebase;
use App\Interfaces\Repositories\ReferentielRepositoryInterface;

class ReferentielRepository implements ReferentielRepositoryInterface
{
    protected $model;

    public function __construct(ReferentielFirebase $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        return $this->model->update($id, $data);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }
}