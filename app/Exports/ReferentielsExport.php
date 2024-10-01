<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReferentielsExport implements FromCollection, WithHeadings
{
    protected $referentiels;

    public function __construct($referentiels)
    {
        $this->referentiels = $referentiels;
    }

    public function collection()
    {
        return collect($this->referentiels)->map(function ($referentiel) {
            return [
                'id' => $referentiel['id'],
                'code' => $referentiel['code'],
                'libelle' => $referentiel['libelle'],
                'description' => $referentiel['description'],
                'etat' => $referentiel['etat'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Code',
            'Libellé',
            'Description',
            'État',
        ];
    }
}