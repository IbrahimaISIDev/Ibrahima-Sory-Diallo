<?php

// app/Repositories/NoteRepository.php
namespace App\Repositories;

use App\Models\Note;
use App\Interfaces\NoteRepositoryInterface;

class NoteRepository implements NoteRepositoryInterface
{
    public function addNotesToModule(int $moduleId, array $notes): array
    {
        $createdNotes = [];
        foreach ($notes as $noteData) {
            $createdNotes[] = Note::create([
                'apprenant_id' => $noteData['apprenantId'],
                'module_id' => $moduleId,
                'note' => $noteData['note']
            ]);
        }
        return $createdNotes;
    }

    public function addNotesToApprenant(int $apprenantId, array $notes): array
    {
        $createdNotes = [];
        foreach ($notes as $noteData) {
            $createdNotes[] = Note::create([
                'apprenant_id' => $apprenantId,
                'module_id' => $noteData['moduleId'],
                'note' => $noteData['note']
            ]);
        }
        return $createdNotes;
    }

    public function updateApprenantNotes(int $apprenantId, array $notes): array
    {
        $updatedNotes = [];
        foreach ($notes as $noteData) {
            $note = Note::find($noteData['noteId']);
            $note->update(['note' => $noteData['note']]);
            $updatedNotes[] = $note;
        }
        return $updatedNotes;
    }

    public function getNotesForReferentiel(int $referentielId): array
    {
        return Note::whereHas('apprenant', function ($query) use ($referentielId) {
            $query->where('referentiel_id', $referentielId);
        })->with(['apprenant', 'module'])->get()->toArray();
    }

    public function getNotesForApprenant(int $apprenantId): array
    {
        return Note::where('apprenant_id', $apprenantId)
            ->with('module')
            ->get()
            ->toArray();
    }
}