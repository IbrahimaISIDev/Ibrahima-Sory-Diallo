<?php

namespace App\Http\Requests\Emargement;

use Illuminate\Foundation\Http\FormRequest;

class DeclencherAbsencesRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ajustez selon vos besoins d'autorisation
    }

    public function rules()
    {
        return [
            'date' => 'nullable|date',
        ];
    }
}