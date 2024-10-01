<?php

// app/Services/NoteService.php
namespace App\Services;

use App\Exports\NotesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\NoteServiceInterface;
use App\Interfaces\NoteRepositoryInterface;

class NoteService implements NoteServiceInterface
{
    protected $noteRepository;

    public function __construct(NoteRepositoryInterface $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function addNotesToModule(int $moduleId, array $notes): array
    {
        return $this->noteRepository->addNotesToModule($moduleId, $notes);
    }

    public function addNotesToApprenant(int $apprenantId, array $notes): array
    {
        return $this->noteRepository->addNotesToApprenant($apprenantId, $notes);
    }

    public function updateApprenantNotes(int $apprenantId, array $notes): array
    {
        return $this->noteRepository->updateApprenantNotes($apprenantId, $notes);
    }

    public function getNotesForReferentiel(int $referentielId): array
    {
        return $this->noteRepository->getNotesForReferentiel($referentielId);
    }

    public function generateReleveNotesForReferentiel(int $referentielId): string
    {
        $notes = $this->getNotesForReferentiel($referentielId);
        $fileName = 'releve_notes_referentiel_' . $referentielId . '.xlsx';
        Excel::store(new NotesExport($notes), $fileName, 'public');
        return $fileName;
    }

    public function generateReleveNotesForApprenant(int $apprenantId): string
    {
        $notes = $this->noteRepository->getNotesForApprenant($apprenantId);
        $fileName = 'releve_notes_apprenant_' . $apprenantId . '.xlsx';
        Excel::store(new NotesExport($notes), $fileName, 'public');
        return $fileName;
    }
}