<?php

namespace App\Imports;

use App\Interfaces\ApprenantsRepositoryInterface;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

class ApprenantsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    protected $repository;
    protected $failures = [];

    public function __construct(ApprenantsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function model(array $row)
    {
        return $this->repository->create([
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'email' => $row['email'],
            'date_naissance' => $row['date_naissance'],
            'sexe' => $row['sexe'],
            'referentiel' => $row['referentiel'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:apprenants,email',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'referentiel' => 'required',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        $this->failures = array_merge($this->failures, $failures);
    }

    public function failures()
    {
        return $this->failures;
    }
}