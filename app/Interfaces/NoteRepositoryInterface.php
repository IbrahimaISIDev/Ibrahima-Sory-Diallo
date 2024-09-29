<?php

// app/Interfaces/NoteRepositoryInterface.php
namespace App\Interfaces;

interface NoteRepositoryInterface
{
    public function addNotesToModule(int $moduleId, array $notes): array;
    public function addNotesToApprenant(int $apprenantId, array $notes): array;
    public function updateApprenantNotes(int $apprenantId, array $notes): array;
    public function getNotesForReferentiel(int $referentielId): array;
    public function getNotesForApprenant(int $apprenantId): array;
}