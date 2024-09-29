<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PromotionsExport implements FromCollection, WithHeadings
{
    protected $promotions;

    public function __construct($promotions)
    {
        $this->promotions = $promotions;
    }

    public function collection(): Collection
    {
        return collect($this->promotions)->map(function ($promotion) {
            return [
                'id' => $promotion['id'],
                'libelle' => $promotion['libelle'],
                'date_debut' => $promotion['date_debut'],
                'date_fin' => $promotion['date_fin'],
                'duree' => $promotion['duree'],
                'etat' => $promotion['etat'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Libellé',
            'Date de début',
            'Date de fin',
            'Durée',
            'État',
        ];
    }
}