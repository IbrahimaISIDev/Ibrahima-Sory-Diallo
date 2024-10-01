<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReferentielRequest extends FormRequest
{
    public function rules()
    {
        return [
            'code' => 'sometimes|required|string|max:255',
            'libelle' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'photo_couverture' => 'sometimes|nullable|url',
            'etat' => 'sometimes|required|in:active,inactive', // selon vos valeurs possibles
        ];
    }

    public function authorize()
    {
        return true; // Changez cela selon votre logique d'autorisation
    }
}
