<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends FirebaseModel
{
    use HasFactory;

    protected $fillable = ['apprenant_id', 'module_id', 'note', 'appreciation'];

    // Définissez le chemin pour la collection Firebase
    protected $path = 'notes'; // Remplacez 'notes' par le chemin de votre collection dans Firebase

    // public function apprenant()
    // {
    //     return $this->belongsTo(Apprenant::class);
    // }

    // public function module()
    // {
    //     return $this->belongsTo(Module::class);
    // }

    // Vous pouvez ajouter d'autres méthodes spécifiques au modèle ici si nécessaire
}
