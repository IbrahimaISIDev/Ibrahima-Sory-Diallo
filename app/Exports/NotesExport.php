<?php

// app/Exports/NotesExport.php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NotesExport implements FromCollection, WithHeadings
{
    protected $notes;

    public function __construct($notes)
    {
        $this->notes = $notes;
    }

    public function collection()
    {
        return collect($this->notes)->map(function ($note) {
            return [
                'Apprenant' => $note['apprenant']['nom'] . ' ' . $note['apprenant']['prenom'],
                'Module' => $note['module']['nom'],
                'Note' => $note['note'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Apprenant',
            'Module',
            'Note',
        ];
    }
}
