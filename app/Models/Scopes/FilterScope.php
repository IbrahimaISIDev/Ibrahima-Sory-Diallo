<?php
namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class FilterScope implements Scope
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }
   
    public function apply(Builder $builder, Model $model)
    {
        foreach ($this->filters as $field => $value) {
            if ($field === 'libelle') {
                $builder->where($field, 'like', "%{$value}%");
            } else {
                $builder->where($field, $value);
            }
        }
    }
}