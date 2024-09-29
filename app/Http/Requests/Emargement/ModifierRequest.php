<?php

namespace App\Http\Requests\Emargement;

use Illuminate\Foundation\Http\FormRequest;

class ModifierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date' => 'required|date',
            'heure_entree' => 'nullable|date_format:H:i',
            'heure_sortie' => 'nullable|date_format:H:i',
            'statut' => 'nullable|in:present,absent,retard',
        ];
    }
}