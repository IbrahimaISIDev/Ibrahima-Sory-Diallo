<?php

use App\Models\UserMongodb;
use App\Models\UserFirebase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// Test de la connexion Firebase
Route::get('/test-firebase', function () {
    try {
        Log::info('Tentative de connexion à Firebase');
        $userFirebase = new UserFirebase();
        $users = $userFirebase->all();
        return response()->json([
            'message' => 'Connexion Firebase réussie !',
            'data' => $users
        ]);
    } catch (\Exception $e) {
        Log::error('Erreur de connexion à Firebase : ' . $e->getMessage());
        return response()->json([
            'erreur' => 'Échec de la connexion à Firebase : ' . $e->getMessage(),
        ], 500);
    }
});

// Test de la connexion MongoDB
Route::get('/test-mongodb', function () {
    try {
        $users = UserMongodb::limit(1)->get();
        return response()->json([
            'message' => 'Connexion MongoDB réussie !',
            'data' => $users
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'erreur' => 'Échec de la connexion à MongoDB : ' . $e->getMessage(),
        ], 500);
    }
});