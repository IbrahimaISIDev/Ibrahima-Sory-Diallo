<?php

namespace App\Repositories;

use App\Models\PromotionFirebase;
use App\Interfaces\PromotionFirebaseInterface;

class PromotionRepository 
// implements PromotionRepositoryInterface
{
    protected $model;

    public function __construct(PromotionFirebase $model)
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

    public function findByName($name)
    {
        return $this->model->findByName($name);
    }

    // Implémentez d'autres méthodes spécifiques aux promotions ici
}