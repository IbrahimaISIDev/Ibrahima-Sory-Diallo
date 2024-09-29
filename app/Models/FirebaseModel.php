<?php

namespace App\Models;

use stdClass;
use Kreait\Firebase\Factory;
use InvalidArgumentException;
use Kreait\Firebase\Database;
use Google\Cloud\Core\Exception\NotFoundException;

abstract class FirebaseModel
{
    protected $database;
    protected $reference;
    protected $path;
    protected $query;
    protected $filters = [];
    protected static $collection;

    public function __construct()
    {
        $this->database = $this->getDatabase();
        $this->reference = $this->database->getReference($this->path);
        $this->query = $this->reference;
    }

    protected function getDatabase(): Database
    {
        $factory = (new Factory())
            ->withDatabaseUri(config('database.connections.firebase.database'))
            ->withServiceAccount(config('database.connections.firebase.credentials'));
        return $factory->createDatabase();
    }

    public function getReference($collection)
    {
        return $this->database->getReference($collection);
    }

    public function all()
    {
        return $this->reference->getValue();
    }

    public function find($id)
    {
        $result = $this->reference->getChild($id)->getValue();
        return $result === null ? new stdClass() : (object) $result;
    }
    
    public function getKey()
    {
        return $this->reference->getKey(); // Assurez-vous que cette ligne est correcte selon votre implémentation
    }

    public function findOrFail($key)
    {
        if (empty($key)) {
            throw new InvalidArgumentException("La clé ne peut pas être nulle ou vide.");
        }
        $childReference = $this->reference->getChild($key);
        $childReference->getSnapshot();
        return $key;
    }

    public function create(array $data)
    {
        $reference = $this->reference;
        $existingUsers = $reference->orderByKey()->getValue();
        $nextId = 1;
        if ($existingUsers) {
            $keys = array_keys($existingUsers);
            $keys = array_map('intval', $keys);
            $nextId = max($keys) + 1;
        }
        $newRef = $reference->getChild((string) $nextId);
        $newRef->set($data);
        return $nextId;
    }

    public function update($id, array $data)
    {
        $this->reference->getChild($id)->update($data);
        return $id;
    }

    public function delete($id)
    {
        $this->reference->getChild($id)->remove();
        return $id;
    }

    public static function query()
    {
        return new static;
    }

    public function where($field, $value, $operator = '=')
    {
        $this->filters[] = [$field, $operator, $value];
        return $this;
    }

    public function paginate($perPage = 15, $page = 1)
    {
        $all = $this->all();
        $total = count($all);
        $offset = ($page - 1) * $perPage;
        $items = array_slice($all, $offset, $perPage);

        return [
            'current_page' => $page,
            'data' => $items,
            'from' => $offset + 1,
            'last_page' => ceil($total / $perPage),
            'per_page' => $perPage,
            'to' => min($offset + $perPage, $total),
            'total' => $total,
        ];
    }

    public function get()
    {
        $result = $this->reference->getValue();

        if (!empty($this->filters)) {
            $result = $this->applyFilters($result);
        }

        $this->filters = []; // Reset filters
        return $result === null ? [] : $result;
    }

    public function first()
    {
        $result = $this->get();
        return !empty($result) ? reset($result) : null;
    }

    public function count()
    {
        return count($this->all());
    }

    public function orderBy($field, $direction = 'asc')
    {
        $query = $this->reference->orderByChild($field);
        $result = $query->getValue();

        if ($direction === 'desc') {
            $result = array_reverse($result);
        }

        return $result;
    }

    public function limit($count)
    {
        return $this->reference->limitToFirst($count)->getValue();
    }

    public function pluck($field)
    {
        $result = $this->all();
        return array_column($result, $field);
    }

    public function findMany(array $ids)
    {
        $results = [];
        foreach ($ids as $id) {
            $result = $this->find($id);
            if ($result) {
                $results[$id] = $result;
            }
        }
        return $results;
    }

    public function whereIn($field, array $values)
    {
        $results = [];
        foreach ($values as $value) {
            $query = $this->reference->orderByChild($field)->equalTo($value);
            $result = $query->getValue();
            if ($result) {
                $results = array_merge($results, $result);
            }
        }
        return $results;
    }

    public function whereBetween($field, $start, $end)
    {
        return $this->reference->orderByChild($field)
            ->startAt($start)
            ->endAt($end)
            ->getValue();
    }

    public function increment($id, $field, $amount = 1)
    {
        $currentValue = $this->find($id)[$field] ?? 0;
        return $this->update($id, [$field => $currentValue + $amount]);
    }

    public function decrement($id, $field, $amount = 1)
    {
        return $this->increment($id, $field, -$amount);
    }

    public function push(array $data)
    {
        $newRef = $this->reference->push();
        $newRef->set($data);
        return $newRef->getKey();
    }

    public function chunk($count, callable $callback)
    {
        $all = $this->all();
        $chunks = array_chunk($all, $count, true);
        foreach ($chunks as $chunk) {
            if ($callback($chunk) === false) {
                break;
            }
        }
    }

    public static function __callStatic($method, $arguments)
    {
        return (new static)->$method(...$arguments);
    }

    public function with($relations)
    {
        if (!is_array($relations)) {
            $relations = [$relations];
        }
        foreach ($relations as $relation) {
            if (method_exists($this, $relation)) {
                $this->$relation();
            } else {
                // Si la relation n'est pas une méthode définie, on suppose qu'il s'agit d'une collection liée
                $relatedData = $this->getReference($relation)->getValue();
                $this->$relation = $relatedData ? $relatedData : [];
            }
        }
        return $this;
    }

    public function withCount($relation)
    {
        $relatedData = $this->getReference($relation)->getValue();
        $this->$relation = $relatedData ? $relatedData : [];
        $this->{$relation . '_count'} = is_array($relatedData) ? count($relatedData) : 0;

        return $this;
    }

    protected function applyFilters($data)
{
    // Vérifier si $data est un tableau
    if (!is_array($data)) {
        return []; // Retourner un tableau vide si $data n'est pas un tableau
    }

    return array_filter($data, function ($item) {
        if (!is_array($item)) {
            return false;
        }
        foreach ($this->filters as $filter) {
            [$field, $operator, $value] = $filter;
            if (!isset($item[$field])) {
                return false;
            }
            if (!$this->evaluateCondition($item[$field], $operator, $value)) {
                return false;
            }
        }
        return true;
    });
}


    protected function evaluateCondition($fieldValue, $operator, $value)
    {
        switch ($operator) {
            case '=':
                return $fieldValue == $value;
            case '>':
                return $fieldValue > $value;
            case '<':
                return $fieldValue < $value;
            case '>=':
                return $fieldValue >= $value;
            case '<=':
                return $fieldValue <= $value;
            case '!=':
                return $fieldValue != $value;
            default:
                return false;
        }
    }

    public function getChild($childPath)
    {
        return $this->reference->getChild($childPath);
    }
}
