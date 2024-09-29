<?php

namespace App\Exports;

use App\Interfaces\ApprenantsRepositoryInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ApprenantsExport implements FromCollection, WithHeadings
{
    protected $repository;

    public function __construct(ApprenantsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function collection(): Collection
    {
        return collect($this->repository->all())->map(function ($apprenant) {
            return [
                'id' => $apprenant['id'],
                'nom' => $apprenant['nom'],
                'prenom' => $apprenant['prenom'],
                'email' => $apprenant['email'],
                'date_naissance' => $apprenant['date_naissance'],
                'sexe' => $apprenant['sexe'],
                'referentiel' => $apprenant['referentiel'],
                // Ajoutez d'autres champs selon vos besoins
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nom',
            'Prénom',
            'Email',
            'Date de naissance',
            'Sexe',
            'Référentiel',
            // Ajoutez d'autres en-têtes selon vos besoins
        ];
    }
}