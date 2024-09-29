<?php

// app/Interfaces/NoteServiceInterface.php
namespace App\Interfaces;

interface NoteServiceInterface
{
    public function addNotesToModule(int $moduleId, array $notes): array;
    public function addNotesToApprenant(int $apprenantId, array $notes): array;
    public function updateApprenantNotes(int $apprenantId, array $notes): array;
    public function getNotesForReferentiel(int $referentielId): array;
    public function generateReleveNotesForReferentiel(int $referentielId): string;
    public function generateReleveNotesForApprenant(int $apprenantId): string;
}
