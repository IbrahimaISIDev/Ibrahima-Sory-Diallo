<?php

namespace App\Repositories;

use App\Models\Note;
use Kreait\Firebase\Database;
use Illuminate\Support\Facades\Log;
use App\Interfaces\NoteRepositoryInterface;
use Kreait\Firebase\Exception\DatabaseException;

class NoteRepository implements NoteRepositoryInterface
{
    protected $note;
    protected $database;

    public function __construct(Note $note, Database $database)
    {
        $this->note = $note;
        $this->database = $database;
    }


    public function addNotesToModule(int $moduleId, array $notes): array
    {
        $createdNotes = [];
        foreach ($notes as $noteData) {
            $createdNotes[] = $this->note->create([
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
            $createdNotes[] = $this->note->create([
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
            $note = $this->note->find($noteData['noteId']);
            if ($note) {
                $note->update(['note' => $noteData['note']]);
                $updatedNotes[] = $note;
            }
        }
        return $updatedNotes;
    }

    public function getNotesForReferentiel(int $referentielId): array
    {
        try {
            Log::info("Recherche des apprenants pour le référentiel ID: " . $referentielId);

            $apprenants = $this->database->getReference('apprenants')
                ->orderByChild('referentiel_id')
                ->equalTo($referentielId)
                ->getValue();

            Log::info("Apprenants trouvés: " . json_encode($apprenants));

            if (!$apprenants) {
                Log::warning("Aucun apprenant trouvé pour le référentiel ID: " . $referentielId);
                return [];
            }

            $apprenantIds = array_keys($apprenants);
            Log::info("IDs des apprenants: " . json_encode($apprenantIds));

            $notes = $this->database->getReference('notes')->getValue();
            Log::info("Toutes les notes récupérées: " . json_encode($notes));

            $filteredNotes = array_filter($notes, function ($note) use ($apprenantIds) {
                return in_array($note['apprenant_id'], $apprenantIds);
            });

            Log::info("Notes filtrées: " . json_encode($filteredNotes));

            return $filteredNotes ? array_values($filteredNotes) : [];
        } catch (DatabaseException $e) {
            Log::error('Erreur Firebase: ' . $e->getMessage());
            return [];
        }
    }

    public function getNotesForApprenant(int $apprenantId): array
    {
        return $this->note->where('apprenant_id', $apprenantId)
            ->with('module')
            ->get()
            ->toArray();
    }
}
