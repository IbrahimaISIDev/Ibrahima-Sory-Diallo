<?php

namespace App\Models;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

abstract class FirebaseModel
{
    protected $database;
    protected $reference;
    protected $path;

    public function __construct()
    {
        $this->database = $this->getDatabase();
        $this->reference = $this->database->getReference($this->path);
    }

    protected function getDatabase(): Database
    {
        $factory = (new Factory())
            ->withDatabaseUri(config('database.connections.firebase.database'))
            ->withServiceAccount(config('database.connections.firebase.credentials'));
        
        return $factory->createDatabase();
    }

    public function all()
    {
        $result = $this->reference->getValue();
        return $result === null ? [] : $result;
    }

    public function find($id)
    {
        $result = $this->reference->getChild($id)->getValue();
        return $result === null ? null : $result;
    }

    public function create(array $data)
    {
        return $this->reference->push($data)->getKey();
    }

    public function update($id, array $data)
    {
        $this->reference->getChild($id)->update($data);
        return $id;
    }

    public function delete($id)
    {
        $this->reference->getChild($id)->remove();
        return true;
    }
}