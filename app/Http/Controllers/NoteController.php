<?php

// app/Http/Controllers/NoteController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Interfaces\NoteServiceInterface;

class NoteController extends Controller
{
    protected $noteService;

    public function __construct(NoteServiceInterface $noteService)
    {
        $this->noteService = $noteService;
    }

    public function addNotesToModule(Request $request, int $id): JsonResponse
    {
        $notes = $this->noteService->addNotesToModule($id, $request->all()['notes']);
        return response()->json($notes, 201);
    }

    public function addNotesToApprenant(Request $request): JsonResponse
    {
        $notes = $this->noteService->addNotesToApprenant($request->all()['apprenantId'], $request->all()['notes']);
        return response()->json($notes, 201);
    }

    public function updateApprenantNotes(Request $request, int $id): JsonResponse
    {
        $notes = $this->noteService->updateApprenantNotes($id, $request->all()['notes']);
        return response()->json($notes);
    }

    public function getNotesForReferentiel(int $id): JsonResponse
    {
        $notes = $this->noteService->getNotesForReferentiel($id);
        return response()->json($notes);
    }

    public function exportNotesReferentiel(int $id): JsonResponse
    {
        $fileName = $this->noteService->generateReleveNotesForReferentiel($id);
        return response()->json(['file' => $fileName]);
    }

    public function exportNotesApprenant(int $id): JsonResponse
    {
        $fileName = $this->noteService->generateReleveNotesForApprenant($id);
        return response()->json(['file' => $fileName]);
    }
}