<?php

// app/Http/Controllers/NoteController.php
namespace App\Http\Controllers;

use App\Http\Requests\AddNotesModuleRequest;
use App\Http\Requests\AddNotesApprenantRequest;
use App\Http\Requests\UpdateNotesApprenantRequest;
use App\Interfaces\NoteServiceInterface;
use Illuminate\Http\JsonResponse;

class NoteController extends Controller
{
    protected $noteService;

    public function __construct(NoteServiceInterface $noteService)
    {
        $this->noteService = $noteService;
    }

    public function addNotesToModule(AddNotesModuleRequest $request, int $id): JsonResponse
    {
        $notes = $this->noteService->addNotesToModule($id, $request->validated()['notes']);
        return response()->json($notes, 201);
    }

    public function addNotesToApprenant(AddNotesApprenantRequest $request): JsonResponse
    {
        $notes = $this->noteService->addNotesToApprenant($request->validated()['apprenantId'], $request->validated()['notes']);
        return response()->json($notes, 201);
    }

    public function updateApprenantNotes(UpdateNotesApprenantRequest $request, int $id): JsonResponse
    {
        $notes = $this->noteService->updateApprenantNotes($id, $request->validated()['notes']);
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