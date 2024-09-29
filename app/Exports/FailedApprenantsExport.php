<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FailedApprenantsExport implements FromCollection, WithHeadings
{
    protected $failures;

    public function __construct($failures)
    {
        $this->failures = $failures;
    }

    public function collection()
    {
        return collect($this->failures)->map(function ($failure) {
            return [
                $failure->row(),
                $failure->attribute(),
                implode(', ', $failure->errors()),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Ligne',
            'Attribut',
            'Erreurs',
        ];
    }
}