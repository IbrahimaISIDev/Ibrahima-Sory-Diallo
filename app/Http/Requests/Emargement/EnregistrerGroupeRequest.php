<?php

namespace App\Http\Requests\Emargement;

use Illuminate\Foundation\Http\FormRequest;

class EnregistrerGroupeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ajustez selon vos besoins d'autorisation
    }

    public function rules()
    {
        return [
            'apprenant_ids' => 'required|array',
            'apprenant_ids.*' => 'exists:apprenants,id',
            'date' => 'nullable|date',
        ];
    }
}